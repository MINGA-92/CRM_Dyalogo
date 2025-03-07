<?php
    /**
     *JDBD - Esta crea espacios para generar el codigo identado.
     *@param int - cantidad de espacios.
     *@return string. 
     */
    function IDNT($intCant_p){
        $espacios = '';
        for ($i=0; $i < $intCant_p; $i++) { 
            $espacios .= ' ';
        }
        return $espacios;
    }

    function NombreParaFormula($strNombre_p)
    {   
        $strNombre_t = trim($strNombre_p);

        $arrBuscar_t = ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë','í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î','ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü','ñ', 'Ñ', 'ç', 'Ç'];

        $arrCambiar_t = ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A','e', 'e', 'e', 'e', 'E', 'E', 'E', 'E','i', 'i', 'i', 'i', 'I', 'I', 'I', 'I','o', 'o', 'o', 'o', 'O', 'O', 'O', 'O','u', 'u', 'u', 'u', 'U', 'U', 'U', 'U','n', 'N', 'c', 'C'];

        $strNombre_t = str_replace($arrBuscar_t, $arrCambiar_t, $strNombre_t);
        $strNombre_t = preg_replace("/[\s]/", "_", $strNombre_t);
        $strNombre_t = preg_replace("/[^A-Za-z0-9_]/", "", $strNombre_t);   
        for ($i=0; $i < 10; $i++) {
            $strNombre_t = str_replace("__", "_", $strNombre_t);
        }
        $strNombre_t = substr($strNombre_t,0,20);

        return $strNombre_t; 
    }

    function sanear_stringsXYZ($string) { 

       // $string = utf8_decode($string);

        $string = str_replace( array('Ã¡', 'Ã ', 'Ã¤', 'Ã¢', 'Âª', 'Ã', 'Ã€', 'Ã‚', 'Ã„'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
        $string = str_replace( array('Ã©', 'Ã¨', 'Ã«', 'Ãª', 'Ã‰', 'Ãˆ', 'ÃŠ', 'Ã‹'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string ); 
        $string = str_replace( array('Ã­', 'Ã¬', 'Ã¯', 'Ã®', 'Ã', 'ÃŒ', 'Ã', 'ÃŽ'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string ); 
        $string = str_replace( array('Ã³', 'Ã²', 'Ã¶', 'Ã´', 'Ã“', 'Ã’', 'Ã–', 'Ã”'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string ); 
        $string = str_replace( array('Ãº', 'Ã¹', 'Ã¼', 'Ã»', 'Ãš', 'Ã™', 'Ã›', 'Ãœ'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string ); 
        $string = str_replace( array('Ã±', 'Ã‘', 'Ã§', 'Ã‡'), array('n', 'N', 'c', 'C',), $string ); 
        //Esta parte se encarga de eliminar cualquier caracter extraÃ±o 
        $string = str_replace( array("\\", "Â¨", "Âº", "-", "~", "#", "@", "|", "!", "\"", "Â·", "$", "%", "&", "/", "(", ")", "?", "'", "Â¡", "Â¿", "[", "^", "`", "]", "+", "}", "{", "Â¨", "Â´", ">â€œ, â€œ< ", ";", ",", ":", "."), '', $string ); 
        return $string; 
    }

    function generateOptionButton(int $lista, string $id):string
    {
        global $mysqli;
        global $BaseDatos_systema;

        (string) $html='';
        (object) $Lsql = $mysqli->query("SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = {$lista} ORDER BY LISOPC_Nombre____b ASC LIMIT 10");
        if($Lsql && $Lsql->num_rows > 0){
            while($row = $Lsql->fetch_object()){
                $html.="<div class='radio'>";
                $html.="<label>";
                $html.="<input type='radio' class='{$id}' name='{$id}' id='opt_{$row->OPCION_ConsInte__b}' value='{$row->OPCION_ConsInte__b}'>{$row->OPCION_Nombre____b}";
                $html.="</label>";
                $html.="</div>";
            }
        }
        return $html;
    }

    function lastItemPestana(int $seccion, int $guion):string
    {
        global $mysqli;

        $response='';
        $sql=$mysqli->query("SELECT MAX(SECCIO_ConsInte__b) AS SECCIO_ConsInte__b FROM DYALOGOCRM_SISTEMA.SECCIO WHERE SECCIO_ConsInte__GUION__b = {$guion} AND SECCIO_VistPest__b=5 ORDER BY SECCIO_Orden_____b ASC");
        if($sql && $sql->num_rows > 0){
            $sql=$sql->fetch_object();
            if($seccion == $sql->SECCIO_ConsInte__b){
                $response='</div>
                    </div>
                </div>';
            }
        }

        return $response;
    }

    function validateURL($cadena)
    {
        $cadena=explode(" ",$cadena);
        if(count($cadena) > 0){
            foreach($cadena as $item=>$palabra){
                if(strstr($palabra,"https://")){
                    $cadena[$item]="<a href='{$palabra}' target='_blank'>{$palabra}</a>";
                }
            }
        }
        
        return join(" ",$cadena);
    }

    function generar_Formulario_Script($id_a_generar, $Formulariopadre=null){

        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        global $BaseDatos_telefonia;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_general;
        global $URL_SERVER;

        $Lsql_Tipo = "SELECT GUION__Tipo______b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$id_a_generar;
        $res_Lsql_Tipo = $mysqli->query($Lsql_Tipo);
        $datoArray = $res_Lsql_Tipo->fetch_array();

        //BUSCAMOS SI HAY QUE HACER ACCIONES AL CARGAR EL FORMULARIO Ó AL CERRAR GESTIÓN
        $sqlActionLoad=$mysqli->query("SELECT PREGUN_consInte__ws_B,PREGUN_ConsInte__b,PREGUN_FormaIntegrarWS_b FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b={$id_a_generar} AND PREGUN_Tipo______b=17 AND PREGUN_ConsInte__SECCIO_b=0 AND (PREGUN_FormaIntegrarWS_b=1 OR PREGUN_FormaIntegrarWS_b=3)");
        $strActionLoad='';
        $strActionFinish='';
        if($sqlActionLoad && $sqlActionLoad->num_rows > 0){
            while($action = $sqlActionLoad->fetch_object()){
                if($action->PREGUN_FormaIntegrarWS_b == 1){
                    $strActionLoad.="llamarIntegracionWS({$action->PREGUN_consInte__ws_B},{$action->PREGUN_ConsInte__b},{$id_a_generar})"."\n";
                }

                if($action->PREGUN_FormaIntegrarWS_b == 3){
                    $strActionFinish.="llamarIntegracionWS({$action->PREGUN_consInte__ws_B},{$action->PREGUN_ConsInte__b},{$id_a_generar})"."\n";
                }
            }
        }
        
        if($id_a_generar != 0){
            $cEc="";
            $cCc="";
            $cCac="";
            $cAc="";
            if ($datoArray['GUION__Tipo______b'] == 1) {
                //JDBD campo ESTADO_CALIDAD_Q_DY
                $cEc = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Texto_____b = 'ESTADO_CALIDAD_Q_DY';";
                $cEc = $mysqli->query($cEc);
                if ($cEc->num_rows > 0) {
                    $cEc = $cEc->fetch_object();
                    $cEc = $cEc->id;
                }else{
                    $cEc="";
                }

                //JDBD campo CALIFICACION_Q_DY
                $cCc = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Texto_____b = 'CALIFICACION_Q_DY';";
                $cCc = $mysqli->query($cCc);
                if ($cCc->num_rows > 0) {
                    $cCc = $cCc->fetch_object();
                    $cCc = $cCc->id;
                }else{
                    $cCc="";
                }

                //JDBD campo COMENTARIO_CALIDAD_Q_DY
                $cCac = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Texto_____b = 'COMENTARIO_CALIDAD_Q_DY';";
                $cCac = $mysqli->query($cCac);
                if ($cCac->num_rows > 0) {
                    $cCac = $cCac->fetch_object();
                    $cCac = $cCac->id;
                }else{
                    $cCac="";
                }


                //JDBD campo COMENTARIO_AGENTE_Q_DY
                $cAc = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Texto_____b = 'COMENTARIO_AGENTE_Q_DY';";
                $cAc = $mysqli->query($cAc);
                if ($cAc->num_rows > 0) {
                    $cAc = $cAc->fetch_object();
                    $cAc = $cAc->id;
                }else{
                    $cAc="";
                }
            }
            $strConfirmamosQueElGuionTieneAdjuntos_t = 0;
            $AjaxEnviarCalificacion = '';
            $modalCorreoCalidad = '';
            $AjaxEnviarFin = '';
            $condicionCalidad = '';
            $MostrarCalidad = '';
            $btnDownCall = '';
            $responderAjax = '';
            $crearAjax = '';
            $idCalidad='';
            $funciones_js = '';
            $funciones_jsx = '';
            $funciones_jsY = '';
            $guion = 'G'.$id_a_generar;
            $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z','aa', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az','ba','bb','bc','bd','be','bf','bg','bh','bi','bj','bk','bl','bm','bn','bo','bp','bq','br','bs','bt','bu','bv','bw','bx','by','bz','ca','cb','cc','cd','ce','cf','cg','ch','ci','cj','ck','cl','cm','cn','co','cp','cq','cr','cs','ct','cu','cv','cw','cx','cy','cz','da','db','dc','dd','de','df','dg','dh','di','dj','dk','dl','dm','dn','do','dp','dq','dr','ds','dt','du','dv','dw','dx','dy','dz','ea','eb','ec','ed','ee','ef','eg','eh','ei','ej','ek','el','em','en','eo','ep','eq','er','es','et','eu','ev','ew','ex','ey','ez','fa','fb','fc','fd','fe','ff','fg','fh','fi','fj','fk','fl','fm','fn','fo','fp','fq','fr','fs','ft','fu','fv','fw','fx','fy','fz','ga','gb','gc','gd','ge','gf','gg','gh','gi','gj','gk','gl','gm','gn','go','gp','gq','gr','gs','gt','gu','gv','gw','gx','gy','gz','ha','hb','hc','hd','he','hf','hg','hh','hi','hj','hk','hl','hm','hn','ho','hp','hq','hr','hs','ht','hu','hv','hw','hx','hy','hz','ia','ib','ic','id','ie','if','ig','ih','ii','ij','ik','il','im','in','io','ip','iq','ir','is','it','iu','iv','iw','ix','iy','iz','ja','jb','jc','jd','je','jf','jg','jh','ji','jj','jk','jl','jm','jn','jo','jp','jq','jr','js','jt','ju','jv','jw','jx','jy','jz');

            $guion_c = $guion."_C";
            $guionSelect2 ='';
            $Lsql = "SELECT PREGUN_ConsInte_PREGUN_Depende_b as depende, PREGUN_Texto_____b as titulo_pregunta, G6.G6_C51 as requerido , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b,SECCIO_TipoSecc__b, PREGUN_Default___b, PREGUN_ContAcce__b , PREGUN_DefaNume__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b LEFT JOIN ".$BaseDatos_systema.".G6 ON PREGUN_ConsInte__b = G6_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Tipo______b != 12 AND PREGUN_Tipo______b != 16 AND PREGUN_Tipo______b != 17 ORDER BY PREGUN_ConsInte__SECCIO_b ASC, PREGUN_OrdePreg__b ASC";

            $Lsql2 = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b, PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_DefaNume__b , PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Tipo______b != 12  ORDER BY PREGUN_ConsInte__SECCIO_b ASC, PREGUN_OrdePreg__b ASC";

            $LsqlCamposPrimairos = "SELECT GUION__ConsInte__PREGUN_Pri_b,  GUION__ConsInte__PREGUN_Sec_b, GUION__ConsInte__PREGUN_Tip_b, GUION__ConsInte__PREGUN_Rep_b, GUION__ConsInte__PREGUN_Fag_b, GUION__ConsInte__PREGUN_Hag_b, GUION__ConsInte__PREGUN_Com_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$id_a_generar ." AND GUION__Tipo______b = 1";

            //JDBD - Esta variable me sirve para guardar el funcionamiento del campo con valor definido, y ponerlo en el codigo para que funcione desde backoffice.
            $strCampoConValorDefinido_t = '';
            //JDBD - Esta variable me sirve para guardar la condicion para la respuesta de ajax de incrementar numero, que ira en el CRUD.php
            $strAjaxIncrementable_t = '';
            $iTsub = [];
            $idSub = [];
            $funcionRecargaTabSub = '';
            $GUION__ConsInte__PREGUN_Pri_b = null;
            $GUION__ConsInte__PREGUN_Sec_b = null;
            $GUION__ConsInte__PREGUN_Tip_b = null;
            $GUION__ConsInte__PREGUN_Rep_b = null;
            $GUION__ConsInte__PREGUN_Fag_b = null;
            $GUION__ConsInte__PREGUN_Hag_b = null;
            $GUION__ConsInte__PREGUN_Com_b = null;

            //echo $LsqlCamposPrimairos;
            $camposBuscadosIzquierda = $mysqli->query($LsqlCamposPrimairos);
            while($key = $camposBuscadosIzquierda->fetch_object()){
                $GUION__ConsInte__PREGUN_Tip_b = $key->GUION__ConsInte__PREGUN_Tip_b;
                $GUION__ConsInte__PREGUN_Rep_b = $key->GUION__ConsInte__PREGUN_Rep_b;
                $GUION__ConsInte__PREGUN_Fag_b = $key->GUION__ConsInte__PREGUN_Fag_b;
                $GUION__ConsInte__PREGUN_Hag_b = $key->GUION__ConsInte__PREGUN_Hag_b;
                $GUION__ConsInte__PREGUN_Com_b = $key->GUION__ConsInte__PREGUN_Com_b;
            }

            $LsqlCamposPrimairos = "SELECT GUION__ConsInte__PREGUN_Pri_b,  GUION__ConsInte__PREGUN_Sec_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$id_a_generar;

            //echo $LsqlCamposPrimairos." Estos ERAN ";

            $camposBuscadosIzquierda = $mysqli->query($LsqlCamposPrimairos);
            while($key = $camposBuscadosIzquierda->fetch_object()){
                $GUION__ConsInte__PREGUN_Pri_b = $key->GUION__ConsInte__PREGUN_Pri_b;
                $GUION__ConsInte__PREGUN_Sec_b = $key->GUION__ConsInte__PREGUN_Sec_b;
            }

            /*echo " SON ESTOS PRIMARIO => ".$GUION__ConsInte__PREGUN_Pri_b."\n".
            "SON ESTOS GUION__ConsInte__PREGUN_Sec_b => ".$GUION__ConsInte__PREGUN_Sec_b."\n".
            "SON ESTOS GUION__ConsInte__PREGUN_Tip_b => ".$GUION__ConsInte__PREGUN_Tip_b."\n".
            "SON ESTOS GUION__ConsInte__PREGUN_Rep_b => ".$GUION__ConsInte__PREGUN_Rep_b."\n".
            "SON ESTOS GUION__ConsInte__PREGUN_Fag_b => ".$GUION__ConsInte__PREGUN_Fag_b."\n".
            "SON ESTOS GUION__ConsInte__PREGUN_Hag_b => ".$GUION__ConsInte__PREGUN_Hag_b."\n".
            "SON ESTOS GUION__ConsInte__PREGUN_Com_b => ".$GUION__ConsInte__PREGUN_Com_b."\n";*/
            
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $carpeta = "C:/xampp/htdocs/crm_php/formularios/".$guion;
            } else {
                $carpeta = "/var/www/html/crm_php/formularios/".$guion;
            }
            
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777);
            }

            /* abrimos el archivo que vamos a crear */
            $fp = fopen($carpeta."/".$guion.".php" , "w");
            //chmod($carpeta."/".$guion.".php" , 0777);

            //echo "Va a ser esto  Linea 2331 => ".$Lsql2;
            $campos_6 = $mysqli->query($Lsql2);
            $camposTabla = '';
            $ordenTabla = '';
            $campTabla = '';
            $joinsTabla = '';
            $j = 0;

            while($key = $campos_6->fetch_object()){
                if($key->id == $GUION__ConsInte__PREGUN_Pri_b){
                    if($key->tipo_Pregunta == '6'){
                        if($j == 0){
                            $camposTabla .= $alfabeto[$j].'.LISOPC_Nombre____b as camp1';
                            $ordenTabla .= $guion_c.$key->id;
                        }else{
                            $camposTabla .= ' , '.$alfabeto[$j].'.LISOPC_Nombre____b as camp1';
                            $campTabla .=    $guion_c.$key->id;
                        }
                        $joinsTabla .= ' LEFT JOIN ".$BaseDatos_systema.".LISOPC as '.$alfabeto[$j].' ON '.$alfabeto[$j].'.LISOPC_ConsInte__b = '. $guion_c.$key->id;
                    }else if($key->tipo_Pregunta == '11'){
                        $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                        $campoPrimario = '';
                        $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);

                        while($key2 = $camposBuscadosIzquierda_2->fetch_object()){
                            $campoPrimario = $key2->GUION__ConsInte__PREGUN_Pri_b;
                        }

                        if($j == 0){
                            $camposTabla .='G'.$key->guion.'_C'.$campoPrimario.' as camp1';
                            $ordenTabla .= $guion_c.$key->id;
                        }else{
                            $camposTabla .= 'G'.$key->guion.'_C'.$campoPrimario.' as camp1';
                            $campTabla .=   $guion_c.$key->id;
                        }
                        $joinsTabla .= ' LEFT JOIN ".$BaseDatos.".G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b = '.$guion_c.$key->id;
                    }else{
                        if($j == 0){
                            $camposTabla .= $guion_c.$key->id.' as camp1';
                            $ordenTabla = $guion_c.$key->id;
                        }else{
                            $camposTabla .= ' , '.$guion_c.$key->id.' as camp1';
                            $campTabla .=    $guion_c.$key->id;
                        }
                    }
                    $j++;
                }

                if($key->id == $GUION__ConsInte__PREGUN_Sec_b){
                    if($key->tipo_Pregunta == '6'){
                        if($j == 0){
                            $camposTabla .= $alfabeto[$j].'.LISOPC_Nombre____b as camp2';
                            $ordenTabla .= $guion_c.$key->id;
                        }else{
                            $camposTabla .= ' , '.$alfabeto[$j].'.LISOPC_Nombre____b as camp2';
                            $campTabla .=    $guion_c.$key->id;
                        }
                        $joinsTabla .= ' LEFT JOIN ".$BaseDatos_systema.".LISOPC as '.$alfabeto[$j].' ON '.$alfabeto[$j].'.LISOPC_ConsInte__b = '. $guion_c.$key->id;

                    }else if($key->tipo_Pregunta == '11'){
                        $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                        $campoPrimario = '';
                        $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);

                        while($key2 = $camposBuscadosIzquierda_2->fetch_object()){
                            $campoPrimario = $key2->GUION__ConsInte__PREGUN_Pri_b;
                        }

                        if($j == 0){
                            $camposTabla .='G'.$key->guion.'_C'.$campoPrimario.' as camp1';
                            $ordenTabla .= $guion_c.$key->id;
                        }else{
                            $camposTabla .= 'G'.$key->guion.'_C'.$campoPrimario.' as camp1';
                            $campTabla .=   $guion_c.$key->id;
                        }
                        $joinsTabla .= ' LEFT JOIN ".$BaseDatos.".G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b = '.$guion_c.$key->id;

                    
                    }else{
                        if($j == 0){
                            $camposTabla .= $guion_c.$key->id.' as camp2';
                            $ordenTabla .= $guion_c.$key->id;
                        }else{
                            $camposTabla .= ' , '.$guion_c.$key->id.' as camp2';
                            $campTabla =    $guion_c.$key->id;
                        }
                    }
                    $j++;
                }
            }

            $camposValidaciones = '';
            $valoresValidados = $mysqli->query($Lsql);
            $fechaValidadaOno = '';
            $horaValidadaOno = '';
            $botonSalvar = '';
            $hayqueValidar = 0;
            $select2 = '';
            $funcionesCampoGuion = '';
            $numeroFuncion = '';
            $decimalFuncion = '';

            $primerCamposJoin ='0';
            $joins = '';

            while ($key = $valoresValidados->fetch_object()) {
              if ($key->PREGUN_ContAcce__b != 2 && $key->requerido == -1) {

                if($key->tipo_Pregunta == '3' || $key->tipo_Pregunta == '4'){
                        $camposValidaciones .= "\n".'
            if(($("#'.$guion_c.$key->id.'").val() == "") && $("#'.$guion_c.$key->id.'").prop("disabled") == false){
                alertify.error(\''.$key->titulo_pregunta.' debe ser diligenciado\');
                $("#'.$guion_c.$key->id.'").closest(".form-group").addClass("has-error");
                valido = 1;
            }';
                    if(!is_null($key->minimoNumero) && !is_null($key->maximoNumero) ){
                        $camposValidaciones .= "\n".'
            if($("#'.$guion_c.$key->id.'").val().length > 0){
                if( $("#'.$guion_c.$key->id.'").val() > '.($key->minimoNumero - 1).' && $("#'.$guion_c.$key->id.'").val() < '.($key->maximoNumero + 1).'){

                }else{
                    alertify.error(\''.$key->error.'\');
                    $("#'.$guion_c.$key->id.'").focus();
                    valido =1;
                }    
            }';
                    }else if(!is_null($key->minimoNumero) && is_null($key->maximoNumero)){
                        $camposValidaciones .= "\n".'
            if($("#'.$guion_c.$key->id.'").val().length > 0){
                if( $("#'.$guion_c.$key->id.'").val() > '.($key->minimoNumero - 1).'){
                    
                }else{
                    alertify.error(\''.$key->error.'\');
                    $("#'.$guion_c.$key->id.'").focus();
                    valido =1;
                }    
            }';
                    }else if(is_null($key->minimoNumero) && !is_null($key->maximoNumero)){
                        $camposValidaciones .= "\n".'
            if($("#'.$guion_c.$key->id.'").val().length > 0){
                if(  $("#'.$guion_c.$key->id.'").val() < '.($key->maximoNumero + 1).'){
                    
                }else{
                    alertify.error(\''.$key->error.'\');
                    $("#'.$guion_c.$key->id.'").focus();
                    valido =1;
                }    
            }';
                    }
                }

                if($key->tipo_Pregunta == '1' || $key->tipo_Pregunta == '2' || $key->tipo_Pregunta == '5' || $key->tipo_Pregunta == '10'){
                        $camposValidaciones .= "\n".'
            if(($("#'.$guion_c.$key->id.'").val() == "") && $("#'.$guion_c.$key->id.'").prop("disabled") == false){
                alertify.error(\''.$key->titulo_pregunta.' debe ser diligenciado\');
                $("#'.$guion_c.$key->id.'").closest(".form-group").addClass("has-error");
                valido = 1;
            }';
                }

                if($key->tipo_Pregunta == '6' || $key->tipo_Pregunta == '13' || $key->tipo_Pregunta == '11'){
                        $camposValidaciones .= "\n".'
            if(($("#'.$guion_c.$key->id.'").val()==0 || $("#'.$guion_c.$key->id.'").val() == null || $("#'.$guion_c.$key->id.'").val() == -1) && $("#'.$guion_c.$key->id.'").prop("disabled") == false){
                alertify.error(\''.$key->titulo_pregunta.' debe ser diligenciado\');
                $("#'.$guion_c.$key->id.'").closest(".form-group").addClass("has-error");
                valido = 1;
            }';
                }

                //if(!is_null($key->minimoNumero) || !is_null($key->maximoNumero)){
                    $hayqueValidar += 1;
                //}
              }
                if($key->tipo_Pregunta == '3' ){
                $numeroFuncion .= '
        $("#'.$guion_c.$key->id.'").numeric();
                ';    
                }

                if($key->tipo_Pregunta == '4' ){
                $decimalFuncion .= '
        $("#'.$guion_c.$key->id.'").numeric({ decimal : ".",  negative : false, scale: 4 });
                ';
                }

                if($key->tipo_Pregunta == '5'){
                  if ($key->PREGUN_ContAcce__b != '2'){ 
                    if(!is_null($key->fechaMinimo) && !is_null($key->fechaMaximo) ){
                        $fechaValidadaOno .= "\n".'
        var startDate = new Date(\''.$key->fechaMinimo.'\');
        var FromEndDate = new Date(\''.$key->fechaMaximo.'\');
        $("#'.$guion_c.$key->id.'").datepicker({
            language: "es",
            autoclose: true,
            startDate: startDate,
            endDate : FromEndDate,
            todayHighlight: true
        });
        $("#DTP_'.$guion_c.$key->id.'").click(function(){
            $("#'.$guion_c.$key->id.'").focus();
        });';
                      }else if(!is_null($key->fechaMinimo) && is_null($key->fechaMaximo)){
                          if($key->fechaMinimo == '0001-01-01 00:00:00'){
                            $startDate="var startDate = 'today';";
                          }else{
                            $startDate="var startDate = new Date('{$key->fechaMinimo}');";
                          }
                        $fechaValidadaOno .= "\n".'
        '.$startDate.'
        $("#'.$guion_c.$key->id.'").datepicker({
            language: "es",
            autoclose: true,
            startDate: startDate,
            todayHighlight: true
        });
        $("#DTP_'.$guion_c.$key->id.'").click(function(){
            $("#'.$guion_c.$key->id.'").focus();
        });';
                    }else if(is_null($key->fechaMinimo) && !is_null($key->fechaMaximo)){
                        $fechaValidadaOno .= "\n".'
        var FromEndDate = new Date(\''.$key->fechaMaximo.'\');
        $("#'.$guion_c.$key->id.'").datepicker({
            language: "es",
            autoclose: true,
            endDate : FromEndDate,
            todayHighlight: true
        });
        $("#DTP_'.$guion_c.$key->id.'").click(function(){
            $("#'.$guion_c.$key->id.'").focus();
        });';
                    }else{
                        $fechaValidadaOno .= "\n".'
        $("#'.$guion_c.$key->id.'").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        $("#DTP_'.$guion_c.$key->id.'").click(function(){
            $("#'.$guion_c.$key->id.'").focus();
        });';
                    }
                  }  
                }

                if($key->tipo_Pregunta == '10'){
                  if ($key->PREGUN_ContAcce__b != '2'){ 
                    if(!is_null($key->horaMini) && !is_null($key->horaMaximo)){
                        $horaValidadaOno .= "\n".'
        //Timepicker
        var options = {  //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker\'s input clearable (has clickable "x")
        }; 
        $("#'.$guion_c.$key->id.'").change(function(){
            let hora=true;
            if($(this).val() != ""){
                try{
                    hora="2022-04-05 "+$(this).val();
                }catch(e){
                    hora=true;
                }    
            }
            $("#'.$guion_c.$key->id.'").datetimepicker({
                format:"HH:mm:ss",
                //useCurrent:hora
            }).on("dp.hide", function(ev){ $("#'.$guion_c.$key->id.'").val($(this).val()).trigger("change")});
        });
        $("#'.$guion_c.$key->id.'").change();
        $("#TMP_'.$guion_c.$key->id.'").click(function(){
            $("#'.$guion_c.$key->id.'").focus();
        });';
                    }else if(!is_null($key->horaMini) && is_null($key->horaMaximo)){
                        $horaValidadaOno .= "\n".'
        //Timepicker
        var options = {  //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker\'s input clearable (has clickable "x")
        };

        $("#'.$guion_c.$key->id.'").change(function(){
            let hora=true;
            if($(this).val() != ""){
                try{
                    hora="2022-04-05 "+$(this).val();
                }catch(e){
                    hora=true;
                }
            }
            $("#'.$guion_c.$key->id.'").datetimepicker({
                format:"HH:mm:ss",
                //useCurrent:hora
            }).on("dp.hide", function(ev){ $("#'.$guion_c.$key->id.'").val($(this).val()).trigger("change")});
        });
        $("#'.$guion_c.$key->id.'").change();
        $("#TMP_'.$guion_c.$key->id.'").click(function(){
            $("#'.$guion_c.$key->id.'").focus();
        });';
                    }else if(is_null($key->horaMini) && !is_null($key->horaMaximo)){
                        $horaValidadaOno .= "\n".'
        //Timepicker
        var options = { //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker\'s input clearable (has clickable "x")
        }; 
        $("#'.$guion_c.$key->id.'").change(function(){
            let hora=true;
            if($(this).val() != ""){
                try{
                    hora="2022-04-05 "+$(this).val();
                }catch(e){
                    hora=true;
                }
            }
            $("#'.$guion_c.$key->id.'").datetimepicker({
                format:"HH:mm:ss",
                //useCurrent:hora
            }).on("dp.hide", function(ev){ $("#'.$guion_c.$key->id.'").val($(this).val()).trigger("change")});
        });
        $("#'.$guion_c.$key->id.'").change();
        $("#TMP_'.$guion_c.$key->id.'").click(function(){
            $("#'.$guion_c.$key->id.'").focus();
        });';
                    }else{
                        $horaValidadaOno .= "\n".'
        //Timepicker
        var options = { //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker\'s input clearable (has clickable "x")
        }; 
        $("#'.$guion_c.$key->id.'").change(function(){
            let hora=true;
            if($(this).val() != ""){
                try{
                    hora="2022-04-05 "+$(this).val();
                }catch(e){
                    hora=true;
                }
            }
            $("#'.$guion_c.$key->id.'").datetimepicker({
                format:"HH:mm:ss",
                //useCurrent:hora
            }).on("dp.hide", function(ev){ $("#'.$guion_c.$key->id.'").val($(this).val()).trigger("change")});
        });
        $("#'.$guion_c.$key->id.'").change();
        $("#TMP_'.$guion_c.$key->id.'").click(function(){
            $("#'.$guion_c.$key->id.'").focus();
        });'; 
                    }
                }

              }
              
            }

            /* preguntamos si toca validar los campos obligatorios o no */
            if($hayqueValidar > 0){
                $validarCampos = $camposValidaciones;
            }else{
                $validarCampos = '';
            }
           
            $botonSalvar = "\n".'
        function cierroGestion(){
                var bol_respuesta = before_save();
                if(bol_respuesta){            
                    $("#Save").attr("disabled",true);
                    var form = $("#FormularioDatos");
                    //Se crean un array con los datos a enviar, apartir del formulario 
                    var formData = new FormData($("#FormularioDatos")[0]);
                    $.ajax({
                       url: \'<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?=$idUsuario?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET[\'id_gestion_cbx\'])){ echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>&campana_crm=<?php if(isset($_GET[\'campana_crm\'])){ echo $_GET[\'campana_crm\']; } else{ echo "0"; } ?>\',  
                        type: \'POST\',
                        data: formData,
                        dataType: "JSON",
                        cache: false,
                        contentType: false,
                        processData: false,
                        //una vez finalizado correctamente
                        success: function(data){
                            try{
                                afterSave(data);
                            }catch(e){}
                            if(data.estado == \'ok\'){
                                '.$strActionFinish.'
                                <?php if(!isset($_GET[\'campan\'])){ ?>
                                    if($("#calidad").val() =="0"){
                                    
                                    //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                    if($("#oper").val() == \'add\'){
                                        idTotal = data.mensaje;
                                    }else{
                                        idTotal= $("#hidId").val();
                                    }
                                   
                                    //Limpiar formulario
                                    form[0].reset();
                                    after_save();
                                    <?php if(isset($_GET[\'registroId\'])){ ?>
                                        var ID = <?=$_GET[\'registroId\'];?>
                                    <?php }else{ ?> 
                                        var ID = data.mensaje;
                                    <?php } ?>  
                                    $.ajax({
                                        url      : \'<?=$url_crud;?>\',
                                        type     : \'POST\',
                                        data     : { CallDatos : \'SI\', id : ID },
                                        dataType : \'json\',
                                        success  : function(data){
                                            //recorrer datos y enviarlos al formulario
                                            $.each(data, function(i, item) {
                                            ';
        $campos_3 = $mysqli->query($Lsql);
        while ($key = $campos_3->fetch_object()){
            if($key->tipo_Pregunta != '8' && $key->tipo_Pregunta != '9'){
              //JDBD-2020-05-10 : Preguntamos si el campo es algun tipo de lista.
                if($key->tipo_Pregunta == '11' || $key->tipo_Pregunta == '13' || $key->tipo_Pregunta == '6'){
                    $conSiGuidet = "SELECT GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__PREGUN_De1_b = ".$key->id." AND GUIDET_ConsInte__PREGUN_Ma1_b IS NULL";
                    $exisRela = $mysqli->query($conSiGuidet);

                    if ($exisRela->num_rows > 0) {
                        $botonSalvar .="\n".'$.ajax({
                        url      : \'<?=$url_crud;?>?Combo_Guion_'.$guion_c.$key->id.'=si\',
                        type     : \'POST\',
                        data     : { q : item.'.$guion_c.$key->id.' },
                        type : \'post\',
                        success  : function(data){
                            $("#'.$guion_c.$key->id.'").html(data);
                        }});';
                    }else{
                        if ($key->tipo_Pregunta == '6' && !is_null($key->depende) && $key->depende != 0) {
                            $botonSalvar .="\n".' 
                            $("#'.$guion_c.$key->id.'").attr("opt",item.'.$guion_c.$key->id.'); ';
                        }else if ($key->tipo_Pregunta == '11'){
                            $botonSalvar .="\n".' 
                            $("#'.$guion_c.$key->id.'").attr("opt",item.'.$guion_c.$key->id.');
                            $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change");';
                        }else{
                            $botonSalvar .="\n".' 
                            $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change");
                            $("#opt_"+item.'.$guion_c.$key->id.').prop("checked",true).trigger("change"); ';
                        }
                        
                    }

                }else{  
                    if ($key->tipo_Pregunta == '15') {
                        $botonSalvar .= "\n".' 
                        if (item.'.$guion_c.$key->id.'){
                            $("#down'.$guion_c.$key->id.'").attr("adjunto",item.'.$guion_c.$key->id.');
                            var lenURL_t = item.'.$guion_c.$key->id.'.split("/");
                            $("#down'.$guion_c.$key->id.'").val(lenURL_t[lenURL_t.length - 1]);
                        }else{
                            $("#down'.$guion_c.$key->id.'").attr("adjunto","");
                            $("#down'.$guion_c.$key->id.'").val("Sin Adjunto");
                            $("#'.$guion_c.$key->id.'").val("");
                        }';
                    }else if($key->tipo_Pregunta == '10'){
                        $botonSalvar .="\n".'
                        $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change"); ';
                    }else{

                        $botonSalvar .= "\n".' 
                        $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.');';

                    }
                }
            }else{
                $botonSalvar .= "\n".'      
                if(item.'.$guion_c.$key->id.' == "1"){
                    $("#'.$guion_c.$key->id.'").prop(\'checked\', true);
                }else{
                    $("#'.$guion_c.$key->id.'").prop(\'checked\', false);
                } ';
            }
        }
                      $botonSalvar .= '
                                                $("#h3mio").html(item.principal);
                                            });

                                            //Deshabilitar los campos 2

                                            //Habilitar todos los campos para edicion
                                            $(\'#FormularioDatos :input\').each(function(){
                                                $(this).attr(\'disabled\', true);
                                            });

                                            //Habilidar los botones de operacion, add, editar, eliminar
                                            $("#add").attr(\'disabled\', false);
                                            $("#edit").attr(\'disabled\', false);
                                            $("#delete").attr(\'disabled\', false);

                                            //Desahabiliatra los botones de salvar y seleccionar_registro
                                            $("#cancel").attr(\'disabled\', true);
                                            $("#Save").attr(\'disabled\', true);
                                        } 
                                    })
                                    $("#hidId").val(ID);  
                                    }else{
                                        $("#calidad").val("0");
                                    }
                                <?php }else{ 
                                    if(!isset($_GET[\'formulario\'])){
                                ?>

                                    $.ajax({
                                        url   : \'formularios/generados/PHP_Ejecutar.php?action=EDIT&tiempo=<?php echo $tiempoDesdeInicio;?>&usuario=<?=$idUsuario?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?>&ConsInteRegresado=\'+data.mensaje +\'<?php if(isset($_GET[\'token\'])) { echo "&token=".$_GET[\'token\']; }?><?php if(isset($_GET[\'id_gestion_cbx\'])) { echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?>&campana_crm=<?php if(isset($_GET[\'campana_crm\'])){ echo $_GET[\'campana_crm\']; }else{ echo "0"; } ?><?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\'];}?><?php if(isset($_GET[\'consinte\'])) { echo "&consinte=".$_GET[\'consinte\']; }?>&cerrarViaPost=true\',
                                        type  : "post",
                                        dataType : "json",
                                        data  : formData,
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        success : function(xt){
                                            borrarStorage($("#CampoIdGestionCbx").val());
                                            
                                            try{
                                                var origen="formulario";
                                                finalizarGestion(xt,origen);
                                            }catch{
                                                var data={
                                                    accion:"cerrargestion",
                                                    datos:xt
                                                };
                                                parent.postMessage(data, \'*\');
                                            }
                                        }
                                    });
                                    
                
                                <?php } 
                                    }
                                ?>            
                            }else{
                                //Algo paso, hay un error
                                $("#Save").attr(\'disabled\', false);
                                alertify.error(data.mensaje);
                            }                
                        },
                        //si ha ocurrido un error
                        error: function(){
                            after_save_error();
                            $("#Save").attr(\'disabled\', false);
                            //alertify.error(\'Un error ha ocurrido y no pudimos guardar la información\');
                        }
                    });
                }        
        }
        
        $("#Save").click(function(){
            var d = new Date();
            var h = d.getHours();
            var horas = (h < 10) ? \'0\' + h : h;
            var dia = d.getDate();
            var dias = (dia < 10) ? \'0\' + dia : dia;
            var fechaFinal = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
            $("#FechaFinal").val(fechaFinal);
            var valido = 0;
            '.$validarCampos.'
            if($(".tipificacion").val() == \'0\'){
                alertify.error("Es necesaria la tipificaciÃ³n!");
                valido = 1;
            }

            $(".saltoRequerido").each(function() {
                if ($(this).prop("disabled")==false) {
                    if (this.type == "select-one") {
                        if ($(this).val() == 0 || $(this).val() == null || $(this).val()== -1) {
                            $(this).closest(".form-group").addClass("has-error");
                            valido = 1;
                        }
                    }else{
                        if ($(this).val()=="") {
                            $(this).closest(".form-group").addClass("has-error");
                            valido = 1;
                        }
                    }
                }
            });

            $(".ReqForTip").each(function() {
                if ($(this).prop("disabled")==false) {
                    if (this.type == "select-one") {
                        if ($(this).val() == 0 || $(this).val() == null || $(this).val()== -1) {
                            $(this).closest(".form-group").addClass("has-error");
                            alertify.error("La lista debe ser diligenciada.");
                            valido = 1;
                        }
                    }else{
                        if ($(this).val()=="") {
                            $(this).closest(".form-group").addClass("has-error");
                            alertify.error("El campo debe ser diligenciado.");
                            valido = 1;
                        }
                    }
                }
            });

            if($(".reintento").val() == \'2\'){
                if($(".TxtFechaReintento").val().length < 1){
                    alertify.error("Es necesario llenar la fecha de reintento!");
                    $(".TxtFechaReintento").focus();
                    valido = 1;
                }

                if($(".TxtHoraReintento").val().length < 1){
                    alertify.error("Es necesario llenar la hora de reintento!");
                    $(".TxtHoraReintento").focus();
                    valido = 1;
                }
            }
            
            /*let booValido=false;
            let showModal=false;
            let strPatrones="";
            let strEjemplo="";
            $(\'.error-phone\').remove();
            $.each($(\'.telefono\').prev(), function(b,key){
                if(this.value !="" && this.value !=0){
                    let strTelefono=this.value;
                    $.each(arr[\'patron_regexp\'], function(i, item){
                        let regex=arr[\'patron_regexp\'][i];
                        let delComillas=/\'/g;
                        regex=regex.replace(delComillas,"");
                        let patron= new RegExp(regex);
                        if(patron.test(strTelefono)){
                            booValido=true;
                        }
                        strPatrones+=arr[\'patron\'][i]+\'  \';
                        strEjemplo+=arr[\'patron_ejemplo\'][i]+\'  \';
                    });
                    if(!booValido){
                        valido=1;
                        showModal=true;
                        $(this).closest(".form-group").append("<span class=\'error-phone\' style=\'color:red;cursor:pointer\' data-toggle=\'popover\' data-trigger=\'hover\' data-content=\'El número de teléfono digitado no es valido con estos formatos <br> "+strPatrones+" <br> Ejemplo: <br>"+strEjemplo+"\'>Este número de teléfono no es valido <i style=\'color:red;\' class=\'fa fa-question-circle\'></i></span>");
                        $(this).closest(".form-group").addClass("has-error");
                        $(\'.error-phone\').css("margin-top:7px");
                        $(this).focus();
                        $(\'[data-toggle="popover"]\').popover({
                            html : true,
                            placement: "right"
                        });
                    }
                }
                
            });

            if(showModal){
            swal({
                html : true,
                title: "Número de télefono no valido",
                text: \'El registro que está guardando, no tiene ningún teléfono con un formato válido según lo definido.\',
                type: "warning",
                confirmButtonText: "dejar los teléfonos así y guardar",
                cancelButtonText : "Modificar el/los télofonos",
                showCancelButton : true,
                closeOnConfirm : true
            },
                function(isconfirm){
                    if(isconfirm){
                        cierroGestion();
                    }else{
                        valido==1
                    }
                });                
            }*/

            if(valido == \'0\'){
                cierroGestion();
            }
        });
    });';

            $botonCerrarErronea = '';

            if(!empty($CAMPAN_TipificacionErrada_b)){
                $botonCerrarErronea .= '
        $("#errorGestion").click(function(event){
            event.preventDefault();
            $("#'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.'").val('.$CAMPAN_TipificacionErrada_b.').change();
            var meses = new Array(12);
            meses[0] = "01";
            meses[1] = "02";
            meses[2] = "03";
            meses[3] = "04";
            meses[4] = "05";
            meses[5] = "06";
            meses[6] = "07";
            meses[7] = "08";
            meses[8] = "09";
            meses[9] = "10";
            meses[10] = "11";
            meses[11] = "12";
            var d = new Date();
            var h = d.getHours();
            var horas = (h < 10) ? \'0\' + h : h;
            var dia = d.getDate();
            var dias = (dia < 10) ? \'0\' + dia : dia;
            var fechaFinal = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
            $("#FechaFinal").val(fechaFinal);
            
            var form = $("#FormularioDatos");
            //Se crean un array con los datos a enviar, apartir del formulario 
            var formData = new FormData($("#FormularioDatos")[0]);
            $.ajax({
               url: \'<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?=$idUsuario?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET[\'id_gestion_cbx\'])){ echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>\',  
                type: \'POST\',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data){
                    try{
                        afterSave(data);
                    }catch(e){}

                    <?php 
                    if(!isset($_GET[\'formulario\'])){
                    ?>

                    $.ajax({
                        url   : \'formularios/generados/PHP_Ejecutar.php?action=EDIT&tiempo=<?php echo $tiempoDesdeInicio;?>&usuario=<?=$idUsuario?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?>&ConsInteRegresado=\'+data +\'<?php if(isset($_GET[\'token\'])) { echo "&token=".$_GET[\'token\']; }?><?php if(isset($_GET[\'id_gestion_cbx\'])) { echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?>&campana_crm=<?php if(isset($_GET[\'campana_crm\'])){ echo $_GET[\'campana_crm\']; } else{ echo "0"; } ?><?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\'];}?><?php if(isset($_GET[\'consinte\'])) { echo "&consinte=".$_GET[\'consinte\']; }?>\',
                        type  : "post",
                        data  : formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success : function(xt){
                            console.log(xt);
                            window.location.href = "https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/Estacion_contact_center.php?busqueda_manual_forzada=true<?php if(isset($_GET[\'campana_crm\'])){ echo "&id_campana_crm=".$_GET[\'campana_crm\']; }?>";
                        }
                    });
                    
            
                    <?php } ?>    
                    
                              
                },
                //si ha ocurrido un error
                error: function(){
                    after_save_error();
                    alertify.error(\'Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde\');
                }
            });
        });';
            }

            if ($datoArray['GUION__Tipo______b'] == 1) {
                $modalCorreoCalidad = '
    <div class="modal fade-in" id="enviarCalificacion" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog" style="width: 50%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" id="CerrarCalificacion">&times;</button>
                    <h4 class="modal-title">Enviar Calificacion</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <p >Para enviar la calificacion a otros correos, ingresarlos <strong>SEPARANDOLOS</strong>  por una coma ( , ).</p>
                            <input type="text" class="form-control" id="cajaCorreos" name="cajaCorreos" placeholder="Ejemplo1@ejem.com, Ejemplo2@ejem.com">
    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <img hidden id="loading" src="/crm_php/assets/plugins/loading.gif" width="30" height="30">&nbsp;&nbsp;&nbsp;
                            <button id="sendEmails" readonly class="btn btn-primary"><i class="fa fa-paper-plane-o"></i>  Enviar Calificacion </button>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="ModalLoading">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong id="TitleModalLoading">Envio De Reporte</strong></h5>
                </div>
                <div class="modal-body">
                    <!-- loading -->
                    <div id="Loading" class="container-loader">
                        <div class="loader">
                            <img src="<?=base_url?>assets/img/loader.gif" style="margin-top: -20%; margin-left: 5%; color: #11D2FD;">
                            <p class="form-label text-black" style="margin-top: -20%; margin-left: 32%;"><strong id="LabelLoading"> ENVIANDO ... </strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                ';

            }


            fputs($fp , '
<?php date_default_timezone_set(\'America/Bogota\'); ?>
<style>
    .datepicker-days .disabled{
        color: gray !important;
        cursor: not-allowed !important;
        opacity : .4 !important;
    }
</style>
'.$modalCorreoCalidad.'
    <input type="hidden" id="IdGestion">
    <div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog" style="width:95%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                    <h4 class="modal-title">Edicion</h4>
                </div>
                <div class="modal-body">
                    <iframe id="frameContenedor" src="" style="width: 100%; height: 900px;"  marginheight="0" marginwidth="0" noresize  frameborder="0">
                    
                    </iframe>
                </div>
            </div>
        </div>
    </div>');
            //echo "Son estos los campos a bucar => ".$camposTabla;

            $PEOBUS = '
    $PEOBUS_Escritur__b = 1 ;
    $PEOBUS_Adiciona__b = 1 ;
    $PEOBUS_Borrar____b = 1 ;

    if(!isset($_GET[\'view\'])){
        $userid= isset($userid) ? $userid : $_SESSION["IDENTIFICACION"];
        $idUsuario = isset($_GET["usuario"]) ? $_GET["usuario"] : $userid;
        $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = ".$_GET[\'formulario\'];
        $query = $mysqli->query($peobus);
        $PEOBUS_VeRegPro__b = 0 ;
        
        while ($key =  $query->fetch_object()) {
            $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            $PEOBUS_Escritur__b = $key->PEOBUS_Escritur__b ;
            $PEOBUS_Adiciona__b = $key->PEOBUS_Adiciona__b ;
            $PEOBUS_Borrar____b = $key->PEOBUS_Borrar____b ;
        }

        if($PEOBUS_VeRegPro__b != 0){
            $Zsql = "SELECT '.$guion.'_ConsInte__b as id, '.$camposTabla.' FROM ".$BaseDatos.".'.$guion.' '.$joinsTabla.' WHERE '.$guion.'_Usuario = ".$idUsuario." ORDER BY '.$guion.'_ConsInte__b DESC LIMIT 0, 50";
        }else{
            $Zsql = "SELECT '.$guion.'_ConsInte__b as id, '.$camposTabla.' FROM ".$BaseDatos.".'.$guion.' '.$joinsTabla.' ORDER BY '.$guion.'_ConsInte__b DESC LIMIT 0, 50";
        }

        $muestra = 0;
        $tipoDistribucion = 0;
        $tareaBackoffice = 0;

        if(isset($_GET[\'tareabackoffice\'])){
            $tareaBackoffice = 1;

            $tareaBsql = "SELECT TAREAS_BACKOFFICE_ConsInte__b as id, TAREAS_BACKOFFICE_ConsInte__ESTPAS_b as estpas, TAREAS_BACKOFFICE_TipoDistribucionTrabajo_b as tipoDist FROM ".$BaseDatos_systema.".TAREAS_BACKOFFICE WHERE TAREAS_BACKOFFICE_ConsInte__b = ".$_GET[\'tareabackoffice\'];
            $tareaBQuery = $mysqli->query($tareaBsql);

            while ($key =  $tareaBQuery->fetch_object()) {
                $resultTareaB = $key;
            }

            $estpassql = "SELECT ESTPAS_ConsInte__MUESTR_b as muestr FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$resultTareaB->estpas;
            $estpasQuery = $mysqli->query($estpassql);

            while ($key =  $estpasQuery->fetch_object()) {
                $resultEstpas = $key;
            }

            $muestra = $resultEstpas->muestr;
            $tipoDistribucion = $resultTareaB->tipoDist;

            if($resultTareaB->tipoDist == 1){
                $Zsql = "SELECT '.$guion.'_ConsInte__b as id, '.$camposTabla.' FROM ".$BaseDatos.".'.$guion.' JOIN ".$BaseDatos.".'.$guion.'_M".$resultEstpas->muestr." ON '.$guion.'_ConsInte__b = '.$guion.'_M".$resultEstpas->muestr."_CoInMiPo__b 
                WHERE ( ('.$guion.'_M".$resultEstpas->muestr."_Estado____b = 0 OR '.$guion.'_M".$resultEstpas->muestr."_Estado____b = 1 OR '.$guion.'_M".$resultEstpas->muestr."_Estado____b = 3) OR ('.$guion.'_M".$resultEstpas->muestr."_Estado____b = 2 AND '.$guion.'_M".$resultEstpas->muestr."_FecHorAge_b <= NOW() ) ) 
                ORDER BY '.$guion.'_ConsInte__b DESC LIMIT 0, 50";
            }else{
                $Zsql = "SELECT '.$guion.'_ConsInte__b as id, '.$camposTabla.' FROM ".$BaseDatos.".'.$guion.' JOIN ".$BaseDatos.".'.$guion.'_M".$resultEstpas->muestr." ON '.$guion.'_ConsInte__b = '.$guion.'_M".$resultEstpas->muestr."_CoInMiPo__b 
                WHERE ( ('.$guion.'_M".$resultEstpas->muestr."_Estado____b = 0 OR '.$guion.'_M".$resultEstpas->muestr."_Estado____b = 1 OR '.$guion.'_M".$resultEstpas->muestr."_Estado____b = 3) OR ('.$guion.'_M".$resultEstpas->muestr."_Estado____b = 2 AND '.$guion.'_M".$resultEstpas->muestr."_FecHorAge_b <= NOW() ) )
                AND '.$guion.'_M".$resultEstpas->muestr."_ConIntUsu_b = ".$idUsuario." 
                ORDER BY '.$guion.'_ConsInte__b DESC LIMIT 0, 50";
            }
            
        }

    }else{
        $userid= isset($userid) ? $userid : "-10";
        $idUsuario = isset($_GET["usuario"]) ? $_GET["usuario"] : $userid;
        $Zsql = "SELECT '.$guion.'_ConsInte__b as id, '.$camposTabla.' FROM ".$BaseDatos.".'.$guion.' '.$joinsTabla.' ORDER BY '.$guion.'_ConsInte__b DESC LIMIT 0, 50";
    }
';    
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea
            fputs($fp , '<?php');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea    
            fputs($fp , '   //SECCION : Definicion urls');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea   
            fputs($fp , '   $url_crud = "formularios/'.$guion.'/'.$guion.'_CRUD.php";');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea  
            fputs($fp , '   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÃ“N');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea  
            fputs($fp , $PEOBUS );
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea  
            fputs($fp , '   $result = $mysqli->query($Zsql);');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea  

            fputs($fp , chr(13).chr(10)); // Genera saldo de linea   
            fputs($fp , '?>');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
            
            //JDBD ponemos la cabecera, si es formulario SC entonces ponemos la condicion para la cabeceraCalidad del modulo CALIDAD.
            if ($datoArray['GUION__Tipo______b'] == 1) {
                fputs($fp , '
<?php 

    include(__DIR__ ."/../cabecera.php");

?>');
            }else{
                fputs($fp , '<?php include(__DIR__ ."/../cabecera.php");?>');
            }
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

            $camposconsultaGuion = '';
            $camposAmostrar = '';
            $valordelArray = 0;
            $nombresDeCampos = '';
            $select2_hojadeDatos = '';
            $JavascriptTipificaciones = '';

            if($GUION__ConsInte__PREGUN_Tip_b != NULL && !is_null($GUION__ConsInte__PREGUN_Tip_b)){
                $Lxsql = "SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$GUION__ConsInte__PREGUN_Tip_b;
                $capo  = $mysqli->query($Lxsql);
                $valorLista = NULL;
                while ($kay = $capo->fetch_object()) {
                    $valorLista = $kay->PREGUN_ConsInte__OPCION_B;
                } 

                $campo  = '
<?php
if(isset($_GET[\'user\'])){
    $idUsuario = isset($_GET["usuario"]) ? $_GET["usuario"] : $userid;

    $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_Nombre____b  FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
    $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
    $datoCampan = $res_Lsql_Campan->fetch_array();
    $str_Pobla_Campan = "G".$datoCampan[\'CAMPAN_ConsInte__GUION__Pob_b\'];
    $int_Pobla_Camp_2 = $datoCampan[\'CAMPAN_ConsInte__GUION__Pob_b\'];
    $int_Muest_Campan = $datoCampan[\'CAMPAN_ConsInte__MUESTR_b\'];
    $int_Guion_Campan = $datoCampan[\'CAMPAN_ConsInte__GUION__Gui_b\'];
    $str_Nombr_Campan = $datoCampan[\'CAMPAN_Nombre____b\'];


    $getPrincipales = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_  WHERE GUION__ConsInte__b = ".$int_Pobla_Camp_2;
    $resLsql = $mysqli->query($getPrincipales);
    //echo $getPrincipales;
    $dato = $resLsql->fetch_array();

    $XLsql = $mysqli->query("SELECT ".$str_Pobla_Campan."_C".$dato[\'GUION__ConsInte__PREGUN_Pri_b\']." as nombre FROM ".$BaseDatos.".".$str_Pobla_Campan." WHERE ".$str_Pobla_Campan."_ConsInte__b = ".$_GET[\'user\'].";");

    if($XLsql && $XLsql->num_rows>0){
        //JDBD - Validamos si se pudo obtener el dato principal.
        $nombre = $XLsql;

        $nombreUsuario = NULL;
        //echo $XLsql;
        while ($key = $nombre->fetch_object()) {
            echo "<h3 style=\'color: rgb(110, 197, 255);\'>".$key->nombre."</h3>";  
            $nombreUsuario = $key->nombre;

            //MOSTRAR EL ESTADO ACTUAL DEL REGISTRO EN LA MUESTRA
            $sqlEstado=$mysqli->query("SELECT dyalogo_general.fn_tipo_reintento_traduccion(G{$int_Pobla_Camp_2}_M{$int_Muest_Campan}_Estado____b) AS intento FROM {$BaseDatos}.G{$int_Pobla_Camp_2}_M{$int_Muest_Campan} WHERE G{$int_Pobla_Camp_2}_M{$int_Muest_Campan}_CoInMiPo__b={$_GET[\'user\']}");
            if($sqlEstado && $sqlEstado->num_rows ==1){
                $sqlEstado=$sqlEstado->fetch_object();
                echo "<span class=\'text-muted\'>Tipo de reintento actual del registro: {$sqlEstado->intento}</span>";
            }
            break;
        }
            


        if(isset($_GET[\'token\']) && isset($_GET[\'id_gestion_cbx\'])){


                        
            $data = array(  "strToken_t" => $_GET[\'token\'], 
                            "strIdGestion_t" => $_GET[\'id_gestion_cbx\'],
                            "strDatoPrincipal_t" => $nombreUsuario,
                            "strNombreCampanaCRM_t" => $str_Nombr_Campan);                                                                    
            $data_string = json_encode($data);    

            $ch = curl_init($IP_CONFIGURADA.\'gestion/asignarDatoPrincipal\');
            //especificamos el POST (tambien podemos hacer peticiones enviando datos por GET
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
            //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                \'Content-Type: application/json\',                                                                                
                \'Content-Length: \' . strlen($data_string))                                                                      
            ); 
            //recogemos la respuesta
            $respuesta = curl_exec ($ch);
            //o el error, por si falla
            $error = curl_error($ch);
            //y finalmente cerramos curl
            //echo "Respuesta =>  ". $respuesta;
            //echo "<br/>Error => ".$error;
            //include "Log.class.php";
            //$log = new Log("log", "./Log/");
            //$log->insert($error, $respuesta, false, true, false);
            //echo "nada";
            curl_close ($ch);
        }
    }else{
        echo "<script>console.log(\'NO SE PUDO OBTENER EL DATO PRINCIPAL DEL REGISTRO.\');</script>";
    }
}else{
    echo "<h3 id=\'h3mio\' style=\'color : rgb(110, 197, 255);\'></h3>";    
}
?>
<input type="hidden" id="CampoIdGestionCbx" value="<?php if(isset($_GET[\'id_gestion_cbx\'])){ echo $_GET["id_gestion_cbx"];}else{echo "";}?>">
<input type="hidden" name="intConsInteBd" id="intConsInteBd" value="<?php if(isset($_GET["user"])) { echo $_GET["user"]; }else{ echo "-1";  } ?>">
<?php if(isset($_GET[\'user\'])){ ?>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="box">
            <div class="box-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th colspan="4">
                                Historico de gestiones
                            </th>
                        </tr>
                        <tr>
                            <th>Gesti&oacute;n</th>
                            <th>Comentarios</th>
                            <th>Fecha - hora</th>
                            <th>Agente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $Lsql = "SELECT * FROM ".$BaseDatos_systema.".CONDIA JOIN ".$BaseDatos_systema.".USUARI ON CONDIA_ConsInte__USUARI_b = USUARI_ConsInte__b JOIN ".$BaseDatos_systema.".MONOEF ON CONDIA_ConsInte__MONOEF_b = MONOEF_ConsInte__b WHERE CONDIA_ConsInte__CAMPAN_b = ".$_GET["campana_crm"]." AND CONDIA_ConsInte__GUION__Gui_b = ".$int_Guion_Campan." AND CONDIA_ConsInte__GUION__Pob_b = ".$int_Pobla_Camp_2." AND CONDIA_ConsInte__MUESTR_b = ".$int_Muest_Campan." AND CONDIA_CodiMiem__b = ".$_GET[\'user\']." ORDER BY CONDIA_Fecha_____b DESC LIMIT 5;";

                            
                            $res = $mysqli->query($Lsql);
                            if($res && $res->num_rows > 0){
                                while($key = $res->fetch_object()){
                                    echo "<tr>";
                                    echo "<td>".($key->MONOEF_Texto_____b)."</td>";
                                    echo "<td>".$key->CONDIA_Observacio_b."</td>";
                                    echo "<td>".$key->CONDIA_Fecha_____b."</td>";
                                    echo "<td>".$key->USUARI_Nombre____b."</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>';        
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
            }   


        //aqui es donde va la jugada de los campos 
        $SeccionSsql = "SELECT SECCIO_ConsInte__b, SECCIO_TipoSecc__b, SECCIO_Nombre____b, SECCIO_PestMini__b, SECCIO_NumColumnas_b, SECCIO_VistPest__b FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__GUION__b =  ".$id_a_generar." ORDER BY SECCIO_Orden_____b ASC ";

        //variables para la comunicacion de formularios
        $LlamarComunicacion='';
        $funcionComunicacion='';
        $CamposComunicacion='';  
            
        $Secciones = $mysqli->query($SeccionSsql);
        $SeccionesPestana = $mysqli->query($SeccionSsql);
        $vistaPestana=false;
        $Columnas = 1;
        $liPestana='';
        //toca guardar las funciones js de los subforms en un array    
        $Kaka = 0;
        $campoPadre='';
        $campoHijo='';
        $operForm='';
        $arrfuncionCargarFinal = array();
        $arrfunctionDescargarFinal = array();
        $arrfunctionRecargarFinal = array();
        $arrfuncionDeguardadoDeLagrillaFinal = array();
        $arrfuncionDeCargadoDelaGrillaFinal = array();
        $arrfuncionCargarComboCuandoSeaMaestroFinal = array();
        $arrcamposXmlParallenarFinal = array();
        $arrcamposSubgrillaFinal = array();
        $arrtabsFinalOperacions = array();
        $arrsubgrilla= array();
        $arrdarlePadreAlHijo= array();
        $arrdarlePadreAlHijo_2= array();
        $arrdarlePadreAlHijo_3= array();
        $arrlimpiadordeGrillas= array();
        $arrfunctionLlamarAloshijosLuegoDeCargar=array();
            
        $subgrilla = '
            ,subGrid: true,
            subGridRowExpanded: function(subgrid_id, row_id) { 
                // we pass two parameters 
                // subgrid_id is a id of the div tag created whitin a table data 
                // the id of this elemenet is a combination of the "sg_" + id of the row 
                // the row_id is the id of the row 
                // If we wan to pass additinal parameters to the url we can use 
                // a method getRowData(row_id) - which returns associative array in type name-value 
                // here we can easy construct the flowing 
                $("#"+subgrid_id).html(\'\');';            
            
        while ($seccionAqui = $Secciones->fetch_object()) {
            $btnFinCal = '';
            if($seccionAqui->SECCIO_TipoSecc__b == 2){

                $idCalidad = "s_".$seccionAqui->SECCIO_ConsInte__b;
                $IdHuesped= $_SESSION['HUESPED'];
                $Cargo= $_SESSION["CARGO"];
                $btnDownCall ='
                <div class="row">
                    <div class="col-md-12 col-xs-12">       
                        <!--Audio Con Controles -->
                        <audio id="Abtn'.$idCalidad.'" controls="controls" style="width: 100%">
                            <source id="btn'.$idCalidad.'" src="" type="audio/mp3"/>
                        </audio>
                    </div>
                    <input type="hidden" name="IdProyecto" id="IdProyecto" value="'. $IdHuesped .'">
                </div>';

                $btnFinCal = '<button class="btn btn-success pull-right" id="BtnFinalizarCalificacion" name="BtnFinalizarCalificacion">Finalizar Calificación</button>';

                $AjaxEnviarCalificacion = '
                //JDBD abrimos el modal de correos.
                $("#BtnFinalizarCalificacion").click(function(){
                    var Cargo= "'. $Cargo .'";
                    console.log("Cargo: ", Cargo);
                    $("#calidad").val("1");
                    $("#enviarCalificacion").modal("show");
                });

                $("#sendEmails").click(function(){
                    $("#loading").attr("hidden", false);
                    var emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                    var cajaCorreos = $("#cajaCorreos").val();
                    var IdProyecto = $("#IdProyecto").val();
                    var IdGestion= $("#IdGestion").val();
                    var IdCampana= <?=$_GET["campanaId"];?>;
                    var IdGuion= <?=$_GET["formulario"];?>;
                    var BaseUrl= "<?=base_url?>";

                    //Captura Servidor
                    var Contador= 0;
                    for (let i = 0; i < BaseUrl.length; i++) {
                        const Letra = BaseUrl[i];
                        if(Letra == "/") {
                            Contador= Contador+1;
                            if(Contador == 3) {
                                var Servidor = BaseUrl.slice(0, i);
                                console.log("Servidor:", Servidor);
                            }
                        }
                    }
                    if((Servidor == "") || (Servidor == undefined)) {
                        Servidor= window.top.location.origin;
                        console.log("Servidor:", Servidor);
                    }

                    //Captura IdGestion
                    if((IdGestion == "") || (IdGestion == undefined)) {
                        var IdGestion= <?=$_GET["registroId"];?>;
                        console.log("IdGestion:", IdGestion);
                    }

                    //Captura Correos
                    if (cajaCorreos == null || cajaCorreos == "") {
                        swal({
                            icon: "error",
                            title: "🤨 Oops...",
                            text: "Debe Agregar Mínimo Un Correo Electrónico",
                            confirmButtonColor: "#2892DB"
                        })
                        cajaCorreos = "";
                        $("#loading").attr("hidden", true);
                    }else{
                        $("#Save").click();
                        $("#ModalLoading").modal();
                        cajaCorreos = cajaCorreos.replace(/ /g, "");
                        cajaCorreos = cajaCorreos.replace(/,,,,,/g, ",");
                        cajaCorreos = cajaCorreos.replace(/,,,,/g, ",");
                        cajaCorreos = cajaCorreos.replace(/,,,/g, ",");
                        cajaCorreos = cajaCorreos.replace(/,,/g, ",");

                        if (cajaCorreos[0] == ",") {
                            cajaCorreos = cajaCorreos.substring(1);
                        }

                        if (cajaCorreos[cajaCorreos.length-1] == ",") {
                            cajaCorreos = cajaCorreos.substring(0,cajaCorreos.length-1);
                        }

                        var porciones = cajaCorreos.split(",");

                        for (var i = 0; i < porciones.length; i++) {
                            if (!emailRegex.test(porciones[i])) {
                                porciones.splice(i, 1);
                            }
                        }

                        cajaCorreos = porciones.join(",");

                        if((IdProyecto == "") || (IdProyecto == null) || (IdProyecto == undefined)){
                            var Data = new FormData();
                            Data.append("IdGuion", IdGuion);
                            $.ajax({
                                url: "<?=$url_crud;?>?ConsultarHuesped=si",  
                                type: "POST",
                                data: Data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function(data){
                                    var IdProyecto = parseInt(data, 10)
                                    console.log("IdProyecto: ", IdProyecto);
                                },
                                error : function(){
                                    alertify.error("No Se Puede Consultar Huesped");
                                },
                                complete : function(){
                                    console.log("Huesped Ok");
                                    console.log("IdProyecto: ", IdProyecto);
                                }
                            });
                        }
    
                        var formData = new FormData($("#FormularioDatos")[0]);
                        formData.append("Servidor", Servidor);
                        formData.append("IdProyecto", IdProyecto);
                        formData.append("IdGestion", IdGestion);
                        formData.append("IdCampana", IdCampana);
                        formData.append("IdGuion", IdGuion);
                        formData.append("IdCal", <?=$_SESSION["IDENTIFICACION"];?>);
                        formData.append("Correos", cajaCorreos);
    
                        $.ajax({
                            url: "<?=$url_crud;?>?EnviarCalificacion=si",  
                            type: "POST",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data){
                                alertify.success("¡Calificación Enviada!");
                                setTimeout(function(){
                                    $("#ModalLoading").modal("hide");
                                    window.location.reload();
                                }, 2000);
                            },
                            error : function(){
                                alertify.error("No Se Puede Enviar La Calificación");
                                $("#ModalLoading").modal("hide");
                            },
                            complete : function(){
                                $("#loading").attr("hidden",true);
                                $("#CerrarCalificacion").click();
                            }
    
                        });

                    }
                    
                });
                ';
                    $AjaxEnviarFin = '
    if (isset($_GET["EnviarCalificacion"])) {
        $IdGuion = $_POST["IdGuion"];
        $Servidor = $_POST["Servidor"];
        $IdGestion = $_POST["IdGestion"];
        $IdCampana = $_POST["IdCampana"];

        $P = "SELECT GUION__ConsInte__PREGUN_Pri_b AS P, GUION__ConsInte__PREGUN_Sec_b AS S FROM ".$BaseDatos_systema.". GUION_ WHERE GUION__ConsInte__b = '.$id_a_generar.';";
        $P = $mysqli->query($P);
        $P = $P->fetch_array();

        $gestion = "SELECT * FROM ".$BaseDatos.".G'.$id_a_generar.' WHERE G'.$id_a_generar.'_ConsInte__b = ".$_POST["IdGestion"];
        $gestion = $mysqli->query($gestion);
        $gestion = $gestion->fetch_array();


        if (is_null($gestion["G'.$id_a_generar.'_C'.$cCc.'"]) || $gestion["G'.$id_a_generar.'_C'.$cCc.'"] == "") {
            $valCal = "NULL";
        }else{
            $valCal = $gestion["G'.$id_a_generar.'_C'.$cCc.'"];
        }

        if (is_null($gestion["G'.$id_a_generar.'_C'.$cCac.'"]) || $gestion["G'.$id_a_generar.'_C'.$cCac.'"] == "") {
            $valCom = "NULL";
        }else{
            $valCom = $gestion["G'.$id_a_generar.'_C'.$cCac.'"];
        }

        $histCalidad = "INSERT INTO ".$BaseDatos_systema.".CALHIS (CALHIS_ConsInte__GUION__b,CALHIS_IdGestion_b,CALHIS_FechaGestion_b,CALHIS_ConsInte__USUARI_Age_b,CALHIS_DatoPrincipalScript_b,CALHIS_DatoSecundarioScript_b,CALHIS_FechaEvaluacion_b,CALHIS_ConsInte__USUARI_Cal_b,CALHIS_Calificacion_b,CALHIS_ComentCalidad_b) VALUES(".$_POST["IdGuion"].",".$_POST["IdGestion"].",\'".$gestion["G'.$id_a_generar.'_FechaInsercion"]."\',".$gestion["G'.$id_a_generar.'_Usuario"].",\'".$gestion["G'.$id_a_generar.'_C".$P["P"]]."\',\'".$gestion["G'.$id_a_generar.'_C".$P["S"]]."\',\'".date(\'Y-m-d H:i:s\')."\',".$_POST["IdCal"].",".$valCal.",\'".$valCom."\')";
        if ($mysqli->query($histCalidad)) {
            $IdCalificacion = $mysqli->insert_id;
            $IdProyecto= $_POST["IdProyecto"];
            $URL = $Servidor."/QA/index.php?SC=".$IdGuion."&G=".$IdGestion."&H=".$IdCalificacion;
            //$URL = "interno.dyalogo.cloud/QA/index.php?SC=".$SC."&G=".$G."&H=".$H;
        }else{
            $URL="";
            $IdProyecto= $_POST["IdProyecto"];
        }
        
        //GuardarURL
        $URL= strval("$URL");
        $GuardarURL= "UPDATE DYALOGOCRM_SISTEMA.CALHIS SET CALHIS_LinkCalificacion= \'". $URL ."\', CALHIS_ConsInte__PROYEC_b= \'". $IdProyecto ."\', CALHIS_IdCampana= \'". $IdCampana ."\' WHERE CALHIS_ConsInte__GUION__b= \'".$IdGuion."\' AND CALHIS_ConsInte__b= \'".$IdCalificacion."\';";
        if ($ResultadoSQL = $mysqli->query($GuardarURL)) {
            $php_response= array("msg" => "URL Guardada");
            //print_r($php_response);
        }

        $upGCE = "UPDATE ".$BaseDatos.".G'.$id_a_generar.' SET G'.$id_a_generar.'_C'.$cEc.' = -201 WHERE G'.$id_a_generar.'_ConsInte__b = ".$_POST["IdGestion"];
        $upGCE = $mysqli->query($upGCE);

        $HTML = "<!DOCTYPE html><html><head><title>HTML</title></head><body><div><h3>Añadir un comentario : </h3><a href = \'".$URL."\'>".$URL."</a></div><div>";

        //JDBD - obtenemos las secciones del formulario.
        $Secciones = "SELECT SECCIO_ConsInte__b AS id, SECCIO_TipoSecc__b AS tipo, SECCIO_Nombre____b AS nom 
                      FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__GUION__b = '.$id_a_generar.' 
                      AND SECCIO_TipoSecc__b <> 4 ORDER BY FIELD(SECCIO_TipoSecc__b,2) DESC, SECCIO_ConsInte__b DESC;";

        $email = "SELECT USUARI_Correo___b AS email FROM ".$BaseDatos_systema.".USUARI WHERE USUARI_ConsInte__b = ".$gestion["G'.$id_a_generar.'_Usuario"];
        $email = $mysqli->query($email);
        $email = $email->fetch_array();

        $Secciones = $mysqli->query($Secciones);

        $itCal = 0;
        $itNor = 0;

        while ($s = $Secciones->fetch_object()) {
            if ($s->tipo == 2) {
                if ($itCal == 0) {
                    $HTML .= "<div><h1 style=\'color: #2D0080\'>CALIFICACION DE LA LLAMADA</h1><div>";
                }

                $HTML .= "<em style=\'color: #11CFFF\'><h3>".$s->nom."</h3></em>";

                $columnas = "SELECT PREGUN_ConsInte__GUION__b AS G, PREGUN_ConsInte__b AS C, PREGUN_Texto_____b AS nom, PREGUN_Tipo______b AS tipo
                             FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$s->id." ORDER BY PREGUN_ConsInte__b;";

                $columnas = $mysqli->query($columnas);

                while ($c = $columnas->fetch_object()) {
                    if (isset($gestion["G".$c->G."_C".$c->C])) {
                        $HTML .= "<p><strong>".$c->nom." : </strong>".traductor($gestion["G".$c->G."_C".$c->C],$c->tipo)."</p>"; 
                    }
                }

                if ($itCal == 0) {
                    $HTML .= "</div></div>";
                }
                $itCal ++;
            }else{
                if ($itNor == 0) {
                    $HTML .= "<h1 style=\'color: #2D0080\'>INFORMACION DE LA GESTION DE LLAMADA</h1>";
                }

                $HTML .= "<div><em><h3 style=\'color: #11CFFF\'>".$s->nom."</h3></em>";

                $columnas = "SELECT PREGUN_ConsInte__GUION__b AS G, PREGUN_ConsInte__b AS C, PREGUN_Texto_____b AS nom, PREGUN_Tipo______b AS tipo
                             FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$s->id." ORDER BY PREGUN_ConsInte__b;";

                $columnas = $mysqli->query($columnas);

                while ($c = $columnas->fetch_object()) {
                    if (isset($gestion["G".$c->G."_C".$c->C])) {
                         $HTML .= "<p><strong>".$c->nom." : </strong>".traductor($gestion["G".$c->G."_C".$c->C],$c->tipo)."</p>";  
                    }
                    
                }

                $HTML .= "</div>";

                $itNor ++;
            }
        }

        $HTML .= "</div></body></html>";
        
                $data = array(  
                    "strUsuario_t"              =>  "crm",
                    "strToken_t"                =>  "D43dasd321",
                    "strIdCfg_t"                =>  "18",
                    "strTo_t"                   =>  \'"\'.$email["email"].\'"\',
                    "strCC_t"                   =>  $_POST["Correos"],
                    "strCCO_t"                  =>  null,
                    "strSubject_t"              =>  "Calificacion Llamada #". $gestion["G'.$id_a_generar.'_ConsInte__b"],
                    "strMessage_t"              =>  $HTML,
                    "strListaAdjuntos_t"        =>  null
                ); 

                $data_string = json_encode($data); 

                $ch = curl_init("localhost:8080/dyalogocore/api/ce/correo/sendmailservice");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
                        "Accept: application/json",                                                               
                        "Content-Type: application/json",                                                  
                        "Content-Length: ".strlen($data_string)
                    )                                                                      
                ); 
                $respuesta = curl_exec ($ch);
                $error = curl_error($ch);
                if (isset($respuesta)) {
                    echo json_encode($respuesta);
                }else{
                    echo json_encode($error);
                }
                curl_close ($ch);


        
    }';

            }
            
            
            
            
            $id_seccion = $seccionAqui->SECCIO_ConsInte__b;
            if(!empty($seccionAqui->SECCIO_NumColumnas_b)){
                $Columnas = $seccionAqui->SECCIO_NumColumnas_b ;
            }
            $stylo = '';
            if($seccionAqui->SECCIO_TipoSecc__b == 4){
                $stylo = 'style=\'display:none;\'';
            }
            
            $hidden = '';
            $ocultarPorPHP='<?php if(!isset($_GET["intrusionTR"])) : ?>';
            $ocultarPorPHPEnd='<?php endif; ?>';
            $idCalidad = $seccionAqui->SECCIO_ConsInte__b;
            if ($seccionAqui->SECCIO_TipoSecc__b == 2) {
                $idCalidad = "s_".$seccionAqui->SECCIO_ConsInte__b;
                $ocultarPorPHP='<?php if(isset($_GET["quality"]) && $_GET["quality"]=="1") : ?>';
                $ocultarPorPHPEnd='<?php endif; ?>';
            }
            //En un principio se abren las secciones
            if($seccionAqui->SECCIO_VistPest__b == 1){

                $str_Campo = '
<div id="'.$idCalidad.'" '.$stylo.$hidden.'>
<h3 class="box box-title">'.$btnFinCal.'</h3>';
                fputs($fp , $str_Campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

            }else  if($seccionAqui->SECCIO_VistPest__b == 2){


                 $str_Campo = '
<div id="'.$idCalidad.'" '.$stylo.$hidden.'>
    <h3 class="box box-title">'.($seccionAqui->SECCIO_Nombre____b).'</h3>'.$btnFinCal;
                fputs($fp , $str_Campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea

            }else  if($seccionAqui->SECCIO_VistPest__b == 3){
                $colapse = '';
                if($seccionAqui->SECCIO_PestMini__b != 0){
                    $colapse = 'in';
                }

                $str_Campo ='
'.$ocultarPorPHP.'
<div class="panel box box-primary" id="'.$idCalidad.'" '.$stylo.$hidden.'>
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_'.$seccionAqui->SECCIO_ConsInte__b.'c">
                '.($seccionAqui->SECCIO_Nombre____b).'
            </a>
        </h4>
        '.$btnFinCal.'
    </div>
    <div id="s_'.$seccionAqui->SECCIO_ConsInte__b.'c" class="panel-collapse collapse '.$colapse.'">
        <div class="box-body">';
            fputs($fp , $str_Campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea

            }elseif($seccionAqui->SECCIO_VistPest__b == 5){
                if($liPestana == ''){
                    $i=0;
                    while($seccionPestana = $SeccionesPestana->fetch_object()){
                        if($seccionPestana->SECCIO_VistPest__b == 5){
                            if($i==0){
                                $active='active';
                            }else{
                                $active='';
                            }
                            $liPestana.="<li class='nav-item {$active}'><a id='link_{$seccionPestana->SECCIO_ConsInte__b}' class='nav-link' href='#tabS_{$seccionPestana->SECCIO_ConsInte__b}' data-toggle='pill'>{$seccionPestana->SECCIO_Nombre____b}</a></li>";
                            $i++;
                        }
                    }
                    $str_Campo =
$ocultarPorPHP.'
<div class="panel box box-primary">
    <div style="margin-top: 5px;">
        <ul class="nav nav-tabs" role="tablist">
            '.$liPestana.'
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tabS_'.$seccionAqui->SECCIO_ConsInte__b.'" style="margin-top: 20px;padding: 0px 15px;">';

                }else{
                    $str_Campo ='<div class="tab-pane" id="tabS_'.$seccionAqui->SECCIO_ConsInte__b.'" style="margin-top: 20px;padding: 0px 15px;">';
                }
            fputs($fp , $str_Campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea
            }else{

                $str_Campo ='
<div class="panel box box-primary" '.$stylo.' id="'.$idCalidad.'" '.$hidden.'>
    <div class="box-header with-border">
        <h4 class="box-title">
            '.($seccionAqui->SECCIO_Nombre____b).'
        </h4>
        '.$btnFinCal.'
    </div>
    <div class="box-body">';
        fputs($fp , $str_Campo);
        fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

            }
            if($seccionAqui->SECCIO_TipoSecc__b == 2){
                if ($idCalidad != "" && $datoArray['GUION__Tipo______b'] == 1) {
                $crearAjax .= '
                    $.ajax({// JDBD - Obtener el link de la llamada y reproducir
                    url      : \'<?=$url_crud;?>?llenarBtnLlamada=si\',
                    type     : \'POST\',
                    data     : {idReg : id},
                    success  : function(data){
                        var audio = $("#Abtn'.$idCalidad.'");
                        $("#btn'.$idCalidad.'").attr("src",data+"&streaming=true").appendTo(audio);
                        audio.load();
                    }});
                ';

    $responderAjax .= '
        if(isset($_GET["llenarBtnLlamada"])){// JDBD - Devolver link de la llamada
            $Con = "SELECT '.$guion.'_LinkContenido as url FROM ".$BaseDatos.".'.$guion.' WHERE '.$guion.'_ConsInte__b = ".$_POST["idReg"];
            $result = $mysqli->query($Con);

            $url = $result->fetch_array();

            echo $url["url"];
        }                   
';

    $MostrarCalidad .= '
                $("#'.$idCalidad.'").attr("hidden", false);';

                }
            }   


        //Aqui hacemos el dibujo de los str_Campos
        $str_LsqlXD = "SELECT REPLACE(PREGUN_Texto_____b,'\t',' ') as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_PermiteAdicion_b , PREGUN_ConsInte_PREGUN_Depende_b , PREGUN_DefaNume__b, PREGUN_DefaText__b, PREGUN_DefCanFec_b, PREGUN_DefUniFec_b, PREGUN_OperEntreCamp_____b, PREGUN_TipoTel_b, PREGUN_SendMail_b, PREGUN_SendSMS_b, PREGUN_idCuentaMail_b, PREGUN_IdProveedorSms_b, PREGUN_textSMS_b,PREGUN_PrefijoSms_b,PREGUN_SearchMail_b,PREGUN_consInte__ws_B,PREGUN_FormaIntegrarWS_b,PREGUN_Formato_b,PREGUN_PosDecimales_b,PREGUN_Longitud__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_ConsInte__SECCIO_b = ".$id_seccion." AND PREGUN_FueGener_b != 3 ORDER BY PREGUN_OrdePreg__b  ASC";

            $str_Campos = $mysqli->query($str_LsqlXD);
            $rowsss = 0;
            $booSubForm=false;
            $strOrigen='';
            $seccion = '';
            $seccionActual = '';

            $maxColumns = $Columnas;
            $filaActual = 0;
            
            //variables del subformulario
            $funcionCargarFinal = '';
            $functionDescargarFinal = '';
            $functionRecargarFinal ='';   
            $funcionDeguardadoDeLagrillaFinal ='';
            

            $funcionDeCargadoDelaGrillaFinal = '';
            $functionLlamarAloshijosLuegoDeCargar = '';
            $funcionCargarComboCuandoSeaMaestroFinal = '';
            $camposXmlParallenarFinal = '';
            $camposSubgrillaFinal ='';
            $tabsFinalOperacions = '';

            $ModalesFinal = '';
            $limpiadordeGrillas = '';
            $camposAenfocar='';

            $funcionCargarDatosDeLasPutasGrillas = '';
            $darlePadreAlHijo = '';
            $darlePadreAlHijo_2 = '';
            $darlePadreAlHijo_3 = '';            
            $checkColumnas = (12 / $Columnas);
            while($obj = $str_Campos->fetch_object()){
                if( $obj->id != $GUION__ConsInte__PREGUN_Tip_b && 
                    $obj->id != $GUION__ConsInte__PREGUN_Rep_b &&
                    $obj->id != $GUION__ConsInte__PREGUN_Fag_b && 
                    $obj->id != $GUION__ConsInte__PREGUN_Hag_b &&
                    $obj->id != $GUION__ConsInte__PREGUN_Com_b){
                  ///  $seccion = $obj->PREGUN_ConsInte__SECCIO_b; 

                //  
                if( $filaActual == 0 ){
                    $str_Campo = '
        <div class="row">
        ';
        fputs($fp , $str_Campo);
        fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

                }


$str_Campo = '
            <div class="col-md-'.$checkColumnas.' col-xs-'.$checkColumnas.'">
';
fputs($fp , $str_Campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                
                

                $valorPordefecto = $obj->PREGUN_Default___b;
                $valoraMostrar = "";
                $disableds = '';
                if($obj->PREGUN_ContAcce__b == 2){
                    $disableds = 'readonly';
                }elseif ($obj->PREGUN_ContAcce__b == 3) {
                    $disableds = 'disabled'; 
                }
                switch ($valorPordefecto) {
                    case '501':
                        $valoraMostrar = '<?php echo date(\'Y-m-d H:i:s\');?>';
                        break;
                    case '1000':
                        $valoraMostrar = $obj->PREGUN_DefaText__b;;
                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor asignado por defecto a este campo.
            $("#'.$guion_c.$obj->id.'").val("'.$valoraMostrar.'");';
                        break;
                    case '1001':
                        $valoraMostrar = '<?php echo date(\'H:i:s\');?>';
                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor asignado por defecto a este campo.
            $("#'.$guion_c.$obj->id.'").val("'.$valoraMostrar.'");';
                        break;
                    case '1002':
                        $valoraMostrar = '<?php if(!isset($_GET["token"])){echo $_SESSION["NOMBRES"];}else{echo getNombreUser($_GET["token"]);}?>';
                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor nombre de usuario.
            $("#'.$guion_c.$obj->id.'").val("'.$valoraMostrar.'");';
                        break;
                    case '1003':
                        $valoraMostrar = '<?php if(!isset($_GET["token"])){echo $_SESSION["CODIGO"];}else{echo getMailUser($_GET["token"]);}?>';
                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor mail de usuario.
            $("#'.$guion_c.$obj->id.'").val("'.$valoraMostrar.'");';
                        break; 
                    case '1004':
                        $valoraMostrar = '<?php if(isset($_GET["ani"])){echo $_GET["ani"];} ?>';
                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor telefono llamada entrante.
            $("#'.$guion_c.$obj->id.'").val("'.$valoraMostrar.'");';
                        break;        
                    case '1005':
                        $valoraMostrar = '<?php if(!isset($_GET["token"])){echo getCedulaUser($_SESSION["IDENTIFICACION"]);}else{echo getCedulaUser($_GET["token"]);}?>';
                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor telefono llamada entrante.
            $("#'.$guion_c.$obj->id.'").val("'.$valoraMostrar.'");';
                        break;        
                    case '5001':
                        $valoraMostrar = '<?=date("Y-m-d");?>';
                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor fecha actual.
            $("#'.$guion_c.$obj->id.'").val("'.$valoraMostrar.'");';
                        break;
                    case '5002':
                        $valoraMostrar = '<?=date("Y-m-d",strtotime(date("Y-m-d")."+ '.$obj->PREGUN_DefCanFec_b.' '.$obj->PREGUN_DefUniFec_b.'"));?>';
                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor fecha actual.
            $("#'.$guion_c.$obj->id.'").val("'.$valoraMostrar.'");';
                        break;
                    case '5003':
                        $valoraMostrar = '<?=date("Y-m-d",strtotime(date("Y-m-d")."- '.$obj->PREGUN_DefCanFec_b.' '.$obj->PREGUN_DefUniFec_b.'"));?>';
                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor fecha actual.
            $("#'.$guion_c.$obj->id.'").val("'.$valoraMostrar.'");';
                        break;
                    case '102':
                        $valoraMostrar = '<?php isset($userid) ? NombreAgente($userid) : getNombreUser($token);?>';
                        break;
                    
                    case '105':
                        $valoraMostrar = '<?php if(isset($_GET["campana_crm"])){ $cmapa = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
                $resCampa = $mysqli->query($cmapa);
                $dataCampa = $resCampa->fetch_array(); echo $dataCampa["CAMPAN_Nombre____b"]; } else { echo "NO TIENE CAMPAÑA";}?>';
                        break;

                    case '3001':

                        $valoraMostrar = $obj->PREGUN_DefaNume__b;

                        $strCampoConValorDefinido_t .= '
            //JDBD - Damos el valor asignado por defecto a este campo.
            $("#'.$guion_c.$obj->id.'").val('.$valoraMostrar.');';

                        break;

                    case '3002':

                        //Es el autonumerico

                        $valoraMostrar = '<?php if(!isset($_GET["registroId"]) && isset($_GET["view"])){  if ($mysqli->query("UPDATE ".$BaseDatos_systema.".CONTADORES SET CONTADORES_Valor_b = (CONTADORES_Valor_b+1) WHERE CONTADORES_ConsInte__PREGUN_b = '.$obj->id.'")){
                                $Lsql = $mysqli->query("SELECT CONTADORES_Valor_b FROM ".$BaseDatos_systema.".CONTADORES WHERE CONTADORES_ConsInte__PREGUN_b = '.$obj->id.'");
                                echo $Lsql->fetch_array()["CONTADORES_Valor_b"];
                            }
                             }?>';

                        $strAjaxIncrementable_t = 'if (isset($_POST["INCTB"])) {
                            if ($mysqli->query("UPDATE ".$BaseDatos_systema.".CONTADORES SET CONTADORES_Valor_b = (CONTADORES_Valor_b+1) WHERE CONTADORES_ConsInte__PREGUN_b = '.$obj->id.'")){
                                $Lsql = $mysqli->query("SELECT CONTADORES_Valor_b FROM ".$BaseDatos_systema.".CONTADORES WHERE CONTADORES_ConsInte__PREGUN_b = '.$obj->id.'");
                                echo $Lsql->fetch_array()["CONTADORES_Valor_b"];
                            }else{echo 0;}
                        }';  

                        $strCampoConValorDefinido_t .= '
            //JDBD - Creamos un nuevo id incrementable y lo asignamos a este campo
            $.ajax({
                url:\'<?=$url_crud;?>\',
                type:\'POST\',
                data:{INCTB:"si"},
                success:function(data){
                    $("#'.$guion_c.$obj->id.'").val(data);
                }
            });';


                        
                        break;

                    default:
                        $valoraMostrar = '<?php if (isset($_GET[\''.$guion_c.$obj->id.'\'])) {
                            echo $_GET[\''.$guion_c.$obj->id.'\'];
                        } ?>';
                        break;
                }

                $strSQLSaltosRequeridos_t = "SELECT A.PRSASA_ConsInte__PRSADE_b AS id FROM ".$BaseDatos_systema.".PRSASA A JOIN ".$BaseDatos_systema.".PRSADE B ON A.PRSASA_ConsInte__PRSADE_b = B.PRSADE_ConsInte__b JOIN ".$BaseDatos_systema.".PREGUN C ON A.PRSASA_ConsInte__PREGUN_b = C.PREGUN_ConsInte__b WHERE A.PRSASA_ConsInte__PREGUN_b = ".$obj->id." AND C.PREGUN_IndiRequ__b = -1";

                $resSQLSaltosRequeridos_t = $mysqli->query($strSQLSaltosRequeridos_t);

                if ($resSQLSaltosRequeridos_t->num_rows > 0) {
                    
                    $strClassSaltoR_t = " saltoRequerido";

                }else{
                    $strClassSaltoR_t = "";                    
                }
                    
                $campoTel='';
                $campoSms='';
                $campoMail='';
                
                if($obj->PREGUN_TipoTel_b == '-1'){
                    $campoTel='<div class="input-group-addon telefono" style="cursor:pointer" id="TLF_'.$guion_c.$obj->id.'" title="Click para llamar">
                        <i class="fa fa-phone"></i>
                    </div>';
                    $funciones_jsx .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#TLF_'.$guion_c.$obj->id.'").click(function(){
        strTel_t=$("#'.$guion_c.$obj->id.'").val();
        llamarDesdeBtnTelefono(strTel_t);
    });';                    
                }

                if($obj->PREGUN_SendSMS_b == '-1'){
                    $campoSms='<div class="input-group-addon telefono" style="cursor:pointer" cuenta="'.$obj->PREGUN_IdProveedorSms_b.'" default="'.$obj->PREGUN_textSMS_b.'" prefijo="'.$obj->PREGUN_PrefijoSms_b.'" campo="G'.$id_a_generar.'_C'.$obj->PREGUN_textSMS_b.'" id="SMS_'.$guion_c.$obj->id.'" title="enviar sms">
                        <i class="fa fa-commenting"></i>
                    </div>';
                    $funciones_jsx .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#SMS_'.$guion_c.$obj->id.'").click(function(){
        strTel_t=$("#'.$guion_c.$obj->id.'").val();
        strCuenta_t=$(this).attr("cuenta");
        strTextDefault_t=$(this).attr("default");
        strCampoDefault_t=$(this).attr("campo");
        strPrefijo_t=$(this).attr("prefijo");
        enviarSmsDesdeBtn(strTel_t,strCuenta_t,strTextDefault_t,strCampoDefault_t,strPrefijo_t);
    });';                    
                }

                if($obj->PREGUN_SendMail_b == '-1'){
                    $campoMail='<div class="input-group-addon telefono" style="cursor:pointer" cuenta="'.$obj->PREGUN_idCuentaMail_b.'" id="MAIL_'.$guion_c.$obj->id.'" title="enviar mail">
                        <i class="fa fa-envelope"></i>
                    </div>';
                    $funciones_jsx .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#MAIL_'.$guion_c.$obj->id.'").click(function(){
        strMail_t=$("#'.$guion_c.$obj->id.'").val();
        strCuenta_t=$(this).attr("cuenta");
        enviarMailDesdeBtn(strMail_t,strCuenta_t);
    });';                    
                }


                switch ($obj->tipo_Pregunta) {

                    case '17':
                        $campo ='<div class="form-group">
                        <label style="color:white">...</label>
                        <button class="form-control input-sm btn btn-primary" id="'.$guion_c.$obj->id.'" ws="'.$obj->PREGUN_consInte__ws_B.'" llave="'.$obj->id.'" name="'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</button>';
                        $campo .='</div>';
                        $funciones_jsx .="\n".'
        //function para '.($obj->titulo_pregunta).' '. "\n".'
        $("#'.$guion_c.$obj->id.'").click(function(e){
            e.preventDefault();
            intWS_t=$(this).attr("ws");
            intLlave_t=$(this).attr("llave");
            intForm_t='.$id_a_generar.';
            llamarIntegracionWS(intWS_t,intLlave_t,intForm_t);
        });';
                        fputs($fp , $campo);
                        fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                        break;

                        
                    case '16':
                        
                        $campo ='<div class="form-group">
                        <label style="color:white">...</label>
                        <button class="form-control input-sm btn btn-primary" id="'.$guion_c.$obj->id.'" campoBuscar="'.$obj->PREGUN_SearchMail_b.'" cuenta="'.$obj->PREGUN_idCuentaMail_b.'" name="'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</button>';
                        $campo .='</div>';
                        $funciones_jsx .="\n".'
        //function para '.($obj->titulo_pregunta).' '. "\n".'
        $("#'.$guion_c.$obj->id.'").click(function(e){
            e.preventDefault();
            strCuenta_t=$(this).attr("cuenta");
            strCampo_t=$(this).attr("campoBuscar")
            buscarMailDesdeBtn(strCuenta_t,strCampo_t);
        });';
                        fputs($fp , $campo);
                        fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                        break;
                        

                    //JDBD-2020-05-10 : Campo tipo adjunto.
                    case '15':

                    $strConfirmamosQueElGuionTieneAdjuntos_t ++;

$campo = '
                    
                    <!-- JDBD-2020-05-10 : Campo tipo adjunto. -->
                    <div class="panel">
                        <label for="F'.$guion_c.$obj->id.'" id="LblF'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                        <input type="file" class="adjuntos'.$strClassSaltoR_t.'" id="F'.$guion_c.$obj->id.'" name="F'.$guion_c.$obj->id.'">
                        <p class="help-block">
                            Peso maximo del archivo 9 MB
                        </p>
                        <input id ="down'.$guion_c.$obj->id.'" name="down'.$guion_c.$obj->id.'" adjunto="" value="Sin Adjuntos" readonly class="btn btn-primary btn-sm" onClick="bajarAdjunto(this.id)" >
                        <input type="hidden" id="'.$guion_c.$obj->id.'" name="'.$guion_c.$obj->id.'">
                    </div>';

                   fputs($fp , $campo);
                   fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                        break;
                        
                        
                    case '1':
                        if($obj->titulo_pregunta=='ORIGEN_DY_WF'){
                            $strOrigen="$('#{$guion_c}{$obj->id}').val('BACKOFFICE');";
                        }
$campo = ' 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>';
                        if($obj->PREGUN_TipoTel_b == '-1' || $obj->PREGUN_SendSMS_b == '-1' || $obj->PREGUN_SendMail_b == '-1'){
                            $campo .='<div class="input-group">
                            <input type="text" maxlength="'.$obj->PREGUN_Longitud__b.'" onKeyDown="longitud(this.id,\'nel\')" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id)" class="form-control input-sm'.$strClassSaltoR_t.'" id="'.$guion_c.$obj->id.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'">
                            '.$campoTel.'
                            '.$campoMail.'
                            '.$campoSms.'
                        </div>';
                        }else{
                            $campo .='<input type="text" maxlength="'.$obj->PREGUN_Longitud__b.'" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm'.$strClassSaltoR_t.'" id="'.$guion_c.$obj->id.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'">';
                        }
                    $campo .='</div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->';
                   $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';
                        
                   fputs($fp , $campo);
                   fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;


                    case '14':
$campo = ' 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>';
                        if($obj->PREGUN_SendMail_b == '-1'){
                            $campo .='<div class="input-group">
                            <input type="email" maxlength="'.$obj->PREGUN_Longitud__b.'" onKeyDown="longitud(this.id,\'nel\')" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id)" class="form-control input-sm'.$strClassSaltoR_t.'" id="'.$guion_c.$obj->id.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'">
                            '.$campoMail.'
                        </div>';                            
                        }else{
                            $campo .='<input type="email" maxlength="'.$obj->PREGUN_Longitud__b.'" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm'.$strClassSaltoR_t.'" id="'.$guion_c.$obj->id.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'">';
                        }
                    $campo .='</div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->';                        

                   $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';
                                                
                   fputs($fp , $campo);
                   fputs($fp , chr(13).chr(10)); // Genera saldo de linea                             
                        break;

                    case '2':
$campo = '  
                    <!-- CAMPO TIPO MEMO -->
                    <div class="form-group">
                        <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                        <textarea class="form-control input-sm'.$strClassSaltoR_t.'" name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" '.$disableds.' value="'.$valoraMostrar.'" placeholder="'.($obj->titulo_pregunta).'"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO MEMO -->';
                    $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';
                    fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;

                    case '3':
                        $formato='';
                        $digitos='';
                        if($obj->PREGUN_Formato_b == '2'){
                            $formato=' moneda';
                            $digitos="digitos={$obj->PREGUN_PosDecimales_b}";
                        }
$campo = ' 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>';
                        if($obj->PREGUN_TipoTel_b == '-1' || $obj->PREGUN_SendSMS_b == '-1'){
                            $campo .='<div class="input-group">
                            <input type="number" class="form-control input-sm Numerico '.$strClassSaltoR_t.''.$formato.'" '.$digitos.' value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
                            '.$campoTel.'
                            '.$campoSms.'
                        </div>';                            
                        }else{
                            $campo .='<input type="number" class="form-control input-sm Numerico '.$strClassSaltoR_t.''.$formato.'" '.$digitos.' value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">';
                        }                        
                    $campo .='</div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->';
                        
                    fputs($fp , $campo);
                    fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

                    $LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b, PREGUN_Formato_b, PREGUN_PosDecimales_b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
                    $cur_result = $mysqli->query($LsqlCadena);
                    $itsCadena = false;
                    while ($key = $cur_result->fetch_object()) {    
                        /* ahora toca buscar el valor de esos campos en la jugada */
                        $buscar = '${'.NombreParaFormula($obj->titulo_pregunta).'}';
                        //$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);
                        if (stristr(trim($key->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
                            $itsCadena = true;
                            //echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
                            /* Toca hacer todo el frito */
                            $funciones_jsY .= "\n".'
        //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){';

                            $LsqlCadenaX = "SELECT REPLACE(REPLACE(PREGUN_Texto_____b,' ','_'),'\t','_') AS PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
                            $cadenaFinalX = trim(str_replace('=', '', $key->PREGUN_OperEntreCamp_____b));

                            $cur_resultX = $mysqli->query($LsqlCadenaX);

                            while ($keyX = $cur_resultX->fetch_object()) {

                                /* ahora toca buscar el valor de esos campos en la jugada */
                                $buscarX = '${'.NombreParaFormula($keyX->PREGUN_Texto_____b).'}';

                                $reemplazo = 'Number($("#'.$guion_c.$keyX->PREGUN_ConsInte__b.'").val())';

                                $cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
                                
                                $camposAenfocar.='$("#'.$guion_c.$keyX->PREGUN_ConsInte__b.'").focus({preventScroll:true});'."\n";
                                $camposAenfocar.='$("#'.$guion_c.$keyX->PREGUN_ConsInte__b.'").blur();'."\n";
                            }

                        
                            switch($key->PREGUN_Formato_b){
                                case 3://PORCENTAJE
                                    $funciones_jsY .= "\n".'
        
                var intCalculo_t = Number('.$cadenaFinalX.'*100);
        
                $("#'.$guion_c.$key->PREGUN_ConsInte__b.'").val(intCalculo_t.toFixed('.$key->PREGUN_PosDecimales_b.')+"%");';
        
                                    $funciones_jsY .= "\n".'
            });';
                                    break;
                                case 2://Moneda
                                    $funciones_jsY .= "\n".'
        
                var intCalculo_t = '.$cadenaFinalX.';
        
                $("#'.$guion_c.$key->PREGUN_ConsInte__b.'").val(intCalculo_t);';
        
                                    $funciones_jsY .= "\n".'
            });';
                                    break;
                                default://Numero
                                $funciones_jsY .= "\n".'
        
                                var intCalculo_t = '.$cadenaFinalX.';
                        
                                $("#'.$guion_c.$key->PREGUN_ConsInte__b.'").val(intCalculo_t.toFixed('.$key->PREGUN_PosDecimales_b.'));';
                        
                                                    $funciones_jsY .= "\n".'
                            });';
                                    break;
                            }
                        }
                    }

                    if($itsCadena == false){
                        /* No esta metido en ningun lado */
                        $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';

                    }

                    break;

                    case '4':

                    $formato='';
                    $digitos='';
                    if($obj->PREGUN_Formato_b == '2'){
                        $formato=' moneda';
                        $digitos="digitos={$obj->PREGUN_PosDecimales_b}";
                    }
$campo = '  
                    <!-- CAMPO TIPO DECIMAL -->
                    <!-- Estos campos siempre deben llevar Decimal en la clase asi class="form-control input-sm Decimal" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                        <input type="number" class="form-control input-sm Decimal '.$strClassSaltoR_t.''.$formato.'" '.$digitos.' value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
                    </div>
                    <!-- FIN DEL CAMPO TIPO DECIMAL -->';
                    
                    fputs($fp , $campo);
                     fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

                     $LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
                    $cur_result = $mysqli->query($LsqlCadena);
                    $itsCadena = false;
                    while ($key = $cur_result->fetch_object()) {    
                        /* ahora toca buscar el valor de esos campos en la jugada */

                        $buscar = '${'.NombreParaFormula($obj->titulo_pregunta).'}';
                    
                        //$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);
                        
                        
                        if (stristr(trim($key->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
                            $itsCadena = true;
                            //echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
                            /* Toca hacer todo el frito */
                            $funciones_jsY .= "\n".'
        //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){';

                            $LsqlCadenaX = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b, PREGUN_Formato_b, PREGUN_PosDecimales_b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
                            $cadenaFinalX = trim(str_replace('=', '', $key->PREGUN_OperEntreCamp_____b));

                            $cur_resultX = $mysqli->query($LsqlCadenaX);

                            while ($keyX = $cur_resultX->fetch_object()) {

                                /* ahora toca buscar el valor de esos campos en la jugada */

                                $buscarX = '${'.NombreParaFormula($keyX->PREGUN_Texto_____b).'}';

                                $reemplazo = 'Number($("#'.$guion_c.$keyX->PREGUN_ConsInte__b.'").val())';
                                
                                $cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
                                $camposAenfocar.='$("#'.$guion_c.$keyX->PREGUN_ConsInte__b.'").focus({preventScroll:true});'."\n";
                                $camposAenfocar.='$("#'.$guion_c.$keyX->PREGUN_ConsInte__b.'").blur();'."\n";
                            }

                            switch($key->PREGUN_Formato_b){
                                case 3://PORCENTAJE
                                    $funciones_jsY .= "\n".'
        
                var intCalculo_t = Number('.$cadenaFinalX.'*100);
        
                $("#'.$guion_c.$key->PREGUN_ConsInte__b.'").val(intCalculo_t.toFixed('.$key->PREGUN_PosDecimales_b.')+"%");';
        
                                    $funciones_jsY .= "\n".'
            });';
                                    break;
                                case 2://Moneda
                                    $funciones_jsY .= "\n".'

                var intCalculo_t = '.$cadenaFinalX.';

                $("#'.$guion_c.$key->PREGUN_ConsInte__b.'").val(intCalculo_t);';

                                $funciones_jsY .= "\n".'
            });';
                                    break;
                                default://Numero
                                $funciones_jsY .= "\n".'
        
                                var intCalculo_t = '.$cadenaFinalX.';
                        
                                $("#'.$guion_c.$key->PREGUN_ConsInte__b.'").val(intCalculo_t.toFixed('.$key->PREGUN_PosDecimales_b.'));';
                        
                                                    $funciones_jsY .= "\n".'
                            });';
                                    break;
                            }
                        }
                    }

                    if($itsCadena == false){
                        /* No esta metido en ningun lado */
                        $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';

                    }

                    break;

                    case '5':
$campo = '  
                    <!-- CAMPO TIPO FECHA -->
                    <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                    <div class="bootstrap-datepicker">
                        <div class="form-group">
                            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm Fecha'.$strClassSaltoR_t.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="YYYY-MM-DD" nombre="'.$obj->titulo_pregunta.'">
                                <div class="input-group-addon" id="DTP_'.$guion_c.$obj->id.'">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO TIPO FECHA-->';
                    $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';
                    fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;
            
                    case '10':
$campo = '  
                    <!-- CAMPO TIMEPICKER -->
                    <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                    <div class="bootstrap-timepicker">
                        <div class="form-group">
                            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm Hora'.$strClassSaltoR_t.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="HH:MM:SS" >
                                <div class="input-group-addon" id="TMP_'.$guion_c.$obj->id.'">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                    </div>
                    <!-- FIN DEL CAMPO TIMEPICKER -->';
                     $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';
                    fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;

                    case '6':

                    $select2 .= "\n".'
    $("#'.$guion_c.$obj->id.'").select2();';
                    
                    $valDefault='echo "<option value=\'".$obje->OPCION_ConsInte__b."\'>".($obje->OPCION_Nombre____b)."</option>";';    
                    if(!is_null($obj->PREGUN_Default___b)){
                        $valDefault='if($obje->OPCION_ConsInte__b =='.$obj->PREGUN_Default___b.'){
                            echo "<option selected=\'true\' value=\'".$obje->OPCION_ConsInte__b."\'>".($obje->OPCION_Nombre____b)."</option>";
                        }else{
                            echo "<option value=\'".$obje->OPCION_ConsInte__b."\'>".($obje->OPCION_Nombre____b)."</option>";
                        }';
                        $valor=$obj->PREGUN_Default___b;
                        $strCampoConValorDefinido_t .= '
                        $("#'.$guion_c.$obj->id.'").val("'.$valor.'").trigger("change");';                        
                    }
$campo = '
                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>';
                        if($obj->PREGUN_Formato_b == 5){
                            $campo.=generateOptionButton($obj->lista,$guion_c.$obj->id);
                        }else{
                            $campo.='<select '.$disableds.' class="form-control '.$guion_c.$obj->id.' input-sm select2'.$strClassSaltoR_t.'"  style="width: 100%;" name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = '.$obj->lista.' ORDER BY LISOPC_Nombre____b ASC";
    
                                    $obj = $mysqli->query($Lsql);
                                    while($obje = $obj->fetch_object()){
                                        '.$valDefault.'
                                    }    
                                    
                                ?>
                            </select>';
                        }
                    $campo.='</div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->';

$GuionsSql = "SELECT PRSADE_ConsInte__OPCION_b  FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = '".$obj->id."' GROUP BY PRSADE_ConsInte__OPCION_b";
$result = $mysqli->query($GuionsSql);
$saltos = '';
if($result->num_rows > 0){
    $sinsalto = '';
    $tamanho = 0;
    $countI=0;
    $strCamposS='';
    while($objr = $result->fetch_object()){
        if($countI == 0){
            $strCamposS.=$objr->PRSADE_ConsInte__OPCION_b;
        }else{
            $strCamposS.=",{$objr->PRSADE_ConsInte__OPCION_b}";
        }
        $countI++;
    }

    $newSql = "SELECT * FROM ".$BaseDatos_systema.".PRSASA JOIN ".$BaseDatos_systema.".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = ".$obj->id." AND PRSADE_ConsInte__OPCION_b IN (".$strCamposS.") GROUP BY PRSASA_NombCont__b;";
    $newResult = $mysqli->query($newSql);
    while ($newObjr = $newResult->fetch_object()) {
        $sqlTipoCampo=$mysqli->query("SELECT PREGUN_Tipo______b AS tipo FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b={$newObjr->PRSASA_ConsInte__PREGUN_b}");
        if($sqlTipoCampo && $sqlTipoCampo->num_rows ==1){
            $sqlTipoCampo=$sqlTipoCampo->fetch_object();
            $tipo=$sqlTipoCampo->tipo;
            switch($tipo){
                case '-1':
        $saltos .= '
        $("#'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").css(\'display\', \'inherit\');
        $("#'.$newObjr->PRSASA_ConsInte__SECCIO_b.' input, #'.$newObjr->PRSASA_ConsInte__SECCIO_b.' select").prop(\'disabled\', false);
        $("#tabS_'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").css(\'visibility\', \'initial\');
        $("#tabS_'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").addClass(\'active\');
        $("#link_'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").css(\'display\', \'inherit\');
        $("#link_'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").parent().addClass(\'active\');
        $("#tabS_'.$newObjr->PRSASA_ConsInte__SECCIO_b.' input, #tabS_'.$newObjr->PRSASA_ConsInte__SECCIO_b.' select").prop(\'disabled\', false);
                        ';
                    break;
                case '9':
        $saltos .= '
        $("#'.$newObjr->PRSASA_NombCont__b.'").css(\'opacity\', 1); 
        ';
                    break;
                default:
        $saltos .= '
        $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', false);
        ';

                    break;
            }
        }else{
        $saltos .= '
            $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', false);
        ';
        }            
    }

    // Valido que la lista dependa de la respuesta de una lista anterior para realizar una agrupacion
    if($obj->PREGUN_ConsInte_PREGUN_Depende_b > 0){

        $lisopcDepende = 0;
        $condiciones = '';
        $cabeceraCondicion = '';
        $opcionesCondicion = '';

        $sqlOpcionesLista = "SELECT * FROM {$BaseDatos_systema}.LISOPC WHERE LISOPC_ConsInte__OPCION_b = {$obj->lista} order by LISOPC_ConsInte__LISOPC_Depende_b ASC";
        $resOpcionesLista = $mysqli->query($sqlOpcionesLista);

        if($resOpcionesLista && $resOpcionesLista->num_rows > 0){

            while ($row = $resOpcionesLista->fetch_object()) {

                if($lisopcDepende == 0){
                    $cabeceraCondicion = '
                        if($(this).val() == \''.$row->LISOPC_ConsInte__b.'\' ';
                    $opcionesCondicion .= $row->LISOPC_ConsInte__b;

                }else{
                    if($lisopcDepende == $row->LISOPC_ConsInte__LISOPC_Depende_b){

                        $cabeceraCondicion .= ' || $(this).val() == \''.$row->LISOPC_ConsInte__b.'\'';
                        $opcionesCondicion .= ",{$row->LISOPC_ConsInte__b}";

                    }else{

                        $condiciones .= $cabeceraCondicion.' ){';

                        // lleno el cuerpo de la condicion

                        $cuerpo = '';
                        $sqlC = "SELECT * FROM {$BaseDatos_systema}.PRSASA JOIN {$BaseDatos_systema}.PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = {$obj->id} AND PRSADE_ConsInte__OPCION_b IN ({$opcionesCondicion}) GROUP BY PRSASA_NombCont__b";
                        $resC = $mysqli->query($sqlC);

                        if($resC && $resC->num_rows > 0){
                            while ($row2 = $resC->fetch_object()) {
                                $cuerpo .= '
                                    $("#'.$row2->PRSASA_NombCont__b.'").prop(\'disabled\', false);
                                ';
                            }
                        }

                        $condiciones .=  $cuerpo;
                        $condiciones .= '
                        }
                        ';

                        $cabeceraCondicion = '
                        if($(this).val() == \''.$row->LISOPC_ConsInte__b.'\' ';
                        
                        $opcionesCondicion = $row->LISOPC_ConsInte__b;
                    }
                }

                $lisopcDepende = $row->LISOPC_ConsInte__LISOPC_Depende_b;
            }

            $condiciones .= $cabeceraCondicion;
            $condiciones .= ' ){';
            // lleno el cuerpo de la condicion

            $cuerpo = '';
            $sqlC = "SELECT * FROM {$BaseDatos_systema}.PRSASA JOIN {$BaseDatos_systema}.PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = {$obj->id} AND PRSADE_ConsInte__OPCION_b IN ({$opcionesCondicion}) GROUP BY PRSASA_NombCont__b";
            $resC = $mysqli->query($sqlC);

            if($resC && $resC->num_rows > 0){
                while ($row2 = $resC->fetch_object()) {
                    $cuerpo .= '
                            $("#'.$row2->PRSASA_NombCont__b.'").prop(\'disabled\', false);
                    ';
                }
            }
            $condiciones .= $cuerpo;
            $condiciones .= '
                        }
            ';

        }

        $saltos .= $condiciones;
    }

    $objr=[];
    $newObjr=[];
    $result = $mysqli->query($GuionsSql);
    while($objr = $result->fetch_object()){
        /* Ya tengo la opcion ahora vamos a buscar los PRSASA de estas opciones */
    $saltos .= '
        //JDBD-20-05-17: funcion para saltos.
        if($(this).val() == \''.$objr->PRSADE_ConsInte__OPCION_b.'\'){';
        $newSql = "SELECT * FROM ".$BaseDatos_systema.".PRSASA JOIN ".$BaseDatos_systema.".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = ".$obj->id." AND PRSADE_ConsInte__OPCION_b = ".$objr->PRSADE_ConsInte__OPCION_b.";";
        $newResult = $mysqli->query($newSql);
        while ($newObjr = $newResult->fetch_object()) {
            $sqlTipoCampo=$mysqli->query("SELECT PREGUN_Tipo______b AS tipo FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b={$newObjr->PRSASA_ConsInte__PREGUN_b}");
            if($sqlTipoCampo && $sqlTipoCampo->num_rows ==1){
                $sqlTipoCampo=$sqlTipoCampo->fetch_object();
                $tipo=$sqlTipoCampo->tipo;
                switch($tipo){
                    case '-1':
                          $saltos .= '
                            $("#'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").css(\'display\', \'none\');
                            $("#'.$newObjr->PRSASA_ConsInte__SECCIO_b.' input, #'.$newObjr->PRSASA_ConsInte__SECCIO_b.' select").prop(\'disabled\', true);
                            $("#tabS_'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").css(\'visibility\', \'hidden\');
                            $("#tabS_'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").removeClass(\'active\');
                            $("#link_'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").css(\'display\', \'none\');
                            $("#link_'.$newObjr->PRSASA_ConsInte__SECCIO_b.'").removeClass(\'active\');
                            $("#tabS_'.$newObjr->PRSASA_ConsInte__SECCIO_b.' input, #tabS_'.$newObjr->PRSASA_ConsInte__SECCIO_b.' select").prop(\'disabled\', true);
                          ';
                        break;
                    case '6':
                          $saltos .= '
                            $("#'.$newObjr->PRSASA_NombCont__b.'").closest(".form-group").removeClass("has-error"); 
                            $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
                          ';
                          if ($newObjr->PRSASA_Limpiar_b == 1) {
                              $saltos .= '//JDBD-20-05-17: Clareamos segun salto definido.
                            $("#'.$newObjr->PRSASA_NombCont__b.'").val("0").trigger("change")';
                          }
                        break;
                    case '9':
                          $saltos .= '
                            $("#'.$newObjr->PRSASA_NombCont__b.'").css(\'opacity\', 0); 
                          ';
                        break;
                    case '11':
                          $saltos .= '
                            $("#'.$newObjr->PRSASA_NombCont__b.'").closest(".form-group").removeClass("has-error"); 
                            $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
                          ';
                          if ($newObjr->PRSASA_Limpiar_b == 1) {
                              $saltos .= '//JDBD-20-05-17: Clareamos segun salto definido.
                            $("#'.$newObjr->PRSASA_NombCont__b.'").html("<option value=\'0\'>Seleccione</option>")';
                          }
                        break;
                    default:
                          $saltos .= '
                            $("#'.$newObjr->PRSASA_NombCont__b.'").closest(".form-group").removeClass("has-error"); 
                            $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
                          ';
                          if ($newObjr->PRSASA_Limpiar_b == 1) {
                              $saltos .= '//JDBD-20-05-17: Clareamos segun salto definido.
                            $("#'.$newObjr->PRSASA_NombCont__b.'").val("")';
                          }
                        break;
                }
            }else{
              $saltos .= '
                $("#'.$newObjr->PRSASA_NombCont__b.'").closest(".form-group").removeClass("has-error"); 
                $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
              ';
              if ($newObjr->PRSASA_Limpiar_b == 1) {
                  $saltos .= '//JDBD-20-05-17: Clareamos segun salto definido.
                $("#'.$newObjr->PRSASA_NombCont__b.'").val("")';
              }
                
            }
        }
    $saltos .= '
        }
    ';
    }
    $sqlOptionSelected=$mysqli->query("SELECT DISTINCT(PRSASA_NombCont__b),PRSASA_ConsInte__SECCIO_b,PRSASA_Limpiar_b,PRSASA_ConsInte__SECCIO_b,PRSASA_ConsInte__PREGUN_b FROM {$BaseDatos_systema}.PRSADE LEFT JOIN {$BaseDatos_systema}.PRSASA ON PRSADE_ConsInte__b=PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__GUION__b={$id_a_generar} AND PRSADE_ConsInte__PREGUN_b={$obj->id}");
    if($sqlOptionSelected && $sqlOptionSelected->num_rows > 0){
        $saltos .= '    if($(this).val()==0){';
        while($option=$sqlOptionSelected->fetch_object()){
            $sqlTipoCampo=$mysqli->query("SELECT PREGUN_Tipo______b AS tipo FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b={$option->PRSASA_ConsInte__PREGUN_b}");
            if($sqlTipoCampo && $sqlTipoCampo->num_rows ==1){
                $sqlTipoCampo=$sqlTipoCampo->fetch_object();
                $tipo=$sqlTipoCampo->tipo;
                switch($tipo){
                    case '-1':
                          $saltos .= '
                            $("#'.$option->PRSASA_ConsInte__SECCIO_b.'").css(\'display\', \'none\');
                            $("#'.$option->PRSASA_ConsInte__SECCIO_b.' input, #'.$option->PRSASA_ConsInte__SECCIO_b.' select").prop(\'disabled\', true);
                            $("#tabS_'.$option->PRSASA_ConsInte__SECCIO_b.'").css(\'visibility\', \'hidden\');
                            $("#tabS_'.$option->PRSASA_ConsInte__SECCIO_b.'").removeClass(\'active\');
                            $("#link_'.$option->PRSASA_ConsInte__SECCIO_b.'").css(\'display\', \'none\');
                            $("#link_'.$option->PRSASA_ConsInte__SECCIO_b.'").removeClass(\'active\');
                            $("#tabS_'.$option->PRSASA_ConsInte__SECCIO_b.' input, #tabS_'.$option->PRSASA_ConsInte__SECCIO_b.' select").prop(\'disabled\', true);
                          ';
                        break;
                    case '6':
                          $saltos .= '
                            $("#'.$option->PRSASA_NombCont__b.'").closest(".form-group").removeClass("has-error"); 
                            $("#'.$option->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
                          ';
                          if ($option->PRSASA_Limpiar_b == 1) {
                              $saltos .= '//JDBD-20-05-17: Clareamos segun salto definido.
                              var htmlSelect=$("#'.$option->PRSASA_NombCont__b.'").html();
                              $("#'.$option->PRSASA_NombCont__b.'").html(htmlSelect);';
                          }
                        break;
                    case '9':
                          $saltos .= '
                            $("#'.$option->PRSASA_NombCont__b.'").css(\'opacity\', 0); 
                          ';
                        break;
                    case '11':
                          $saltos .= '
                            $("#'.$option->PRSASA_NombCont__b.'").closest(".form-group").removeClass("has-error"); 
                            $("#'.$option->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
                          ';
                          if ($option->PRSASA_Limpiar_b == 1) {
                              $saltos .= '//JDBD-20-05-17: Clareamos segun salto definido.
                            $("#'.$option->PRSASA_NombCont__b.'").html("<option value=\'0\'>Seleccione</option>")';
                          }
                        break;
                    default:
                          $saltos .= '
                            $("#'.$option->PRSASA_NombCont__b.'").closest(".form-group").removeClass("has-error"); 
                            $("#'.$option->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
                          ';
                          if ($option->PRSASA_Limpiar_b == 1) {
                              $saltos .= '//JDBD-20-05-17: Clareamos segun salto definido.
                            $("#'.$option->PRSASA_NombCont__b.'").val("")';
                          }
                        break;
                }
            }else{
              $saltos .= '
                $("#'.$option->PRSASA_NombCont__b.'").closest(".form-group").removeClass("has-error"); 
                $("#'.$option->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
              ';
              if ($option->PRSASA_Limpiar_b == 1) {
                  $saltos .= '//JDBD-20-05-17: Clareamos segun salto definido.
                $("#'.$option->PRSASA_NombCont__b.'").val("");';
              }
                
            }    
        }
        $saltos .="\n".' }';

    }
}

//Aqui lo que estamos haciendo es validar que la pregunta tenga o no hijos o dependencias
$validarSiEsPadre = "SELECT PREGUN_ConsInte__b, PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte_PREGUN_Depende_b = ".$obj->id;
$resEsPadre = $mysqli->query($validarSiEsPadre);
$hijosdeEsteGuion = "\n";
if($resEsPadre->num_rows > 0){
    while ($keyPadre = $resEsPadre->fetch_object()) {
        $hijosdeEsteGuion .='
        $.ajax({
            url    : \'<?php echo $url_crud; ?>\',
            type   : \'post\',
            data   : { getListaHija : true , opcionID : \''.$keyPadre->PREGUN_ConsInte__OPCION_B.'\' , idPadre : $(this).val() },
            success : function(data){
                var opt'.$guion_c.$keyPadre->PREGUN_ConsInte__b.' = $("#'.$guion_c.$keyPadre->PREGUN_ConsInte__b.'").attr("opt");
                $("#'.$guion_c.$keyPadre->PREGUN_ConsInte__b.'").html(data);
                if (opt'.$guion_c.$keyPadre->PREGUN_ConsInte__b.' != null) {
                    $("#'.$guion_c.$keyPadre->PREGUN_ConsInte__b.'").val(opt'.$guion_c.$keyPadre->PREGUN_ConsInte__b.').trigger("change");
                }
            }
        });
        ';
    }   
}

                    $funciones_jsx .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $(".'.$guion_c.$obj->id.'").change(function(){ '.$saltos.' 
        //Esto es la parte de las listas dependientes
        '.$hijosdeEsteGuion.'
    });';


                    
                  fputs($fp , $campo);
                  fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;


                    case '13':

                    $select2 .= "\n".'
    $("#'.$guion_c.$obj->id.'").select2();';

$campo = '
                    <!-- CAMPO DE TIPO LISTA / Respuesta -->
                    <div class="form-group">
                        <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                        <select class="form-control input-sm select2'.$strClassSaltoR_t.'"  style="width: 100%;" name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, LISOPC_Respuesta_b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = '.$obj->lista.' ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value=\'".$obje->OPCION_ConsInte__b."\' respuesta = \'".$obje->LISOPC_Respuesta_b."\'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="respuesta_'.$guion_c.$obj->id.'" id="respuesta_Lbl'.$guion_c.$obj->id.'">Respuesta</label>
                        <textarea id="respuesta_'.$guion_c.$obj->id.'" class="form-control" placeholder="Respuesta"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA  / Respuesta -->';

$GuionsSql = "SELECT PRSADE_ConsInte__OPCION_b  FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = '".$obj->id."' GROUP BY PRSADE_ConsInte__OPCION_b";
$result = $mysqli->query($GuionsSql);
$saltos = '';
if($result->num_rows > 0){
    $sinsalto = '';
    $tamanho = 0;
    
    while($objr = $result->fetch_object()){
        $newSql = "SELECT * FROM ".$BaseDatos_systema.".PRSASA JOIN ".$BaseDatos_systema.".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = ".$obj->id." AND PRSADE_ConsInte__OPCION_b = ".$objr->PRSADE_ConsInte__OPCION_b.";";
        $newResult = $mysqli->query($newSql);
        while ($newObjr = $newResult->fetch_object()) {
    $saltos .= '
        $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', false);
    ';
    
        }

    }
    $objr=[];
    $newObjr=[];
    $result = $mysqli->query($GuionsSql);
    while($objr = $result->fetch_object()){
        /* Ya tengo la opcion ahora vamos a buscar los PRSASA de estas opciones */
    $saltos .= '
        if($(this).val() == \''.$objr->PRSADE_ConsInte__OPCION_b.'\'){';
        $newSql = "SELECT * FROM ".$BaseDatos_systema.".PRSASA JOIN ".$BaseDatos_systema.".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = ".$obj->id." AND PRSADE_ConsInte__OPCION_b = ".$objr->PRSADE_ConsInte__OPCION_b.";";
        $newResult = $mysqli->query($newSql);
        while ($newObjr = $newResult->fetch_object()) {
          $saltos .= '
            $("#'.$newObjr->PRSASA_NombCont__b.'").closest(".form-group").removeClass("has-error"); 
            $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', true);
          ';
            if ($newObjr->PRSASA_Limpiar_b == 1) {
                $saltos .= '//JDBD-20-05-17: Clareamos segun salto definido.
        $("#'.$newObjr->PRSASA_NombCont__b.'").val("");';
            }
        }
    $saltos .= '
        }
    ';
    }
}

//Aqui lo que estamos haciendo es validar que la pregunta tenga o no hijos o dependencias
$validarSiEsPadre = "SELECT PREGUN_ConsInte__b, PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte_PREGUN_Depende_b = ".$obj->id;
$resEsPadre = $mysqli->query($validarSiEsPadre);
$hijosdeEsteGuion = "\n";
if($resEsPadre->num_rows > 0){
    while ($keyPadre = $resEsPadre->fetch_object()) {
        $hijosdeEsteGuion .='
        $.ajax({
            url    : \'<?php echo $url_crud; ?>\',
            type   : \'post\',
            data   : { getListaHija : true , opcionID : \''.$keyPadre->PREGUN_ConsInte__OPCION_B.'\' , idPadre : $(this).val() },
            success : function(data){
                var opt'.$guion_c.$keyPadre->PREGUN_ConsInte__b.' = $("#'.$guion_c.$keyPadre->PREGUN_ConsInte__b.'").attr("opt"); 
                $("#'.$guion_c.$keyPadre->PREGUN_ConsInte__b.'").html(data);
                if (opt'.$guion_c.$keyPadre->PREGUN_ConsInte__b.' != null) {
                    $("#'.$guion_c.$keyPadre->PREGUN_ConsInte__b.'").val(opt'.$guion_c.$keyPadre->PREGUN_ConsInte__b.').trigger("change");
                }
            }
        });
        ';
    }   
}

                    $funciones_jsx .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$guion_c.$obj->id.'").change(function(){ '.$saltos.' 
        //Esto es la parte de las listas dependientes
        var respuesta =  $("#'.$guion_c.$obj->id.' option:selected").attr(\'respuesta\');
        $("#respuesta_'.$guion_c.$obj->id.'").val(respuesta);
        '.$hijosdeEsteGuion.'
    });';



                    
                  fputs($fp , $campo);
                  fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;




                    case '11':
                        $campoGuion = $obj->id; //JDBD-20-05-11: Este es el id del campo actual.
                        $guionSelect2 = $obj->guion; //JDBD-20-05-11: Este es el id de la base de donde proviene la lista compleja.
                        $campo = '
                            <!-- JDBD-20-05-11: CAMPO DE TIPO LISTA COMPLEJA -->
                            <div class="form-group">
                                <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                                <select class="form-control input-sm select2'.$strClassSaltoR_t.'" style="width: 100%;"  name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'">
                                    <option value="0">Seleccione</option>
                                </select>
                            </div>
                            <!-- JDBD-20-05-11: FIN DEL CAMPO TIPO LISTA COMPLEJA -->';

                        $strSQLPregui_t = "SELECT A.PREGUI_ConsInte__b AS id, B.CAMPO__Nombre____b, C.CAMPO__Nombre____b AS campo_2, A.PREGUI_Consinte__CAMPO__GUI_B, B.CAMPO__Tipo______b AS campoTipo FROM ".$BaseDatos_systema.".PREGUI A JOIN ".$BaseDatos_systema.".CAMPO_ B ON A.PREGUI_ConsInte__CAMPO__b = B.CAMPO__ConsInte__b JOIN ".$BaseDatos_systema.".CAMPO_ C ON A.PREGUI_Consinte__CAMPO__GUI_B = C.CAMPO__ConsInte__b WHERE A.PREGUI_ConsInte__PREGUN_b = ".$campoGuion." AND A.PREGUI_Consinte__CAMPO__GUI_B != 0";
                        $resSQLPregui_t = $mysqli->query($strSQLPregui_t);
                        $strLlenadoDinamico_t = '';

                        if ($resSQLPregui_t->num_rows > 0) {
                            while ($row = $resSQLPregui_t->fetch_object()) {
                                if($row->campoTipo == '6'){
                                    $strLlenadoDinamico_t .= '
                                    $("#'.$row->campo_2.'").val(data.'.$row->CAMPO__Nombre____b.').trigger("change");';
                                }else{
                                    $strLlenadoDinamico_t .= '
                                    $("#'.$row->campo_2.'").val(data.'.$row->CAMPO__Nombre____b.');';
                                }
                            }
                        }
                        
                        //JDBD-20-05-11: Vamos a traer las opciones del campo de la base de datos elegida para esta lista compleja.
                        $strSQLPregui_t = "SELECT MIN(A.PREGUI_ConsInte__b) AS id, B.CAMPO__Nombre____b, C.PREGUN_ConsInte__GUION__b AS base FROM ".$BaseDatos_systema.".PREGUI A JOIN ".$BaseDatos_systema.".CAMPO_ B ON A.PREGUI_ConsInte__CAMPO__b = B.CAMPO__ConsInte__b JOIN ".$BaseDatos_systema.".PREGUN C ON B.CAMPO__ConsInte__PREGUN_b = C.PREGUN_ConsInte__b WHERE A.PREGUI_ConsInte__PREGUN_b = ".$campoGuion." AND A.PREGUI_Consinte__CAMPO__GUI_B = 0";
                        $resSQLPregui_t = $mysqli->query($strSQLPregui_t);

                        if ($resSQLPregui_t->num_rows > 0) {
                            
                            $objSQLPregui_t = $resSQLPregui_t->fetch_object();

                            $select2 .= '
                            $("#'.$guion_c.$obj->id.'").select2({
                                placeholder: "Buscar",
                                allowClear: false,
                                minimumInputLength: 3,
                                ajax:{
                                    url: \'<?=$url_crud;?>?CallDatosCombo_Guion_'.$guion_c.$obj->id.'=si\',
                                    dataType: \'json\',
                                    type : \'post\',
                                    delay: 250,
                                    data: function (params) {
                                        return {
                                            q: params.term
                                        };
                                    },
                                    processResults: function(data) {
                                        try{
                                            try{
                                                after_select_'.$guion_c.$obj->id.'(data,document.getElementsByClassName(\'select2-search__field\')[0].value);
                                            }catch{
                                                console.log(\'error\');
                                            }
                                            return {
                                                results: $.map(data, function(obj) {
                                                    return {id: obj.id,text: obj.text};
                                                })
                                            };
                                        }catch{
                                            console.log(\'error\');
                                        }
                                    },
                                    cache: true
                                }
                            });

                            $("#'.$guion_c.$obj->id.'").change(function(){
                                var valor = $(this).attr("opt");
                                if ($(this).val() && $(this).val() !==0) {
                                    valor = $(this).val();
                                }
                                $.ajax({
                                    url   : "<?php echo $url_crud;?>",
                                    data  : { dameValoresCamposDinamicos_Guion_'.$guion_c.$obj->id.' : valor},
                                    type  : "post",
                                    dataType : "json",
                                    success  : function(data){
                                        $("#'.$guion_c.$obj->id.'").html(\'<option value="\'+data.G'.$guionSelect2.'_ConsInte__b+\'" >\'+data.'.$objSQLPregui_t->CAMPO__Nombre____b.'+\'</option>\');
                                        '.$strLlenadoDinamico_t.'
                                    }
                                });
                            });';

                            $funcionesCampoGuion .= '
                            if(isset($_GET[\'CallDatosCombo_Guion_'.$guion_c.$obj->id.'\'])){
                                $Ysql = "SELECT G'.$guionSelect2.'_ConsInte__b as id, '.$objSQLPregui_t->CAMPO__Nombre____b.' as text FROM ".$BaseDatos.".G'.$guionSelect2.' WHERE '.$objSQLPregui_t->CAMPO__Nombre____b.' LIKE \'%".$_POST[\'q\']."%\'";
                                $guion = $mysqli->query($Ysql);
                                $i = 0;
                                $datos = array();
                                while($obj = $guion->fetch_object()){
                                    $datos[$i][\'id\'] = $obj->id;
                                    $datos[$i][\'text\'] = $obj->text;
                                    $i++;
                                } 
                                echo json_encode($datos);
                            }
                            if(isset($_POST["dameValoresCamposDinamicos_Guion_'.$guion_c.$obj->id.'"])){
                                 $strSQLOpt_t = "SELECT * FROM ".$BaseDatos.".G'.$objSQLPregui_t->base.' WHERE G'.$objSQLPregui_t->base.'_ConsInte__b = ".$_POST["dameValoresCamposDinamicos_Guion_'.$guion_c.$obj->id.'"];
                                $resSQLOpt_t = $mysqli->query($strSQLOpt_t);

                                if ($resSQLOpt_t) {
                                    if ($resSQLOpt_t->num_rows > 0) {
                                        $objSQLOpt_t = $resSQLOpt_t->fetch_object();
                                        echo json_encode($objSQLOpt_t);
                                    }
                                }

                            }';

                            fputs($fp , $campo);
                            fputs($fp , chr(13).chr(10));

                        }

                        break;

                    case '8':
$campo = '  
                    <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                    <div class="form-group">
                        <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" data-error="Before you wreck yourself"  > 
                            </label>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO SI/NO -->';
                    $funciones_js .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$guion_c.$obj->id.'").change(function(){
        if($(this).is(":checked")){

        }else{

        }
    });';
    fputs($fp , $campo);
    fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;
            
            

                    case '9':
$campo = '  
                    <!-- lIBRETO O LABEL -->
                    <p style="text-align:justify;" id="'.$guion_c.$obj->id.'">'.validateURL($obj->titulo_pregunta).'</p>
                    <!-- FIN LIBRETO -->';
                    fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea

                    break;
                        
                    case '12':
                        if(!$booSubForm){
                                $tabsDeMaestro = '
<!-- SI ES MAESTRO - DETALLE CREO LAS TABS --> '."\n".'
<div class="nav-tabs-custom">';
            $contenidoMaestro = "\n".'
    <ul class="nav nav-tabs">';
            $tabscontenidoMaestro = "\n".'
    <div class="tab-content">';

            $LsqlMaestro = "SELECT a.*,b.PREGUN_ConsInte__SECCIO_b FROM ".$BaseDatos_systema.".GUIDET a join ".$BaseDatos_systema.".PREGUN b on a.GUIDET_ConsInte__PREGUN_Cre_b=PREGUN_ConsInte__b WHERE PREGUN_ConsInte__SECCIO_b={$obj->PREGUN_ConsInte__SECCIO_b} and GUIDET_ConsInte__GUION__Mae_b = ".$id_a_generar;
            $EjecutarMaetsro = $mysqli->query($LsqlMaestro);
            $EsONoEs = 0;
            while ($key = $EjecutarMaetsro->fetch_object()) {    

                $iTsub[] = $Kaka;
                if (is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b) || $key->GUIDET_ConsInte__PREGUN_Ma1_b=='-1') {
                    if(is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)){
                        $idSub[] = 'id';
                    }else{
                        $idSub[] = 'id+"&idBd=si"';
                    }
                }else{
                    $idSub[] = '
        $("#'.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.'").val()';
                }
                

                $activo = '';
                if($Kaka == 0){
                   $activo = 'active';
                }//cierro el ide  $Kaka == 0


                $funcionCargar = '';
                $functionDescargar = '';
                $functionRecargar ='';
                $funcionDeguardadoDeLagrilla ='';
                

                $funcionDeCargadoDelaGrilla = '';
                $funcionCargarComboCuandoSeaMaestro = '';
                $camposXmlParallenar = '';
                $camposSubgrilla ='';
                $tabsFinal = '';
                $EsONoEs = 1; 

                if (is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)) {
                    $yourfather = '&yourfather=NULL';
                }else{
                    if($key->GUIDET_ConsInte__PREGUN_Ma1_b == '-1'){
                        $yourfather='&yourfather=-1';
                    }else{
                        $yourfather = '&yourfather=\'+ padre +\'';
                    }
                }
                if($hayqueValidar > 0){
                    $strValidacion_t = $camposValidaciones;
                }else{
                    $strValidacion_t = '';
                }//Este es el if de hay que validar
                
                //BUSCAMOS EL CAMPO ORIGEN DEL SUBFORMULARIO
                $sqlCampoOrigen=$mysqli->query("SELECT PREGUN_ConsInte__b FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$key->GUIDET_ConsInte__GUION__Det_b} AND PREGUN_Texto_____b = 'ORIGEN_DY_WF'");
                $strOrigenSub='';
                if($sqlCampoOrigen && $sqlCampoOrigen->num_rows==1){
                    $sqlCampoOrigen=$sqlCampoOrigen->fetch_object();
                    $strOrigenSub="&G{$key->GUIDET_ConsInte__GUION__Det_b}_C{$sqlCampoOrigen->PREGUN_ConsInte__b}=Subformulario";
                }

                //validar si hay que establecer una comuniación entre iframes
                    //validamos el campo padre
                if($key->GUIDET_ConsInte__PREGUN_Totalizador_b !=null && $key->GUIDET_ConsInte__PREGUN_Totalizador_b !=0){
                    //este formulario es padre ahora debemos validar la función matematica a realizar en este campo 1=suma 2=resta
                    $operPadre=$key->GUIDET_Oper_Totalizador_b;
                    $campoPadre ="var valorInicial=$('#{$guion_c}{$key->GUIDET_ConsInte__PREGUN_Totalizador_b}').val();";
                    $campoPadre .="\n".
                    "if(valorInicial == 0 || valorInicial =='' || valorInicial =='NaN'){
        valorInicial=0;
                    }";
                    $campoPadre .="\n";
                    $isNumero=true;
                    switch($operPadre){
                        case '1':
                            $campoPadre .="var valor=parseInt(valorInicial)+parseInt(e.data);";
                            $campoPadre .="\n".
                            $campoPadre .="if(isNaN(valor)){
                                valor=valorInicial;
                            }";
                            break;
                        case '2':
                            $campoPadre .="var valor=parseInt(valorInicial)-parseInt(e.data);";
                            $campoPadre .="\n".
                            $campoPadre .="if(isNaN(valor)){
                                valor=valorInicial;
                            }";
                            //$campoPadre .="$('#{$guion_c}{$key->GUIDET_ConsInte__PREGUN_Totalizador_b}').val($(this).val()-e.data);";
                            break;
                        case '3':
                            $campoPadre .="var valor=e.data;";
                            $isNumero=false;
                            break;
                        default:
                            break;
                    }
                    if($isNumero){
                        $campoPadre .="\n".
                            "try{
                                $('#{$guion_c}{$key->GUIDET_ConsInte__PREGUN_Totalizador_b}').val(parseInt(valor)).change();
                            }catch(e){
                                $('#{$guion_c}{$key->GUIDET_ConsInte__PREGUN_Totalizador_b}').val(valor).change();
                            }";
                    }else{
                        $campoPadre .="\n".
                            "try{
                                $('#{$guion_c}{$key->GUIDET_ConsInte__PREGUN_Totalizador_b}').val(valor).change();
                            }catch(e){
                                $('#{$guion_c}{$key->GUIDET_ConsInte__PREGUN_Totalizador_b}').val(valor).change();
                            }";
                    }
                    $isNumero=true;
                    $campoPadre .="\n".
                        "$('#{$guion_c}{$key->GUIDET_ConsInte__PREGUN_Totalizador_b}').focus();"."\n".
                        "$('#{$guion_c}{$key->GUIDET_ConsInte__PREGUN_Totalizador_b}').blur();";
                    //$campoPadre .="\n".
                        //"$('#refrescarGrillas').click();";
                }
                
                //VALIDAMOS SI HAY QUE ESTABLECER UNA COMUNICACION DE ENVIO DE INFORMACION DE CAMPOS DEL FORMULARIO AL SUBFORMULARIO
                    $sqlComunicacion=$mysqli->query("SELECT * FROM ".$BaseDatos_systema.".COMUFORM WHERE COMUFORM_Guion_hijo_b={$key->GUIDET_ConsInte__GUION__Det_b}");
                    if($sqlComunicacion && $sqlComunicacion->num_rows>0){
                        $funcionComunicacion="function llamarHijo() {
                                                $.blockUI({
                                                    baseZ: 2000,
                                                    message: '<img src=\"assets/img/clock.gif\" />Por favor espere a que se cargue el subformulario'
                                                });                                                
                                                var formDatas = $('#FormularioDatos').serializeArray();
                                                var iframe = document.getElementById('frameContenedor');
                                                iframe.contentWindow.postMessage(formDatas, '*');
                                                $.unblockUI();
                                            }";
                        $LlamarComunicacion='let padre= sendMessage("llamarhijo");';
                        $CamposComunicacion.="if(typeof(e.data)=='string'){
                            llamarHijo();
                        }else{
                            if(e.data.hasOwnProperty('accion')){
                                if(e.data.accion=='llamadaDesdeG'){
                                    parent.postMessage(e.data, '*');
                                }
                            }else{
                                $.each(e.data, function(item, elemento){"."\n";
                        while($comunicacion=$sqlComunicacion->fetch_object()){
                            $CamposComunicacion.="if(elemento.name=='G{$key->GUIDET_ConsInte__GUION__Mae_b}_C{$comunicacion->COMUFORM_IdPregun_Padre_b}'){
                                                    $('#G{$key->GUIDET_ConsInte__GUION__Det_b}_C{$comunicacion->COMUFORM_IdPregun_hijo_b}').val(elemento.value).trigger('change');
                                                }";
                        }
                        $CamposComunicacion.="});"."\n";
                        $CamposComunicacion.="}"."\n";        
                        $CamposComunicacion.="}"."\n";                 
                    }

                $urlGuidet="new_index.php";
                $agendador=false;
                //VALIDAMOS SI ES UN GUION POR MODULO 1->AGENDADOR
                $sqlTipoGuidet=$mysqli->query("SELECT GUION_ByModulo_b FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__b={$key->GUIDET_ConsInte__GUION__Det_b}");
                if($sqlTipoGuidet && $sqlTipoGuidet -> num_rows > 0){
                    $sqlTipoGuidet=$sqlTipoGuidet->fetch_object();
                    if($sqlTipoGuidet->GUION_ByModulo_b == 1){ // agendador
                        $urlGuidet="formularios/agendador/agendador.php";
                        $agendador=true;
                    }
                }

                $botonSalvar2 = "\n".'

           
            if($("#oper").val() == \'add\'){
                if(before_save()){
                    $("#frameContenedor").attr(\'src\', \'https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/'.$urlGuidet.'?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.$strOrigenSub.'&view=si&formaDetalle=si&formularioPadre='.$id_a_generar.'&idFather=\'+idTotal+\''.$yourfather.'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?><?php if(isset($_GET[\'campana_crm\'])){ echo "&campana_crm=".$_GET[\'campana_crm\']; }?>&usuario=<?=$idUsuario?><?php if(isset($_GET[\'consinte\'])){ echo "&consinte=".$_GET[\'consinte\']; }?>\');
                    $("#editarDatos").modal(\'show\');
                }else{
                    before_save();
                    var d = new Date();
                    var h = d.getHours();
                    var horas = (h < 10) ? \'0\' + h : h;
                    var dia = d.getDate();
                    var dias = (dia < 10) ? \'0\' + dia : dia;
                    var fechaFinal = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
                    $("#FechaFinal").val(fechaFinal);
                    
                    var valido = 0;
                    '.$strValidacion_t.'
                    if (validado == \'0\') {
                        var form = $("#FormularioDatos");
                        //Se crean un array con los datos a enviar, apartir del formulario 
                        var formData = new FormData($("#FormularioDatos")[0]);
                        $.ajax({
                           url: \'<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?=$idUsuario?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET[\'id_gestion_cbx\'])){ echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?><?php if(isset($_GET[\'campana_crm\'])){ echo "&campana_crm=".$_GET[\'campana_crm\']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>\',  
                            type: \'POST\',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            //una vez finalizado correctamente
                            success: function(data){
                                try{
                                    afterSave(data);
                                }catch(e){}
                                if(data){
                                    //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                    if($("#oper").val() == \'add\'){
                                        idTotal = data;
                                    }else{
                                        idTotal= $("#hidId").val();
                                    }
                                    $("#hidId").val(idTotal);

                                    int_guardo = 1;
                                    $(".llamadores").attr(\'padre\', idTotal);
                                    $("#frameContenedor").attr(\'src\', \'https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/'.$urlGuidet.'?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.$strOrigenSub.'&view=si&formaDetalle=si&formularioPadre='.$id_a_generar.'&idFather=\'+idTotal+\''.$yourfather.'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'&action=add<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?><?php if(isset($_GET[\'campana_crm\'])){ echo "&campana_crm=".$_GET[\'campana_crm\']; }?>&usuario=<?=$idUsuario?><?php if(isset($_GET[\'consinte\'])){ echo "&consinte=".$_GET[\'consinte\']; }?>\');
                                    $("#editarDatos").modal(\'show\');
                                    $("#oper").val(\'edit\');

                                }else{
                                    //Algo paso, hay un error
                                    alertify.error(\'Un error ha ocurrido\');
                                }                
                            },
                            //si ha ocurrido un error
                            error: function(){
                                after_save_error();
                                alertify.error(\'Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde\');
                            }
                        });
                    }
                }
            }else{

                $("#frameContenedor").attr(\'src\', \'https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/'.$urlGuidet.'?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.$strOrigenSub.'&view=si&idFather=\'+idTotal+\''.$yourfather.'&formaDetalle=si&formularioPadre='.$id_a_generar.'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'&action=add<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?><?php if(isset($_GET[\'campana_crm\'])){ echo "&campana_crm=".$_GET[\'campana_crm\']; }?>&usuario=<?=$idUsuario?><?php if(isset($_GET[\'consinte\'])){ echo "&consinte=".$_GET[\'consinte\']; }?>\');
                $("#editarDatos").modal(\'show\');
            }';

                $contenidoMaestro .=  "\n".'
        <li class="'.$activo.'">
            <a href="#tab_'.$Kaka.'" data-toggle="tab" id="tabs_click_'.$Kaka.'">'.$key->GUIDET_Nombre____b.'</a>
        </li>';

                $botonLlamado = '';
                if($key->GUIDET_Modo______b != 1){
                    $botonLlamado = '<button title="Crear '.$key->GUIDET_Nombre____b.'" class="btn btn-primary btn-sm llamadores" padre="\'<?php if(isset($_GET[\'yourfather\'])){ echo $_GET[\'yourfather\']; }else{ echo "0"; }?>\' " id="btnLlamar_'.$Kaka.'"><i class="fa fa-plus"></i></button>';
                    $darlePadreAlHijo = '$("#btnLlamar_'.$Kaka.'").attr(\'padre\', <?php echo $_GET[\'registroId\'];?>);';
                    $darlePadreAlHijo_2 = '$("#btnLlamar_'.$Kaka.'").attr(\'padre\', id);';
                    $darlePadreAlHijo_3 = '$("#btnLlamar_'.$Kaka.'").attr(\'padre\', <?php echo $_GET[\'user\'];?>);';
                    $arrdarlePadreAlHijo[$Kaka] =$darlePadreAlHijo;
                    $arrdarlePadreAlHijo_2[$Kaka] =$darlePadreAlHijo_2;
                    $arrdarlePadreAlHijo_3[$Kaka] =$darlePadreAlHijo_3;
                }//cierro el if de $key->GUIDET_Modo______b != 1

                $tabscontenidoMaestro .= "\n".'
        <div class="tab-pane '.$activo.'" id="tab_'.$Kaka.'"> 
            <table class="table table-hover table-bordered" id="tablaDatosDetalless'.$Kaka.'" width="100%">
            </table>
            <div id="pagerDetalles'.$Kaka.'">
            </div> 
            '.$botonLlamado.'
        </div>';

            if (is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b) || $key->GUIDET_ConsInte__PREGUN_Ma1_b=='-1'){
                $cargarHijos = 'idTotal';
                if($key->GUIDET_ConsInte__PREGUN_Ma1_b=='-1'){
                    $cargarHijos .='+"&idBd=si"';
                }
            }else{
                $cargarHijos = '$("#'.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.'").val()';
            }


            $tabsFinal = "\n".'
    $("#tabs_click_'.$Kaka.'").click(function(){ 
        $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); 
        $("#btnLlamar_'.$Kaka.'").attr(\'padre\', '.$cargarHijos.');
        var id_'.$Kaka.' = '.$cargarHijos.';
        $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); //funcion Recargar 
        cargarHijos_'.$Kaka.'(id_'.$Kaka.');
    });

    $("#btnLlamar_'.$Kaka.'").click(function( event ) {
        event.preventDefault(); 
        var padre = '.$cargarHijos.';
        '.$botonSalvar2.'
    });';

                $funcionCargarDatosDeLasPutasGrillas .= "\n".'
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); 
            cargarHijos_'.$Kaka.'(id);';



                $limpiadordeGrillas = "\n".'
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\');';
                $arrlimpiadordeGrillas[$Kaka]=$limpiadordeGrillas;

                $LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error, PREGUN_OperEntreCamp_____b, PREGUN_MostrarSubForm FROM ".$BaseDatos_systema.".PREGUN LEFT JOIN  ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b  WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b." ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";

                $LsqlDetalle2 = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN  WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b." ORDER BY PREGUN_OrdePreg__b LIMIT 0,2";
                          
                $functionDescargar .= "\n".'
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); //funcion descargar ';


                $functionRecargar .= "\n".'
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); //funcion Recargar 
            ';

                $campos = $mysqli->query($LsqlDetalle);
                $i = 0;
                $titulos ='';
                $titulosSubForm='';
                $orden = '';

               // echo $LsqlDetalle;
                while ($key3 = $campos->fetch_object()){
                    if($key3->tipo_Pregunta != '9' && $key3->tipo_Pregunta != '12'){
                        if($i==0){
                            if($key3->PREGUN_MostrarSubForm == 1){
                                $titulosSubForm = ',\''.($key3->titulo_pregunta).'\'';    
                            }
                            $titulos = '\''.($key3->titulo_pregunta).'\'';
                            $orden = 'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key3->id;
                        }else{
                            if($key3->PREGUN_MostrarSubForm == 1){
                                $titulosSubForm .= ',\''.($key3->titulo_pregunta).'\'';    
                            }                            
                            $titulos .= ',\''.($key3->titulo_pregunta).'\'';
                            
                        }//if de $i ==0
                        $i++;
                    }// if $key3->tipo_Pregunta != '9'
                    
                }//while key3 = $campos->fetch_object()

                $camposDelgguardadodeLagrilla = '
        if(isset($_POST["oper"])){
            $Lsql  = \'\';

            $validar = 0;
            $LsqlU = "UPDATE ".$BaseDatos.".G'.$key->GUIDET_ConsInte__GUION__Det_b.' SET "; 
            $LsqlI = "INSERT INTO ".$BaseDatos.".G'.$key->GUIDET_ConsInte__GUION__Det_b.'(";
            $LsqlV = " VALUES ("; '."\n";

                $subgrilla .= "\n".'
                var subgrid_table_id_'.$Kaka.', pager_id_'.$Kaka.'; 

                subgrid_table_id_'.$Kaka.' = subgrid_id+"_t_'.$Kaka.'"; 

                pager_id_ = "p_"+subgrid_table_id_'.$Kaka.'; 

                $("#"+subgrid_id).append("<table id=\'"+subgrid_table_id_'.$Kaka.'+"\' class=\'scroll\'></table><div id=\'"+pager_id_'.$Kaka.'+"\' class=\'scroll\'></div>"); 

                jQuery("#"+subgrid_table_id_'.$Kaka.').jqGrid({ 
                    url:\'<?=$url_crud;?>?callDatosSubgrilla_'.$Kaka.'=si&id=\'+row_id,
                    datatype: \'xml\',
                    mtype: \'POST\',
                    colNames:[\'id\''.$titulosSubForm.', \'padre\'],
                    colModel: [ 
                        {    
                            name:\'providerUserId\',
                            index:\'providerUserId\', 
                            width:100,editable:true, 
                            editrules:{
                                required:false, 
                                edithidden:true
                            },
                            hidden:true, 
                            editoptions:{ 
                                dataInit: function(element) {                     
                                    $(element).attr("readonly", "readonly"); 
                                } 
                            }
                        }';
                $functionsBeforeSelect = '';
                $funcionCargar .= "\n".'
    function cargarHijos_'.$Kaka.'(id_'.$Kaka.'){
        $.jgrid.defaults.width = \'1225\';
        $.jgrid.defaults.height = \'650\';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = \'Bootstrap\';
        var lastSels;
        $("#tablaDatosDetalless'.$Kaka.'").jqGrid({
            url:\'<?=$url_crud;?>?callDatosSubgrilla_'.$Kaka.'=si&id=\'+id_'.$Kaka.',
            datatype: \'xml\',
            mtype: \'POST\',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:[\'id\''.$titulosSubForm.', \'padre\'],
            colModel:[

                {
                    name:\'providerUserId\',
                    index:\'providerUserId\', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }';

                $campos_2 = $mysqli->query($LsqlDetalle);
                $camposAbuscar = 'SELECT G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b';
                $joins2 = '';
                $otraTabla = '';
                $Kakaroto =0;

                while ($key2 = $campos_2->fetch_object()){
                    if($key2->PREGUN_MostrarSubForm==1){
                        switch ($key2->tipo_Pregunta) {
                            case '15':
                    //JDBD-2020-05-10 : Separador       
                                $funcionCargar .= "\n".'
                    ,
                    { 
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index: \'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }';
                    //JDBD-2020-05-10 : Separador
                                $camposSubgrilla .= "\n".'
                            ,
                            { 
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index: \'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }';
                    //JDBD-2020-05-10 : Separador
                                $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                                $camposXmlParallenar .= "\n".'
                echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';
                    //JDBD-2020-05-10 : Separador

                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){

                                    $camposDelgguardadodeLagrilla .= ' 

                if (isset($_FILES["FG'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G'.$key->GUIDET_ConsInte__GUION__Det_b.'/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G'.$key->GUIDET_ConsInte__GUION__Det_b.'")){
                        mkdir("/Dyalogo/tmp/G'.$key->GUIDET_ConsInte__GUION__Det_b.'", 0777);
                    }

                    if ($_FILES["FG'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]["size"] != 0) {
                        $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = $_FILES["FG'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]["tmp_name"];
                        $nG'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = $fechUp."_".$_FILES["FG'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]["name"];
                        $rutaFinal = $destinoFile.$nG'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';
                        if (is_uploaded_file($G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')) {
                            move_uploaded_file($G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.', $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$nG'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                        $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                        $LsqlV .= $separador."\'".$nG'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                        $validar = 1;
                    }
                }'."\n";


                                }//cierro el if $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id               
                    //JDBD-2020-05-10 : Separador
                            break;
                            case '1':
                                $funcionCargar .= "\n".'
                    ,
                    { 
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index: \'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }';

                                $camposSubgrilla .= "\n".'
                            ,
                            { 
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index: \'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }';

                                $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                                $camposXmlParallenar .= "\n".'
                echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';  
                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                    $camposDelgguardadodeLagrilla .= ' 

                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $validar = 1;
                }

                                                                               '."\n";    
                                }//cierro el if $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id               

                            break;

                            case '2':
                                $funcionCargar .="\n". '
                    ,
                    {
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:150, 
                        editable: true 
                    }';

                                $camposSubgrilla .="\n". '
                            ,
                            { 
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:150, 
                                editable: true 
                            }';
                                $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                                $camposXmlParallenar .=  "\n".'
                echo "<cell><![CDATA[". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."]]></cell>";';  

                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                    $camposDelgguardadodeLagrilla .= '  

                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $validar = 1;
                }
                                                                               '."\n";  
                                }//cierro el if de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                            break;

                            case '3':
                                $funcionCargar .="\n".' 
                    ,
                    {  
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:80 ,
                        editable: true, 
                        searchoptions: {
                            sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                        }, 
                        editoptions:{
                            size:20,';


                                //

                                 $LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
                        $cur_result = $mysqli->query($LsqlCadena);
                        $itsCadena = false;
                        while ($key22 = $cur_result->fetch_object()) {  
                            /* ahora toca buscar el valor de esos campos en la jugada */
                            $buscar = '${'.NombreParaFormula($key2->titulo_pregunta).'}';

                            //$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);


                            if (stristr(trim($key22->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
                                $itsCadena = true;
                                //echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
                                /* Toca hacer todo el frito */
                                $funcionCargar .= "\n".'
                                dataInit:function(el){
                                    $(el).numeric();
                                },
                                dataEvents: [
                                    {  
                                        type: \'change\',
                                        fn: function(e) {
                                            var r = this.id;
                                            var rId = r.split(\'_\');';



                                $LsqlCadenaX = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
                                $cadenaFinalX = trim(str_replace('=', '', $key22->PREGUN_OperEntreCamp_____b));

                                $cur_resultX = $mysqli->query($LsqlCadenaX);

                                while ($keyX = $cur_resultX->fetch_object()) {

                                    /* ahora toca buscar el valor de esos campos en la jugada */
                                    $buscarX = '${'.NombreParaFormula($keyX->PREGUN_Texto_____b).'}';

                                    $reemplazo = 'Number($("#"+rId[0]+"_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$keyX->PREGUN_ConsInte__b.'").val())';

                                    $cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
                                }

                                $funcionCargar .= "\n".'

            var totales = '.$cadenaFinalX.';
            $("#"+rId[0]+"_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key22->PREGUN_ConsInte__b.'").val(totales.toFixed(2));';

                                $funcionCargar .= "\n".'
                                        }
                                    }
                                ]';
                            }
                        }

                        if($itsCadena == false){
                            /* No esta metido en ningun lado */
                            $funcionCargar .= "\n".'
                                dataInit:function(el){
                                    $(el).numeric();
                                }';
                        }

                                $funcionCargar .= '
                        }

                    }';

                                $camposSubgrilla .="\n".' 
                            ,
                            {  
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:80 ,
                                editable: true, 
                                searchoptions: {
                                    sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                                }, 
                                editoptions:{
                                    size:20,
                                    dataInit:function(el){
                                        $(el).numeric();
                                    }
                                }

                            }';

                                $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;         
                                $camposXmlParallenar .= "\n".'
                echo "<cell>". $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."</cell>"; ';
                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                    $camposDelgguardadodeLagrilla .= ' 
                $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id .'= NULL;
                //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){    
                    if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'\'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"];
                        $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                        $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                        $LsqlV .= $separador."\'".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                        $validar = 1;
                    }
                }'."\n";
                                }//cierro el if $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                            break;

                            case '4':
                                $funcionCargar .="\n".'
                    ,
                    {  
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:80 ,
                        editable: true, 
                        searchoptions: {
                            sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                        }, 
                        editoptions:{
                            size:20,';

                            $LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
                        $cur_result = $mysqli->query($LsqlCadena);
                        $itsCadena = false;
                        while ($key22 = $cur_result->fetch_object()) {  

                            $buscar = '${'.NombreParaFormula($key2->titulo_pregunta).'}';


                            //$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);


                            if (stristr(trim($key22->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
                                $itsCadena = true;
                                //echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
                                /* Toca hacer todo el frito */
                                $funcionCargar .= "\n".'
                                dataInit:function(el){
                                    $(el).numeric();
                                },
                                dataEvents: [
                                    {  
                                        type: \'change\',
                                        fn: function(e) {
                                            var r = this.id;
                                            var rId = r.split(\'_\');';



                                $LsqlCadenaX = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
                                $cadenaFinalX = trim(str_replace('=', '', $key22->PREGUN_OperEntreCamp_____b));

                                $cur_resultX = $mysqli->query($LsqlCadenaX);

                                while ($keyX = $cur_resultX->fetch_object()) {

                                    /* ahora toca buscar el valor de esos campos en la jugada */
                                    $buscarX = '${'.NombreParaFormula($keyX->PREGUN_Texto_____b).'}';

                                    $reemplazo = 'Number($("#"+rId[0]+"_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$keyX->PREGUN_ConsInte__b.'").val())';

                                    $cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
                                }

                                $funcionCargar .= "\n".'
            var totales = '.$cadenaFinalX.';
            $("#"+rId[0]+"_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key22->PREGUN_ConsInte__b.'").val(totales.toFixed(2));';

                                $funcionCargar .= "\n".'
                                        }
                                    }
                                ]';
                            }
                        }

                        if($itsCadena == false){
                            /* No esta metido en ningun lado */
                            $funcionCargar .= "\n".'
                                dataInit:function(el){
                                    $(el).numeric();
                                }';
                        }

                                $funcionCargar .= '
                        }

                    }';

                                $camposSubgrilla .="\n".'
                            ,
                            { 
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:80 ,
                                editable: true, 
                                searchoptions: {
                                    sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                                }, 
                                editoptions:{
                                    size:20,
                                    dataInit:function(el){
                                        $(el).numeric({ decimal : ".",  negative : false, scale: 4 });
                                    }
                                } 
                            }';

                                $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                                $camposXmlParallenar .= "\n".'
                echo "<cell>". $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."</cell>"; ';

                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                    $camposDelgguardadodeLagrilla .= '  
                $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = NULL;
                //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){    
                    if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'\'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"];
                        $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                        $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                        $LsqlV .= $separador."\'".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                        $validar = 1;
                    }
                }'."\n";

                                }//cierro el if de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                            break;

                            case '5':
                                $funcionCargar .="\n".'
                    ,
                    {  
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:120 ,
                        editable: true ,
                        formatter: \'text\', 
                        searchoptions: {
                            sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                        }, 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                $(el).datepicker({
                                    language: "es",
                                    autoclose: true,
                                    todayHighlight: true
                                });
                            },
                            defaultValue: function(){
                                var currentTime = new Date();
                                var month = parseInt(currentTime.getMonth() + 1);
                                month = month <= 9 ? "0"+month : month;
                                var day = currentTime.getDate();
                                day = day <= 9 ? "0"+day : day;
                                var year = currentTime.getFullYear();
                                return year+"-"+month + "-"+day;
                            }
                        }
                    }';
                                $camposSubgrilla .="\n".'
                            ,
                            {  
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:120 ,
                                editable: true ,
                                formatter: \'text\', 
                                searchoptions: {
                                    sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                                }, 
                                editoptions:{
                                    size:20,
                                    dataInit:function(el){
                                        $(el).datepicker({
                                            language: "es",
                                            autoclose: true,
                                            todayHighlight: true
                                        });
                                    },
                                    defaultValue: function(){
                                        var currentTime = new Date();
                                        var month = parseInt(currentTime.getMonth() + 1);
                                        month = month <= 9 ? "0"+month : month;
                                        var day = currentTime.getDate();
                                        day = day <= 9 ? "0"+day : day;
                                        var year = currentTime.getFullYear();
                                        return year+"-"+month + "-"+day;
                                    }
                                }
                            }';

                                $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                                $camposXmlParallenar .= "\n".'
                if($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' != \'\'){
                    echo "<cell>". explode(\' \', $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')[0]."</cell>";
                }else{
                    echo "<cell></cell>";
                }'; 
                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){                        
                                    $camposDelgguardadodeLagrilla .= '
                $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = NULL;
                //este es de tipo date hay que preguntar si esta vacia o no
                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){    
                    if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'\'){
                        if(validateDate(str_replace(\' \', \'\',$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])." 00:00:00")){
                            $separador = "";
                            if($validar == 1){
                                $separador = ",";
                            }

                            $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = "\'".str_replace(\' \', \'\',$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])." 00:00:00\'";
                            $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';
                            $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                            $LsqlV .= $separador.$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';
                            $validar = 1;
                        }    
                    }
                }'."\n";
                                }//$key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                            break;

                            case '10':

                                $funcionCargar .="\n".'
                    ,
                    {  
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:70 ,
                        editable: true ,
                        formatter: \'text\', 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                //Timepicker
                                var options = {  //hh:mm 24 hour format only, defaults to current time
                                    timeFormat: \'HH:mm:ss\',
                                    interval: 5,
                                    minTime: \'10\',
                                    dynamic: false,
                                    dropdown: true,
                                    scrollbar: true
                                }; 
                                $(el).timepicker(options);
                                $(".timepicker").css("z-index", 99999 );
                            }
                        }
                    }';

                                $camposSubgrilla .="\n".'
                            ,
                            {  
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:70 ,
                                editable: true ,
                                formatter: \'text\', 
                                editoptions:{
                                    size:20,
                                    dataInit:function(el){
                                        //Timepicker
                                         var options = {  //hh:mm 24 hour format only, defaults to current time
                                            timeFormat: \'HH:mm:ss\',
                                            interval: 5,
                                            minTime: \'10\',
                                            dynamic: false,
                                            dropdown: true,
                                            scrollbar: true
                                        }; 
                                        $(el).timepicker(options);


                                    }
                                }
                            }';

                                $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                                $camposXmlParallenar .= "\n".'
                if($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' != \'\'){
                    echo "<cell>". explode(\' \', $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')[1]."</cell>";
                }else{
                    echo "<cell></cell>";
                }'; 
                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){                            
                                    $camposDelgguardadodeLagrilla .= ' 
                $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = NULL;
                //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){    
                    if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'\' && $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'undefined\' && $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'null\'){
                        $fecha = date(\'Y-m-d\');
                        if(validateDate($fecha." ".str_replace(\' \', \'\',$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]))){
                            $separador = "";
                            if($validar == 1){
                                $separador = ",";
                            }

                            $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = "\'".$fecha." ".str_replace(\' \', \'\',$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])."\'";
                            $LsqlU .= $separador."  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."";
                            $LsqlI .= $separador."  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                            $LsqlV .= $separador.$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';
                            $validar = 1;
                        }    
                    }
                }'."\n";  
                                }//cierro el If de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                            break;

                            case '6':
                                $funcionCargar .="\n".'
                    ,
                    {  
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                        }
                    }';

                                $camposSubgrilla .="\n".'
                            ,
                            {  
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:120 ,
                                editable: true, 
                                edittype:"select" , 
                                editoptions: {
                                    dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                                }
                            }';
                                $camposAbuscar .= ', '.$alfabeto[$Kakaroto].'.LISOPC_Nombre____b as  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                                $joins2 .= ' LEFT JOIN ".$BaseDatos_systema.".LISOPC as '.$alfabeto[$Kakaroto].' ON '.$alfabeto[$Kakaroto].'.LISOPC_ConsInte__b =  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                                $camposXmlParallenar .= "\n".'
                echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';  
                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                    $camposDelgguardadodeLagrilla .= ' 
                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $validar = 1;
                }'."\n";  
                                }//cierro el if de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                            break;



                            case '13':
                                $funcionCargar .="\n".'
                    ,
                    {  
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                        }
                    }';

                                $camposSubgrilla .="\n".'
                            ,
                            {  
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:120 ,
                                editable: true, 
                                edittype:"select" , 
                                editoptions: {
                                    dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                                }
                            }';
                                $camposAbuscar .= ', '.$alfabeto[$Kakaroto].'.LISOPC_Nombre____b as  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                                $joins2 .= ' LEFT JOIN ".$BaseDatos_systema.".LISOPC as '.$alfabeto[$Kakaroto].' ON '.$alfabeto[$Kakaroto].'.LISOPC_ConsInte__b =  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                                $camposXmlParallenar .= "\n".'
                echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';  
                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                    $camposDelgguardadodeLagrilla .= ' 
                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $validar = 1;
                }'."\n";  
                                }//cierro el if de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                            break;



                            case '11':

                                /* Neceistamos obtener los campos que vamos a llenar dinamicamente */
                                $campoGuion = $key2->id;
                                $guionSelect2 = $key2->guion;

                                $Lsql_Principal_Guion = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$guionSelect2;
                                $resX = $mysqli->query($Lsql_Principal_Guion);
                                $datosX = $resX->fetch_array();


                                /* Neceistamos obtener los campos que vamos a llenar dinamicamente */
                                $SqlCamposAMostrar = "SELECT PREGUI_ConsInte__PREGUN_b, Camp1.CAMPO__Nombre____b as PREGUI_ConsInte__CAMPO__b , Camp2.CAMPO__Nombre____b as PREGUI_Consinte__CAMPO__GUI_B  FROM  ".$BaseDatos_systema.".PREGUI JOIN ".$BaseDatos_systema.".CAMPO_ as Camp1 ON Camp1.CAMPO__ConsInte__b =  PREGUI_ConsInte__CAMPO__b JOIN ".$BaseDatos_systema.".CAMPO_ as Camp2 ON Camp2.CAMPO__ConsInte__b =  PREGUI_Consinte__CAMPO__GUI_B WHERE PREGUI_Consinte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b;

                                $CamposQuevaAmostrarEnconsulta = $mysqli->query($SqlCamposAMostrar);
                                $verdaderoscamposAmostrar = " G".$guionSelect2."_ConsInte__b as id ";
                                $verdaderosCamposAllenar  = '';
                                $verdaderosCamposAllenarGuion  = '';
                                while ($keyXMostar = $CamposQuevaAmostrarEnconsulta->fetch_object()) {
                                    $verdaderoscamposAmostrar .=  ", ".$keyXMostar->PREGUI_ConsInte__CAMPO__b;
                                    $verdaderosCamposAllenar  .= '
                    $data[$i][\''.$keyXMostar->PREGUI_Consinte__CAMPO__GUI_B.'\'] = $key->'.$keyXMostar->PREGUI_ConsInte__CAMPO__b.";\n";
                                    $verdaderosCamposAllenarGuion  .= "
                                                $('#'+rId[0]+'_".$keyXMostar->PREGUI_Consinte__CAMPO__GUI_B."').val(item.".$keyXMostar->PREGUI_Consinte__CAMPO__GUI_B.");\n";
                                }

                                $funcionesCampoGuion .= "\n".'
            if(isset($_GET[\'MostrarCombo_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'])){
                echo \'<select class="form-control input-sm"  name="'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'" id="'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'">\';
                echo \'<option >Buscar</option>\';
                echo \'</select>\';
            }

            if(isset($_GET[\'CallDatosCombo_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'])){
                $Ysql = "SELECT G'.$guionSelect2.'_ConsInte__b as id,  G'.$guionSelect2.'_C'.$datosX['GUION__ConsInte__PREGUN_Pri_b'].' as text FROM ".$BaseDatos.".G'.$guionSelect2.' WHERE G'.$guionSelect2.'_C'.$datosX['GUION__ConsInte__PREGUN_Pri_b'].' LIKE \'%".$_POST[\'q\']."%\'";
                $guion = $mysqli->query($Ysql);
                $i = 0;
                $datos = array();
                while($obj = $guion->fetch_object()){
                    $datos[$i][\'id\'] = $obj->id;
                    $datos[$i][\'text\'] = $obj->text;
                    $i++;
                } 
                echo json_encode($datos);
            }

            if(isset($_POST[\'dameValoresCamposDinamicos_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'])){
                $Lsql = "SELECT '.$verdaderoscamposAmostrar.' FROM ".$BaseDatos.".G'.$guionSelect2.' WHERE G'.$guionSelect2.'_ConsInte__b = ".$_POST[\'dameValoresCamposDinamicos_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'];
                $res = $mysqli->query($Lsql);
                $data = array();
                $i = 0;
                while ($key = $res->fetch_object()) {
                    '.$verdaderosCamposAllenar.'
                    $i++;
                }

                echo json_encode($data);
            }
            ';

                                $funcionCargar .="\n".'
                    ,
                    {  
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:300 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: \'<?=$url_crud;?>?MostrarCombo_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'=si\',
                            dataInit:function(el){
                                $(el).select2({ 
                                    placeholder: "Buscar",
                                    allowClear: false,
                                    minimumInputLength: 3,
                                    ajax: {
                                        url: \'<?=$url_crud;?>?CallDatosCombo_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'=si\',
                                        dataType: \'json\',
                                        type : \'post\',
                                        delay: 250,
                                        data: function (params) {
                                            return {
                                                q: params.term
                                            };
                                        },
                                        processResults: function(data) {
                                            return {
                                                results: $.map(data, function(obj) {
                                                    return {
                                                        id: obj.id,
                                                        text: obj.text
                                                    };
                                                })
                                            };
                                        },
                                        cache: true
                                    }
                                });  
                            },
                            dataEvents: [
                                {  
                                    type: \'change\',
                                    fn: function(e) {
                                        var r = this.id;
                                        var rId = r.split(\'_\');

                                        $.ajax({
                                            url   : \'<?php echo $url_crud;?>\',
                                            data  : { dameValoresCamposDinamicos_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' : this.value },
                                            type  : \'post\',
                                            dataType : \'json\',
                                            success  : function(data){
                                                $.each(data, function(i, item){
                                                '.$verdaderosCamposAllenarGuion.'
                                                });
                                            }
                                        });
                                    }
                                }
                            ]
                        }
                    }';

                                $camposSubgrilla .="\n".'
                            ,
                            {  
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:300 ,
                                editable: true, 
                                edittype:"select" , 
                                editoptions: {
                                    dataUrl: \'<?=$url_crud;?>?CallDatosCombo_Guion_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'=si\',
                                    dataInit:function(el){
                                        $(el).select2({ 
                                            templateResult: function(data) {
                                                var r = data.text.split(\'|\');
                                                var row = \'<div class="row">\';
                                                var totalRows = 12 / r.length;
                                                for(i= 0; i < r.length; i++){
                                                    row += \'<div class="col-md-\'+ Math.round(totalRows) +\'">\' + r[i] + \'</div>\';
                                                }
                                                row += \'</div>\';
                                                var $result = $(row);
                                                return $result;
                                            },
                                            templateSelection : function(data){
                                                var r = data.text.split(\'|\');
                                                return r[0];
                                            }
                                        });
                                        $(el).change(function(){
                                            var valores = $(el + " option:selected").text();
                                            var campos =  $(el + " option:selected").attr("dinammicos");
                                            var r = valores.split(\'|\');
                                            if(r.length > 1){

                                                var c = campos.split(\'|\');
                                                for(i = 1; i < r.length; i++){
                                                   if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                                    // the input field is not a select
                                                        $("#"+ rowid +"_"+c[i]).val(r[i]);
                                                    }else{
                                                        var change = r[i].replace(\' \', \'\'); 
                                                        $("#"+ rowid +"_"+c[i]).val(change).change();
                                                    }
                                                }
                                            }
                                        });
                                        //campos sub grilla
                                    }
                                }
                            }';

                                $CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$key2->id." ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
                                $campoSqlE = $mysqli->query($CampoSql);

                                while ($cam = $campoSqlE->fetch_object()) {
                                    //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                                    $campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$cam->PREGUI_ConsInte__CAMPO__b;
                                    //echo $campoObtenidoSql;
                                    $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

                                    while ($o = $resultCamposObtenidos->fetch_object()) {
                                        $camposAbuscar .= ', '.$o->CAMPO__Nombre____b .' as G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                                    }//cierro el While $o = $resultCamposObtenidos->fetch_object()
                                }//Cierro el While de $cam = $campoSqlE->fetch_object()

                                $joins2 .= ' LEFT JOIN ".$BaseDatos.".G'.$key2->guion.' ON G'.$key2->guion.'_ConsInte__b  =  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                                $camposXmlParallenar .= "\n".'
                echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';  
                                                                 // $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;



                                //Esto es para cargar ese pinche gion en el crud
                                //Primero necesitamos obener el campo que vamos a usar
                                $campoGuion = $key2->id;
                                $guionSelect2 = $key2->guion;
                                //Luego buscamos los campos en la tabla Pregui
                                $CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$campoGuion;
                                //recorremos esos campos para ir a buscarlos en la tabla campo_
                                $CampoSqlR = $mysqli->query($CampoSql);
                                $camposconsultaGuion = ' G'.$key2->guion.'_ConsInte__b as id ';


                                $camposAmostrar = '';
                                $valordelArray = 0;
                                $nombresDeCampos = '';
                                $camposAcolocarDinamicamente = '0';

                                while($objet = $CampoSqlR->fetch_object()){
                                    //aqui obtenemos los campos que se colocara el valor dinamicamente al seleccionar una opcion del guion, ejemplo ciudad - departamento- pais..
                                    if($objet->PREGUI_Consinte__CAMPO__GUI_B != 0){
                                        //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                                        $campoamostrarSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$objet->PREGUI_Consinte__CAMPO__GUI_B;
                                        $campoamostrarSqlE = $mysqli->query($campoamostrarSql);
                                        while($campoNombres = $campoamostrarSqlE->fetch_object()){
                                            $camposAcolocarDinamicamente .= '|'.$campoNombres->CAMPO__Nombre____b;
                                        }//cierro el while de ($campoNombres = $campoamostrarSqlE->fetch_object()
                                    }//cierro el if de $objet->PREGUI_Consinte__CAMPO__GUI_B != 0

                                    //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                                    $campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$objet->PREGUI_ConsInte__CAMPO__b;
                                    //echo $campoObtenidoSql;
                                    $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);
                                    while ($objCampo = $resultCamposObtenidos->fetch_object()) {

                                        //Busco el nombre del campo para el nombre de las columnas
                                        $selectGuion = "SELECT PREGUN_Texto_____b as titulo_pregunta FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b =".$objCampo->CAMPO__ConsInte__PREGUN_b;
                                        $selectGuionE = $mysqli->query($selectGuion);
                                        while($o = $selectGuionE->fetch_object()){
                                            if($valordelArray == 0){
                                                $nombresDeCampos .= ($o->titulo_pregunta);
                                            }else{
                                                $nombresDeCampos .= ' | '.($o->titulo_pregunta).'';
                                            }
                                        }//cierro el while de $o = $selectGuionE->fetch_object()

                                        //aÃ±adimos los campos a la consulta que se necesita para cargar el guion
                                        $camposconsultaGuion .=', '.$objCampo->CAMPO__Nombre____b;
                                        if($valordelArray == 0){
                                            $camposAmostrar .= '".utf8_encode($obj->'.$objCampo->CAMPO__Nombre____b.')."';
                                        }else{
                                            $camposAmostrar .= ' | ".utf8_encode($obj->'.$objCampo->CAMPO__Nombre____b.')."';
                                        }//cierro el if de $valordelArray == 0

                                        $valordelArray++;
                                    }//cierro el while de $objCampo = $resultCamposObtenidos->fetch_object()
                                }//cierro el while de $objet = $CampoSqlR->fetch_object()




                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){                              
                                    $camposDelgguardadodeLagrilla .= '  
                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                    $validar = 1;
                }
                '."\n";  
                                }//Cierro el If de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id

                            break;

                            case '8':
                                $funcionCargar .="\n". '
                    , 
                    { 
                        name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                        width:70 ,
                        editable: true, 
                        edittype:"checkbox",
                        editoptions: {
                            value:"1:0"
                        } 
                    }';
                                $camposSubgrilla .="\n". '
                            ,
                            { 
                                name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                                width:70 ,
                                editable: true, 
                                edittype:"checkbox",
                                editoptions: {
                                    value:"1:0"
                                } 
                            }';  
                                $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                                $camposXmlParallenar .= "\n".'
                echo "<cell>". $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."</cell>"; ';
                                if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                    $camposDelgguardadodeLagrilla .= ' 
                $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 0;
                //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
                if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                    if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] == \'Yes\'){
                        $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 1;
                    }else if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] == \'off\'){
                        $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 0;
                    }else if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] == \'on\'){
                        $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 1;
                    }else if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] == \'No\'){
                        $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 1;
                    }else{
                        $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] ;
                    }   

                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."";
                    $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador.$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';

                    $validar = 1;
                }else{
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."";
                    $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador.$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';

                    $validar = 1;

                }'."\n";
                                }


                            break;
                            default:

                            break;
                        }//cierro el swich de $key2->tipo_Pregunta
                        $Kakaroto++;                        
                    }
                }//cierroe el while de $key2 = $campos_2->fetch_object()

                $subgrilla .= $camposSubgrilla."\n".'
                        ,
                        { 
                            name: \'Padre\', 
                            index:\'Padre\', 
                            hidden: true , 
                            editable: false, 
                            editrules: { 
                                edithidden:true 
                            },
                            editoptions:{ 
                                dataInit: function(element) {                     
                                    $(element).val(id); 
                                } 
                            }
                        }
                    ], 
                    rowNum:20, 
                    pager: pager_id_'.$Kaka.', 
                    sortname: \'num\', 
                    sortorder: "asc",
                    height: \'100%\' 
                }); 

                jQuery("#"+subgrid_table_id_'.$Kaka.').jqGrid(\'navGrid\',"#"+pager_id_'.$Kaka.',{edit:false,add:false,del:false}) ';                   
                $arrsubgrilla[$Kaka]=$subgrilla;
                $subgrilla='';
                $dobleclick = '';
                if($key->GUIDET_Modo______b != 1){
                    $dobleclick .= ',

            ondblClickRow: function(rowId) {
                $("#frameContenedor").attr(\'src\', \'https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.$strOrigenSub.'&view=si&registroId=\'+ rowId +\'&formaDetalle=si&yourfather=\'+ idTotal +\'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'<?php if(isset($_GET[\'campana_crm\'])){ echo "&campana_crm=".$_GET[\'campana_crm\']; }?>&formularioPadre='.$id_a_generar.'<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?>\');
                $("#editarDatos").modal(\'show\');

            }';
                }//cierro el if de $key->GUIDET_Modo______b != 1

                $funcionCargar .= '
                ,
                { 
                    name: \'Padre\', 
                    index:\'Padre\', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    },
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).val(id_'.$Kaka.'); 
                        } 
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerDetalles'.$Kaka.'",
            rowList: [40,80],
            sortable: true,
            sortname: \''.$orden.'\',
            sortorder: \'asc\',
            viewrecords: true,
            caption: \''.$key->GUIDET_Nombre____b.'\',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_'.$Kaka.'=si&usuario=<?=$idUsuario?>",
            height:\'250px\',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    '.$functionsBeforeSelect.'
                }
                lastSels = rowid;
            }
            '.$dobleclick.'
        });';

                if($key->GUIDET_Modo______b == 1){
                    $funcionCargar .= '
        $(\'#tablaDatosDetalless'.$Kaka.'\').navGrid("#pagerDetalles'.$Kaka.'", { add:false, del: true , edit: false });


        $(\'#tablaDatosDetalless'.$Kaka.'\').inlineNav(\'#pagerDetalles'.$Kaka.'\',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        });';
                }else{//$key->GUIDET_Modo______b == 1
                    //$funcionCargar .='jQuery("#tablaDatosDetalless'.$Kaka.'").jqGrid(\'navGrid\',\'#pagerDetalles'.$Kaka.'\',{});';
                }//cierrro el if $key->GUIDET_Modo______b == 1

                                        

                $funcionCargar .= ' 

        $(window).bind(\'resize\', function() {
            $("#tablaDatosDetalless'.$Kaka.'").setGridWidth($(window).width());
        }).trigger(\'resize\');
    }';

                $estavaina = '';

                if(!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)){
                    $estavaina .= '
        $Lsql = \'SELECT '.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.' FROM \'.$BaseDatos.\'.'.$guion.' WHERE '.$guion.'_ConsInte__b =\'.$id;
        // echo $Lsql;
        $resultado = $mysqli->query($Lsql);
        $numero = 0;

        while( $fila2 = $resultado->fetch_object() ) {
            $numero = $fila2->'.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.';
        }//Lql de esta vaina';

                }else{//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b
                    $estavaina .= '
        $numero = $id; // esta linea es la del padre';
                }//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b


                if(!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b) && $key->GUIDET_ConsInte__PREGUN_Ma1_b != '-1'){
                    $functionLlamarAloshijosLuegoDeCargar = '
        $("#btnLlamar_'.$Kaka.'").attr(\'padre\', $("#'.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.'").val());
            var id_'.$Kaka.' = $("#'.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.'").val();
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); //funcion Recargar 
            cargarHijos_'.$Kaka.'(id_'.$Kaka.');';
                    $arrfunctionLlamarAloshijosLuegoDeCargar[$Kaka]=$functionLlamarAloshijosLuegoDeCargar;
                }else{//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)
                    $functionLlamarAloshijosLuegoDeCargar = '
        $("#btnLlamar_'.$Kaka.'").attr(\'padre\', <?php if(isset($_GET[\'user\'])){ echo $_GET[\'user\']; }else{echo \'$("#hidId").val()\';};?>);
            var id_'.$Kaka.' = <?php if(isset($_GET[\'user\'])){ echo $_GET[\'user\']; }else{echo \'$("#hidId").val()\';};?>;
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); //funcion Recargar 
            cargarHijos_'.$Kaka.'(id_'.$Kaka.');';
                    if($key->GUIDET_ConsInte__PREGUN_Ma1_b == '-1'){
                    $functionLlamarAloshijosLuegoDeCargar = '
        $("#btnLlamar_'.$Kaka.'").attr(\'padre\', <?php if(isset($_GET[\'user\'])){ echo $_GET[\'user\']; }else{echo \'$("#hidId").val()\';};?>);
            var id_'.$Kaka.' = <?php if(isset($_GET[\'user\'])){ echo $_GET[\'user\']; }else{echo \'$("#hidId").val()+"&idBd=si"\';};?>;
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); //funcion Recargar 
            cargarHijos_'.$Kaka.'(id_'.$Kaka.');';    
                    }
                    $arrfunctionLlamarAloshijosLuegoDeCargar[$Kaka]=$functionLlamarAloshijosLuegoDeCargar;
                }//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)

                $funcionDeCargadoDelaGrilla .= "\n".'
    if(isset($_GET["callDatosSubgrilla_'.$Kaka.'"])){

        $numero = $_GET[\'id\'];
        if(isset($_GET[\'idBd\'])){
            $sqlMiembro=$mysqli->query("SELECT G'.$key->GUIDET_ConsInte__GUION__Mae_b.'_CodigoMiembro AS miembro FROM DYALOGOCRM_WEB.G'.$key->GUIDET_ConsInte__GUION__Mae_b.' WHERE G'.$key->GUIDET_ConsInte__GUION__Mae_b.'_ConsInte__b={$numero}");
            if($sqlMiembro && $sqlMiembro-> num_rows ==1){
                $sqlMiembro=$sqlMiembro->fetch_object();
                $numero=$sqlMiembro->miembro;            
            }
        }

        $SQL = "'.$camposAbuscar.' FROM ".$BaseDatos.".G'.$key->GUIDET_ConsInte__GUION__Det_b.' '.$joins2.' ";

        $SQL .= " WHERE G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.' = \'".$numero."\'"; 

        $SQL .= " ORDER BY '.$orden.'";

        // echo $SQL;
        if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
            header("Content-type: application/xhtml+xml;charset=utf-8"); 
        } else { 
            header("Content-type: text/xml;charset=utf-8"); 
        } 

        $et = ">"; 
        echo "<?xml version=\'1.0\' encoding=\'utf-8\'?$et\n"; 
        echo "<rows>"; // be sure to put text data in CDATA
        $result = $mysqli->query($SQL);
        while( $fila = $result->fetch_object() ) {
            echo "<row asin=\'".$fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b."\'>"; 
            echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b)."</cell>"; 
            '.$camposXmlParallenar.'
            echo "</row>"; 
        } 
        echo "</rows>"; 
    }';

                $guardarPadre = '';
                if(!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)){

                    $guardarPadre = '$Lsql = \'SELECT '.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.' FROM \'.$BaseDatos_systema.\'.'.$guion.' WHERE '.$guion.'_ConsInte__b =\'.$_POST["Padre"];
                    // echo $Lsql;
                    $resultado = $mysqli->query($Lsql);
                    $numero = 0;

                    while( $fila2 = $resultado->fetch_object() ) {
                        $numero = $fila2->G547_C7118;
                    } // gusrdar Padre';
                }else{//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)
                    $guardarPadre  ='
                    $numero = $_POST["Padre"];';    
                }//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)


                $funcionDeguardadoDeLagrilla .= '
    if(isset($_GET["insertarDatosSubgrilla_'.$Kaka.'"])){
        '.$camposDelgguardadodeLagrilla.'
            if(isset($_POST["Padre"])){
                if($_POST["Padre"] != \'\'){
                    //esto es porque el padre es el entero
                    $numero = $_POST["Padre"];

                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.' = $numero;
                    $LsqlU .= ", G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.'."";
                    $LsqlI .= ", G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.'";
                    $LsqlV .= ",".$_POST["Padre"];
                }
            }  



            if(isset($_POST[\'oper\'])){
                if($_POST["oper"] == \'add\' ){
                    $LsqlI .= ",  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_Usuario ,  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_FechaInsercion";
                    $LsqlV .= ", ".$_GET[\'usuario\']." , \'".date(\'Y-m-d H:i:s\')."\'";
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == \'edit\' ){
                    $Lsql = $LsqlU." WHERE G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b =".$_POST["providerUserId"]; 
                }else if($_POST[\'oper\'] == \'del\'){
                    $Lsql = "DELETE FROM  ".$BaseDatos.".G'.$key->GUIDET_ConsInte__GUION__Det_b.' WHERE  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b = ".$_POST[\'id\'];
                    $validar = 1;
                }
            }

            if($validar == 1){
                // echo $Lsql;
                if ($mysqli->query($Lsql) === TRUE) {
                    echo $mysqli->insert_id;
                } else {
                    echo \'0\';
                    $queryCondia="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
                    VALUES(\"".$Lsql."\",\"".$mysqli->error."\",\'Insercion Script\')";
                    $mysqli->query($queryCondia);                    
                    echo "Error Haciendo el proceso los registros : " . $mysqli->error;
                }  
            }  
        }
    }';


                $funcionCargarFinal = $funcionCargar;
                $functionDescargarFinal = $functionDescargar;
                $functionRecargarFinal = $functionRecargar;

                $funcionDeguardadoDeLagrillaFinal = $funcionDeguardadoDeLagrilla;
                

                $funcionDeCargadoDelaGrillaFinal = $funcionDeCargadoDelaGrilla;
                $funcionCargarComboCuandoSeaMaestroFinal = $funcionCargarComboCuandoSeaMaestro;
                $camposXmlParallenarFinal = $camposXmlParallenar;
                $camposSubgrillaFinal = $camposSubgrilla;
                $tabsFinalOperacions = $tabsFinal;
                //generar_extra($key->GUIDET_ConsInte__GUION__Det_b);
                if($id_a_generar != $key->GUIDET_ConsInte__GUION__Det_b){
                    generar_Formulario_Script($key->GUIDET_ConsInte__GUION__Det_b,$key->GUIDET_ConsInte__GUION__Mae_b);
                }
                //llenamos los array para la funcionalidad javascript de los subformularios
                $arrfuncionCargarFinal[$Kaka]=$funcionCargarFinal;
                $arrfunctionDescargarFinal[$Kaka]=$functionDescargarFinal;
                $arrfunctionRecargarFinal[$Kaka]=$functionRecargarFinal;
                $arrfuncionDeguardadoDeLagrillaFinal[$Kaka]=$funcionDeguardadoDeLagrillaFinal;
                $arrfuncionDeCargadoDelaGrillaFinal[$Kaka]=$funcionDeCargadoDelaGrillaFinal;
                $arrfuncionCargarComboCuandoSeaMaestroFinal[$Kaka]=$funcionCargarComboCuandoSeaMaestroFinal;
                $arrcamposXmlParallenarFinal[$Kaka]=$camposXmlParallenarFinal;
                $arrcamposSubgrillaFinal[$Kaka]=$camposSubgrillaFinal;
                $arrtabsFinalOperacions[$Kaka]=$tabsFinalOperacions;
                $Kaka++;
            }//cierro el while $key = $EjecutarMaetsro->fetch_object()
            $tabscontenidoMaestro .= "\n".'
    </div>';
            $contenidoMaestro .= "\n".'
    </ul>';
            $tabsDeMaestro .= $contenidoMaestro ."\n". $tabscontenidoMaestro;
            $tabsDeMaestro .= "\n".'
</div>';
            /* si no es maestro no debo escribirlo solo borrar lso cpntenidos de subgrilla */
            if($EsONoEs > 0){
                $tabsDeMaestro='<div class=row>
                        <div class="col-md-12 col-xs-12">
                            '.$tabsDeMaestro.'
                        </div>
                    </div>';
                $crearform=true;
            }else{
                $subgrilla = '';
            }//Cierro el if de $EsONoEs > 0
                        $booSubForm=true;
                        }//cierre de generación de subformularios
                    break;    
                    default:
              
                    break;
                }//Cierro el Swich

$campo = '  
            </div> <!-- AQUIFINCAMPO -->
';    
fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                
                $filaActual += 1;
                if($filaActual >= $maxColumns){
                    $filaActual = 0;
                    $campo = '  
        </div> 
';    
fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                }

                }//cierro el IF


        }//Cierro el Wile de secciones
        if($filaActual > 0){
            if($filaActual < $maxColumns){
                if($maxColumns % $filaActual != 0){
                    $filaActual = 0;
                    $campo = '
        </div> <!-- AQUIFINSALDO1 -->
';
fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea
                }

                if($filaActual == 1){
                    $filaActual = 0;
                    $campo = '
        </div> <!-- AQUIFINSALDO1 -->
';
fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea
                }
            }
        }
        

          /*              $campo = '
            </div>
';    
            fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

*/          if ($seccionAqui->SECCIO_TipoSecc__b == 2) {
                if(isset($crearform)){
                    $campo=$tabsDeMaestro;
                    $campo .= $btnDownCall;
                    unset($crearform);
                }else{
                    $campo = $btnDownCall;
                }
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera boton de link de llamada 
            }

            //aqui cerramos las secciones y obtenemos un solo codigo
            if($seccionAqui->SECCIO_VistPest__b == 1){
                if(isset($crearform)){
                    $campo=$tabsDeMaestro;
                $campo .= '
</div>';
                    unset($crearform);
                }else{
                $campo = '
</div>';    
                }
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

            }else  if($seccionAqui->SECCIO_VistPest__b == 2){
                if(isset($crearform)){
                    $campo=$tabsDeMaestro;
                $campo .= '
</div>';
                    unset($crearform);
                }else{
                $campo = '
</div>';    
                }
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 


            }else  if($seccionAqui->SECCIO_VistPest__b == 3){
                if(isset($crearform)){
                    $campo=$tabsDeMaestro;
                    $campo .= '
                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            '.$ocultarPorPHPEnd;
                    unset($crearform);
                }else{
                   $campo = '
                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            '.$ocultarPorPHPEnd;
                }
            fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 


            }elseif($seccionAqui->SECCIO_VistPest__b == 5){
                $vistaPestana=true;
                if(isset($crearform)){
                    $campo=$tabsDeMaestro;
                    $campo .= '
            </div><!-- AQUIFINSECCION -->'
            .lastItemPestana($seccionAqui->SECCIO_ConsInte__b,$id_a_generar);
                    unset($crearform);
                }else{
        $campo = '
            </div><!-- AQUIFINSECCION -->'
            .lastItemPestana($seccionAqui->SECCIO_ConsInte__b,$id_a_generar);
                }

                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); 

            }else{

                if(isset($crearform)){
                    $campo=$tabsDeMaestro;
                    $campo .= '
        </div>            
    </div>
</div>';
                    unset($crearform);
                }else{
        $campo = '
    </div>
</div>';  
                }

fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); 

            }
        }

if($vistaPestana){
    $ocultarPorPHPEnd='<?php endif; ?>';
    $campo = '
            </div>
        </div>
    </div>'
    .$ocultarPorPHPEnd;
    fputs($fp , $campo);
    fputs($fp , chr(13).chr(10));
}
        //convertir en una sola cadena los array para el js de los subformularios
            $funcionCargarFinal='';
            $functionDescargarFinal='';
            $functionRecargarFinal='';
            $funcionDeguardadoDeLagrillaFinal='';
            $funcionDeCargadoDelaGrillaFinal='';
            $funcionCargarComboCuandoSeaMaestroFinal='';
            $camposXmlParallenarFinal='';
            $camposSubgrillaFinal='';
            $tabsFinalOperacions='';
            $subgrilla='';
            $darlePadreAlHijo='';
            $darlePadreAlHijo_2='';
            $darlePadreAlHijo_3='';
            $limpiadordeGrillas='';
            $functionLlamarAloshijosLuegoDeCargar='';
        for($i=0; $i<count($arrfuncionCargarFinal); $i++){
            $funcionCargarFinal .=$arrfuncionCargarFinal[$i];
            $functionDescargarFinal .=$arrfunctionDescargarFinal[$i];
            $functionRecargarFinal .=$arrfunctionRecargarFinal[$i];
            $funcionDeguardadoDeLagrillaFinal .=$arrfuncionDeguardadoDeLagrillaFinal[$i];
            $funcionDeCargadoDelaGrillaFinal .=$arrfuncionDeCargadoDelaGrillaFinal[$i];
            $funcionCargarComboCuandoSeaMaestroFinal .=$arrfuncionCargarComboCuandoSeaMaestroFinal[$i];
            $camposXmlParallenarFinal .=$arrcamposXmlParallenarFinal[$i];
            $camposSubgrillaFinal .=$arrcamposSubgrillaFinal[$i];
            $tabsFinalOperacions .=$arrtabsFinalOperacions[$i];
            $subgrilla .=$arrsubgrilla[$i];
            $darlePadreAlHijo .=$arrdarlePadreAlHijo[$i];
            $darlePadreAlHijo_2 .=$arrdarlePadreAlHijo_2[$i];
            $darlePadreAlHijo_3 .=$arrdarlePadreAlHijo_3[$i];
            $limpiadordeGrillas .=$arrlimpiadordeGrillas[$i];
            $functionLlamarAloshijosLuegoDeCargar .=$arrfunctionLlamarAloshijosLuegoDeCargar[$i];
            $limpiaSubgrilla=true;
        }
            if(isset($limpiaSubgrilla)){
                $subgrilla .= "\n".'
                }, 
                subGridRowColapsed: function(subgrid_id, row_id) { 
                    // this function is called before removing the data 
                    //var subgrid_table_id; 
                    //subgrid_table_id = subgrid_id+"_t"; 
                    //jQuery("#"+subgrid_table_id).remove(); 
                }';
                unset($limpiaSubgrilla);
            }
        //AHORA TOCA VALIDAR SI ESTE FORMULARIO ES HIJO DE UN FORMULARIO    
            $sqlFormHijo=$mysqli->query("SELECT GUIDET_ConsInte__GUION__Mae_b,GUIDET_ConsInte__PREGUN_Totalizador_H_b FROM {$BaseDatos_systema}.PREGUN JOIN {$BaseDatos_systema}.GUIDET ON PREGUN_ConsInte__b=GUIDET_ConsInte__PREGUN_Totalizador_H_b WHERE GUIDET_ConsInte__GUION__Det_b={$id_a_generar}");
            if($sqlFormHijo && $sqlFormHijo -> num_rows >0){
                while($hijo = $sqlFormHijo->fetch_object()){
                    $campoHijo='= $("#'.$guion_c.$hijo->GUIDET_ConsInte__PREGUN_Totalizador_H_b.'").val()';
                }
                
            }
            
            $sqlComunicacion=$mysqli->query("SELECT * FROM ".$BaseDatos_systema.".COMUFORM WHERE COMUFORM_Guion_hijo_b={$id_a_generar}");
            if($sqlComunicacion && $sqlComunicacion->num_rows>0){
                $LlamarComunicacion='let padre= sendMessage("llamarhijo");';
                $CamposComunicacion.="if(typeof(e.data)=='string'){
                    llamarHijo();
                }else{
                    if(e.data.hasOwnProperty('accion')){
                        if(e.data.accion=='llamadaDesdeG'){
                            parent.postMessage(e.data, '*');
                        }
                    }else{
                        $.each(e.data, function(item, elemento){"."\n";
                            while($comunicacion=$sqlComunicacion->fetch_object()){
                                $CamposComunicacion.="if(elemento.name=='G{$comunicacion->COMUFORM_Guion_Padre_b}_C{$comunicacion->COMUFORM_IdPregun_Padre_b}'){
                                                        $('#G{$id_a_generar}_C{$comunicacion->COMUFORM_IdPregun_hijo_b}').val(elemento.value).trigger('change');
                                                    }"."\n";
                            }
                $CamposComunicacion.="});"."\n";
                $CamposComunicacion.="}"."\n";
                $CamposComunicacion.="}"."\n";
            }

            
            if($GUION__ConsInte__PREGUN_Tip_b != NULL && !is_null($GUION__ConsInte__PREGUN_Tip_b)){
            

            $strSQLAccionesTip_t = "SELECT ACCIONTIPI_ConsInte_LISOPC_Tipi_b AS L, ACCIONTIPI_ConsInte_PREGUN_Campo_b AS C FROM ".$BaseDatos_systema.".ACCIONTIPI WHERE ACCIONTIPI_ConsInte_GUION__b = ".$id_a_generar." AND ACCIONTIPI_ConsInte_PREGUN_Tipi_b = ".$GUION__ConsInte__PREGUN_Tip_b;
            $resSQLAccionesTip_t = $mysqli->query($strSQLAccionesTip_t);
            //echo    $strSQLAccionesTip_t;die(); 
            if ($resSQLAccionesTip_t->num_rows > 0) {
                
                $strAccionTip_t = '';

                while ($row = $resSQLAccionesTip_t->fetch_object()) {
                    $strAccionTip_t .= '
                    if($(this).val() == "'.$row->L.'"){
                        $("#'.$guion_c.$row->C.'").addClass("ReqForTip");
                    }';
                }

                $select2 .= '
                $("#'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.'").change(function(){
                    $(".ReqForTip").closest(".form-group").removeClass("has-error");
                    $(".ReqForTip").removeClass("ReqForTip");
                        '.$strAccionTip_t.'
                });';

            }

$ocultarPorPHP='<?php if(!isset($_GET["intrusionTR"])) : ?>';
$ocultarPorPHPEnd='<?php endif; ?>';
//echo "Si llega  esta parte";
                $campo = '
'.$ocultarPorPHP.'
<div class="row" style="background-color: #FAFAFA; ">
    <br/>
    <?php if(isset($_GET[\'user\'])){ ?>
    <div class="col-md-10 col-xs-9">
        <div class="form-group">
            <select class="form-control input-sm tipificacion" name="tipificacion" id="'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.'">
                <option value="0">Tipificaci&oacute;n</option>
                <?php
                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM ".$BaseDatos_systema.".LISOPC 
                        JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = '.$valorLista.';";
                $obj = $mysqli->query($Lsql);
                while($obje = $obj->fetch_object()){
                    echo "<option value=\'".$obje->OPCION_ConsInte__b."\' efecividad = \'".$obje->MONOEF_EFECTIVA__B."\' monoef=\'".$obje->MONOEF_ConsInte__b."\' TipNoEF = \'".$obje->MONOEF_TipNo_Efe_b."\' cambio=\'".$obje->LISOPC_CambRepr__b."\' importancia = \'".$obje->MONOEF_Importanc_b."\' contacto=\'".$obje->MONOEF_Contacto__b."\'>".($obje->OPCION_Nombre____b)."</option>";

                }          
                ?>
            </select>
            
            <input type="hidden" name="Efectividad" id="Efectividad" value="0">
            <input type="hidden" name="MonoEf" id="MonoEf" value="0">
            <input type="hidden" name="TipNoEF" id="TipNoEF" value="0">
            <input type="hidden" name="FechaInicio" id="FechaInicio" value="0">
            <input type="hidden" name="FechaFinal" id="FechaFinal" value="0">
            <input type="hidden" name="MonoEfPeso" id="MonoEfPeso" value="0">
            <input type="hidden" name="ContactoMonoEf" id="ContactoMonoEf" value="0">
        </div>
    </div>
    <div class="col-md-2 col-xs-3" style="text-align: center;">
        <button class="btn btn-primary btn-block" id="Save" type="button">
            Cerrar Gesti&oacute;n
        </button>
        <a id="errorGestion" style="text-align: center; font-size: 12px; color: gray; cursor: pointer;">
            <u>Cambiar registro</u>
        </a>
    </div>
    <?php }else{ ?>
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
            <select class="form-control input-sm tipificacion" name="tipificacion" id="'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.'">
                <option value="0">Tipificaci&oacute;n</option>
                <?php
                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM ".$BaseDatos_systema.".LISOPC 
                        JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = '.$valorLista.';";
                $obj = $mysqli->query($Lsql);
                while($obje = $obj->fetch_object()){
                    echo "<option value=\'".$obje->OPCION_ConsInte__b."\' efecividad = \'".$obje->MONOEF_EFECTIVA__B."\' monoef=\'".$obje->MONOEF_ConsInte__b."\' TipNoEF = \'".$obje->MONOEF_TipNo_Efe_b."\' cambio=\'".$obje->LISOPC_CambRepr__b."\' importancia = \'".$obje->MONOEF_Importanc_b."\' contacto=\'".$obje->MONOEF_Contacto__b."\' >".($obje->OPCION_Nombre____b)."</option>";

                }            
                ?>
            </select>
            
            <input type="hidden" name="Efectividad" id="Efectividad" value="0">
            <input type="hidden" name="MonoEf" id="MonoEf" value="0">
            <input type="hidden" name="TipNoEF" id="TipNoEF" value="0">
            <input type="hidden" name="FechaInicio" id="FechaInicio" value="0">
            <input type="hidden" name="FechaFinal" id="FechaFinal" value="0">
            <input type="hidden" name="MonoEfPeso" id="MonoEfPeso" value="0">
            <input type="hidden" name="ContactoMonoEf" id="ContactoMonoEf" value="0">
        </div>
    </div>
    <?php } ?>
</div>
<div class="row" style="background-color: #FAFAFA; <?php if(isset($_GET[\'sentido\']) && $_GET[\'sentido\'] == \'2\'){ echo ""; } ?> ">
    <div class="col-md-4 col-xs-4">
        <div class="form-group">
            <select class="form-control input-sm reintento" name="reintento" id="'.$guion_c.$GUION__ConsInte__PREGUN_Rep_b.'">
                <option value="0">Reintento</option>
                <option value="1">REINTENTO AUTOMATICO</option>
                <option value="2">AGENDADO</option>
                <option value="3">NO REINTENTAR</option>
            </select>     
        </div>
    </div>
    <div class="col-md-4 col-xs-4">
        <div class="form-group">
            <input type="text" name="TxtFechaReintento" id="'.$guion_c.$GUION__ConsInte__PREGUN_Fag_b.'" class="form-control input-sm TxtFechaReintento" placeholder="Fecha Reintento"  >
        </div>
    </div>
    <div class="col-md-4 col-xs-4" style="text-align: left;">
        <div class="form-group">
            <input type="text" name="TxtHoraReintento" id="'.$guion_c.$GUION__ConsInte__PREGUN_Hag_b.'" class="form-control input-sm TxtHoraReintento" placeholder="Hora Reintento">
        </div>
    </div>
</div>
<div class="row" style="background-color: #FAFAFA;">
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
            <textarea class="form-control input-sm textAreaComentarios" name="textAreaComentarios" id="'.$guion_c.$GUION__ConsInte__PREGUN_Com_b.'" placeholder="Observaciones"></textarea>
        </div>
    </div>
</div>
'.$ocultarPorPHPEnd.'
';

                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); 
            }else{
                //echo 'no entro';die();
            }

            fputs($fp , '<!-- SECCION : PAGINAS INCLUIDAS -->');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
            
            //JDBD ponemos el pies, si es formulario SC entonces ponemos la condicion para piesCalidad del modulo CALIDAD.
            if ($datoArray['GUION__Tipo______b'] == 1) {
                fputs($fp , '
<?php 

    include(__DIR__ ."/../pies.php");

?>');
            }else{
                fputs($fp , '<?php include(__DIR__ ."/../pies.php");?>');
            }

            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
            fputs($fp , '<script type="text/javascript" src="formularios/'.$guion.'/'.$guion.'_eventos.js"></script>');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
            fputs($fp , '<?php require_once "'.$guion.'_extender_funcionalidad.php";?>');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
            fputs($fp , '<?php require_once __DIR__."/../enviarSms_Mail.php";?>');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
            fputs($fp , '<?php require_once __DIR__."/../integracionesCrm.php";?>');
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

            //esto es escribir el JavaScript

            if ($idCalidad != "" && $datoArray['GUION__Tipo______b'] == 1) {
                $condicionCalidad .= '
<?php
    //JDBD - validamos que no estemos en la estacion
    if(!isset($_GET["id_gestion_cbx"])){
        //JDBD - validamos que estemos en el modulo calidad
        if(isset($_SESSION["QUALITY"]) && $_SESSION["QUALITY"] ==1){
            //JDBD - validamos que tenga permisos para acceder a calidad.
            if(isset($_SESSION["CARGO"]) && ($_SESSION["CARGO"] == "calidad" || $_SESSION["CARGO"] == "administrador" || $_SESSION["CARGO"] == "super-administrador")){?>';
                $condicionCalidad .= $AjaxEnviarCalificacion;
                $condicionCalidad .= $MostrarCalidad;
                $condicionCalidad .= '
    <?php   }
        }
    }
?>';
            }

            $datoJavascript = '
<script type="text/javascript">
    function bindEvent(element, eventName, eventHandler) {
        if (element.addEventListener) {
            element.addEventListener(eventName, eventHandler, false);
        } else if (element.attachEvent) {
            element.attachEvent(\'on\' + eventName, eventHandler);
        }
    }
    
    //escuchar mensajes de  otro formulario
    bindEvent(window, \'message\', function (e) {
        console.log(e.data);
        '.$campoPadre.'
        '.$CamposComunicacion.'
    });
    
    //enviar mensajes al formulario padre
    var sendMessage = function (msg) {
        window.parent.postMessage(msg, \'*\');
    };    
    var messageButton = document.getElementById(\'Save\');    
    bindEvent(messageButton, \'click\', function (e) {
        var mensaje'.$campoHijo.';
        sendMessage(\'\' + mensaje);
    });

    //JDBD - Funcion para descargar los adjuntos
    function bajarAdjunto(id){

        var strURL_t = $("#"+id).attr("adjunto");

        if (strURL_t != "") {

            location.href=\'<?=$url_crud;?>?adjunto=\'+strURL_t;
            
        }


    }
    
    '.$funcionComunicacion.'

    $(function(){
    // JDBD Envio de calificacion por correo.
    //NBG - Esto es para mostrar la secciÃ³n de calidad solo cuando se ingrese por esta
    //////////////////////////////////////////////////////////////////////////////////
        
    '.$condicionCalidad.'      
    
    //JDBD - Esta seccion es solo para la interaccion con el formulario Padre
    /////////////////////////////////////////////////////////////////////////
    <?php if(isset($_GET["yourfather"]) && isset($_GET["idFather"]) && isset($_GET["pincheCampo"])){ ?>
        <?php 
            if($_GET["yourfather"] != "NULL"){ 
                if($_GET["yourfather"] == "-1") {
                    if(isset($_GET["token"]) && isset($_GET["idFather"])){ ?>
                        $("#'.$guion.'_C<?=$_GET[\'pincheCampo\'];?>").attr("opt","<?=$_GET["idFather"]?>");
                        $("#'.$guion.'_C<?=$_GET[\'pincheCampo\'];?>").val("<?=$_GET["idFather"]?>");
                        setTimeout(function(){
                            $("#'.$guion.'_C<?=$_GET[\'pincheCampo\'];?>").change();       
                        },1000);                        
                    <?php }else{
                    $sqlMiembro=$mysqli->query("SELECT G{$_GET[\'formularioPadre\']}_CodigoMiembro AS miembro FROM DYALOGOCRM_WEB.G{$_GET[\'formularioPadre\']} WHERE G{$_GET[\'formularioPadre\']}_ConsInte__b={$_GET[\'idFather\']}");
                    if($sqlMiembro && $sqlMiembro-> num_rows ==1){
                        $sqlMiembro=$sqlMiembro->fetch_object();
                        $intMiembro=$sqlMiembro->miembro;
                    }
        ?>
                        $("#'.$guion.'_C<?=$_GET[\'pincheCampo\'];?>").attr("opt","<?=$intMiembro?>");
                        $("#'.$guion.'_C<?=$_GET[\'pincheCampo\'];?>").val("<?=$intMiembro?>");
                        setTimeout(function(){
                            $("#'.$guion.'_C<?=$_GET[\'pincheCampo\'];?>").change();       
                        },1000);                        
                <?php } ?>
        <?php }else{ ?>
                $("#'.$guion.'_C<?=$_GET[\'pincheCampo\'];?>").val("<?=$_GET[\'yourfather\'];?>");
        <?php } ?>        
        <?php }else{ ?>
            if(document.getElementById("'.$guion.'_C<?=$_GET[\'pincheCampo\'];?>").type == "select-one"){
                $.ajax({
                    url      : \'<?=$url_crud;?>?Combo_Guion_G<?php echo $_GET[\'formulario\'];?>_C<?php echo $_GET[\'pincheCampo\']; ?>=si\',
                    type     : \'POST\',
                    data     : { q : <?php echo $_GET["idFather"]; ?> },
                    success  : function(data){
                        $("#G<?php echo $_GET["formulario"]; ?>_C<?php echo $_GET["pincheCampo"]; ?>").html(data);
                    }
                });
            }else{
                $("#'.$guion.'_C<?=$_GET[\'pincheCampo\'];?>").val("<?=$_GET[\'idFather\'];?>");
            }
        <?php } ?>
        '.$LlamarComunicacion.'
    <?php } ?>
    /////////////////////////////////////////////////////////////////////////
    <?php if (!isset($_GET["view"])) {?>
        $("#add").click(function(){
            '.$strCampoConValorDefinido_t.'
            '.$strOrigen.'
            
        });
    <?php } ?>;';
    if ($strConfirmamosQueElGuionTieneAdjuntos_t > 0) {
$datoJavascript .= '
    $(".adjuntos").change(function(){
        var imax = $(this).attr("valor");
        var imagen = this.files[0];

        //JDBD - Se captura fecha de cargue adjunto.
        var Hoy = moment().format(\'YYYYMMDDHHmmss\');

        //JDBD - Se captura todo el nombre del archivo neto.
        var arrArchivo_t = imagen.name.split(\'.\');
        //JDBD - Se captura lo que va antes de la extension del archivo.
        var strNombre = arrArchivo_t[0];
        //JDBD - Se captura la extension del archivo.
        var strExtension_t = arrArchivo_t[arrArchivo_t.length - 1];
        //JDBD - Se limpia de caracteres raros.
        var strNombreDep_t = strNombre.replace(/[^0-9A-Za-z\s+]/g, \'\');
        //JDBD - Se reemplaza espacios vacios por guion bajo.
        var strNombreEspac_t = strNombreDep_t.replace(/[\s+]/g, \'_\');

        var strNombreFinal_t = strNombreEspac_t+"-"+Hoy+"."+strExtension_t;

        var strIdDown_t = $(this).attr("id").replace("F","");

        $("#"+strIdDown_t).val("/Dyalogo/tmp/adjuntos/'.$guion.'/"+strNombreFinal_t);

        if(imagen["size"] > 9000000 ) {
            $(".NuevaFoto").val("");
            $(this).val("");
            swal({
                title : "Error al subir el archivo",
                text  : "El archivo no debe pesar mas de 9 MB",
                type  : "error",
                confirmButtonText : "Cerrar"
            });
        }
    });
    ';        
    }

$datoJavascript .= '
    var meses = new Array(12);
    meses[0] = "01";
    meses[1] = "02";
    meses[2] = "03";
    meses[3] = "04";
    meses[4] = "05";
    meses[5] = "06";
    meses[6] = "07";
    meses[7] = "08";
    meses[8] = "09";
    meses[9] = "10";
    meses[10] = "11";
    meses[11] = "12";

    var d = new Date();
    var h = d.getHours();
    var horas = (h < 10) ? \'0\' + h : h;
    var dia = d.getDate();
    var dias = (dia < 10) ? \'0\' + dia : dia;
    var fechaInicial = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
    $("#FechaInicio").val(fechaInicial);
            

    //Esta es por si lo llaman en modo formulario de edicion LigthBox
    <?php if(isset($_GET[\'registroId\'])){ ?>
    $.ajax({
        url      : \'<?=$url_crud;?>\',
        type     : \'POST\',
        data     : { CallDatos : \'SI\', id : <?php echo $_GET[\'registroId\']; ?> },
        dataType : \'json\',
        success  : function(data){
            //recorrer datos y enviarlos al formulario
            $.each(data, function(i, item) {
                    '.$functionRecargarFinal;

            $campos_3 = $mysqli->query($Lsql);
            while ($key = $campos_3->fetch_object()){
                if($key->tipo_Pregunta != '8' && $key->tipo_Pregunta != '9'){
                    //JDBD-2020-05-10 : Preguntamos si son algun tipo de Lista.
                    if($key->tipo_Pregunta == '11' || $key->tipo_Pregunta == '13' || $key->tipo_Pregunta == '6'){
                        $conSiGuidet = "SELECT GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__PREGUN_De1_b = ".$key->id." AND GUIDET_ConsInte__PREGUN_Ma1_b IS NULL";
                        $exisRela = $mysqli->query($conSiGuidet);

                        if ($exisRela->num_rows > 0) {
                            $datoJavascript .='
                            $.ajax({
                                url      : \'<?=$url_crud;?>?Combo_Guion_'.$guion_c.$key->id.'=si\',
                                type     : \'POST\',
                                data     : { q : item.'.$guion_c.$key->id.' },
                                type : \'post\',
                                success  : function(data){
                                    $("#'.$guion_c.$key->id.'").html(data);
                                }
                            });';
                        }else{
                            if ($key->tipo_Pregunta == '6' && !is_null($key->depende) && $key->depende != 0) {
                                $datoJavascript .=' 
                                $("#'.$guion_c.$key->id.'").attr("opt",item.'.$guion_c.$key->id.'); ';
                            }elseif($key->tipo_Pregunta == '11'){
                                $datoJavascript .=' 
                                $("#'.$guion_c.$key->id.'").attr("opt",item.'.$guion_c.$key->id.');
                                $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change");';
                            }else{
                                $datoJavascript .=' 
                                $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change");
                                $("#opt_"+item.'.$guion_c.$key->id.').prop("checked",true).trigger("change"); ';
                            }
                            
                        }
                    }else{
                        if ($key->tipo_Pregunta == '15') {
                            $datoJavascript .= ' 

                            if (item.'.$guion_c.$key->id.'){
                                $("#down'.$guion_c.$key->id.'").attr("adjunto",item.'.$guion_c.$key->id.');
                                var lenURL_t = item.'.$guion_c.$key->id.'.split("/");
                                $("#down'.$guion_c.$key->id.'").val(lenURL_t[lenURL_t.length - 1]);
                            }else{
                                $("#down'.$guion_c.$key->id.'").attr("adjunto","");
                                $("#down'.$guion_c.$key->id.'").val("Sin Adjunto");
                                $("#'.$guion_c.$key->id.'").val("");
                            }';
                        }else if($key->tipo_Pregunta == '10'){
                            $datoJavascript .= '
                            $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change"); ';
                        }else{

                            $datoJavascript .= ' 
                            $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.');';
                            
                        }
                    }//cierro el if de $key->tipo_Pregunta == '11'
                }else{
                    $datoJavascript .= '   
                    if(item.'.$guion_c.$key->id.' == "1"){
                        $("#'.$guion_c.$key->id.'").prop(\'checked\', true);
                    }else{
                        $("#'.$guion_c.$key->id.'").prop(\'checked\', false);
                    } ';
                }//cierro el if de $key->tipo_Pregunta != '8' && $key->tipo_Pregunta != '9'
            }//cierro el while $key = $campos_3->fetch_object()
            $funcionRecargaTabSub = '';
            if (count($iTsub) > 0) {
                foreach ($iTsub as $IT => $ITS) {
                    $funcionRecargaTabSub .= '
                cargarHijos_'.$ITS.'('.$idSub[$IT].');';    
                }
            }

            $datoJavascript .= '
                '.$funcionRecargaTabSub.'
                $("#h3mio").html(item.principal);

            });

            //Deshabilitar los campos 3

            //Habilitar todos los campos para edicion
            $(\'#FormularioDatos :input\').each(function(){
                $(this).attr(\'disabled\', true);
            });              

            //Habilidar los botones de operacion, add, editar, eliminar
            $("#add").attr(\'disabled\', false);
            $("#edit").attr(\'disabled\', false);
            $("#delete").attr(\'disabled\', false);

            //Desahabiliatra los botones de salvar y seleccionar_registro
            $("#cancel").attr(\'disabled\', true);
            $("#Save").attr(\'disabled\', true);
        } 
    });

        $("#hidId").val(<?php echo $_GET[\'registroId\'];?>);
        idTotal = <?php echo $_GET[\'registroId\'];?>;

        $("#TxtFechaReintento").attr(\'disabled\', true);
        $("#TxtHoraReintento").attr(\'disabled\', true); 
        '.$darlePadreAlHijo.'

        vamosRecargaLasGrillasPorfavor(<?php echo $_GET[\'registroId\'];?>)

        <?php } ?>

        <?php if(isset($_GET[\'user\'])){ ?>
            /*'.$darlePadreAlHijo_3.'
            vamosRecargaLasGrillasPorfavor(\'<?php echo $_GET[\'user\'];?>\');
            idTotal = <?php echo $_GET[\'user\'];?>; */
        <?php } ?>

        $("#refrescarGrillas").click(function(){
            '.$limpiadordeGrillas.'
            '.$functionLlamarAloshijosLuegoDeCargar.'
        });

        //Esta es la funcionalidad de los Tabs
        '."\n".' '.$tabsFinalOperacions.'
        //Select2 estos son los guiones
        '."\n".$select2.'
        //datepickers
        '.$fechaValidadaOno.'

        //Timepickers
        '."\n".$horaValidadaOno.'

        //Validaciones numeros Enteros
        '."\n".$numeroFuncion.'

        //Validaciones numeros Decimales
        '."\n".$decimalFuncion.'

        /* Si son d formulas */
        '."\n".$funciones_jsY.'

        //Si tienen dependencias

        '."\n".$funciones_jsx.'
        
        //Funcionalidad del botob guardar
        '."\n".$botonSalvar.'

        //funcionalidad del boton Gestion botonCerrarErronea
        '.$botonCerrarErronea.'




    <?php if(!isset($_GET[\'view\'])) { ?>
    //SECICON : CARGUE INFORMACION EN HOJA DE DATOS
    //Cargar datos de la hoja de datos
    function cargar_hoja_datos(){
        $.jgrid.defaults.width = \'1225\';
        $.jgrid.defaults.height = \'650\';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = \'Bootstrap\';
        var lastsel2;
        $("#tablaDatos").jqGrid({
            url:\'<?=$url_crud;?>?CallDatosJson=si\',
            datatype: \'json\',
            mtype: \'POST\',';

            $campos = $mysqli->query($Lsql);
            $i = 0;
            $titulos='';
            $orden = '';
            while ($key = $campos->fetch_object()){
                if( $key->id != $GUION__ConsInte__PREGUN_Tip_b && 
                            $key->id != $GUION__ConsInte__PREGUN_Rep_b &&
                            $key->id != $GUION__ConsInte__PREGUN_Fag_b && 
                            $key->id != $GUION__ConsInte__PREGUN_Hag_b &&
                            $key->id != $GUION__ConsInte__PREGUN_Com_b){
                    if($key->tipo_Pregunta != '9' && $key->tipo_Pregunta != '12'){
                        if($i==0){
                            $titulos = '\''.($key->titulo_pregunta).'\'';
                            $orden = $guion_c.$GUION__ConsInte__PREGUN_Pri_b;
                        }else{
                            $titulos .= ',\''.($key->titulo_pregunta).'\'';
                        }//cierro el if $i==0
                        $i++;
                    }//cierro el if $key->tipo_Pregunta != '9'
                }//cierro el if $$key->id != $GUION__ConsInte__PREGUN_Tip_b && $key->id != $GUION__ConsInte__PREGUN_Rep_b && $key->id != $GUION__ConsInte__PREGUN_Fag_b && $key->id != $GUION__ConsInte__PREGUN_Hag_b && $key->id != $GUION__ConsInte__PREGUN_Com_b             
            }//cierro el while $key = $campos->fetch_object()

            $datoJavascript .= '
            colNames:[\'id\','.$titulos.'],
            colModel:[
                //Traigo los datos de la base de dtaos y los defino en que columna va cada uno, tambien definimos con es su forma de edicion, sea Tipo listas, tipo Textos, etc.
                {
                    name:\'providerUserId\',
                    index:\'providerUserId\', 
                    width:100,
                    editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                          $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }';
            $campos_2 = $mysqli->query($Lsql);
            
            while ($key = $campos_2->fetch_object()){
                if( $key->id != $GUION__ConsInte__PREGUN_Tip_b && 
                    $key->id != $GUION__ConsInte__PREGUN_Rep_b &&
                    $key->id != $GUION__ConsInte__PREGUN_Fag_b && 
                    $key->id != $GUION__ConsInte__PREGUN_Hag_b &&
                    $key->id != $GUION__ConsInte__PREGUN_Com_b){
                    switch ($key->tipo_Pregunta) {
                        case '15':
                            $datoJavascript .= "\n".'
                    ,
                    { 
                        name:\''.$guion_c.$key->id.'\', 
                        index: \''.$guion_c.$key->id.'\', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }';
                        break;
                        case '1':
                            $datoJavascript .= "\n".'
                    ,
                    { 
                        name:\''.$guion_c.$key->id.'\', 
                        index: \''.$guion_c.$key->id.'\', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }';
                        break;

                        case '2':
                            $datoJavascript .="\n". '
                    ,
                    { 
                        name:\''.$guion_c.$key->id.'\', 
                        index:\''.$guion_c.$key->id.'\', 
                        width:150, 
                        editable: true 
                    }';
                        break;

                        case '3':
                            $datoJavascript .="\n".' 
                    ,
                    {  
                        name:\''.$guion_c.$key->id.'\', 
                        index:\''.$guion_c.$key->id.'\', 
                        width:80 ,
                        editable: true, 
                        searchoptions: {
                            sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                        }, 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                $(el).numeric();
                            }
                        } 
                    }';
                        break;

                        case '4':
                            $datoJavascript .="\n".'
                    ,
                    {  
                        name:\''.$guion_c.$key->id.'\', 
                        index:\''.$guion_c.$key->id.'\', 
                        width:80 ,
                        editable: true, 
                        searchoptions: {
                            sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                        }, 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                $(el).numeric({ decimal : ".",  negative : false, scale: 4 });
                            }
                        } 
                    }';
                        break;

                        case '5':
                            $datoJavascript .="\n".'
                    ,
                    {  
                        name:\''.$guion_c.$key->id.'\', 
                        index:\''.$guion_c.$key->id.'\', 
                        width:120 ,
                        editable: true ,
                        formatter: \'text\', 
                        searchoptions: {
                            sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                        }, 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                $(el).datepicker({
                                    language: "es",
                                    autoclose: true,
                                    todayHighlight: true
                                });
                            },
                            defaultValue: function(){
                                var currentTime = new Date();
                                var month = parseInt(currentTime.getMonth() + 1);
                                month = month <= 9 ? "0"+month : month;
                                var day = currentTime.getDate();
                                day = day <= 9 ? "0"+day : day;
                                var year = currentTime.getFullYear();
                                return year+"-"+month + "-"+day;
                            }
                        }
                    }';
                        break;
            
                        case '10':
                            $datoJavascript .="\n".'
                    ,
                    {  
                        name:\''.$guion_c.$key->id.'\', 
                        index:\''.$guion_c.$key->id.'\', 
                        width:70 ,
                        editable: true ,
                        formatter: \'text\', 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                //Timepicker
                                var options = { 
                                    now: "15:00:00", //hh:mm 24 hour format only, defaults to current time
                                    twentyFour: true, //Display 24 hour format, defaults to false
                                    title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
                                    showSeconds: true, //Whether or not to show seconds,
                                    secondsInterval: 1, //Change interval for seconds, defaults to 1
                                    minutesInterval: 1, //Change interval for minutes, defaults to 1
                                    beforeShow: null, //A function to be called before the Wickedpicker is shown
                                    show: null, //A function to be called when the Wickedpicker is shown
                                    clearable: false, //Make the picker\'s input clearable (has clickable "x")
                                }; 
                                $(el).wickedpicker(options);
                            }
                        }
                    }';
                        break;

                        case '6':
                            $datoJavascript .="\n".'
                    ,
                    { 
                        name:\''.$guion_c.$key->id.'\', 
                        index:\''.$guion_c.$key->id.'\', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key->lista.'&campo='.$guion_c.$key->id.'\'
                        }
                    }';
          
                        break;

                        case '11':

                            $datoJavascript .="\n".'
                    ,
                    { 
                        name:\''.$guion_c.$key->id.'\', 
                        index:\''.$guion_c.$key->id.'\', 
                        width:300 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: \'<?=$url_crud;?>?CallDatosCombo_Guion_'.$guion_c.$key->id.'=si\',
                            dataInit:function(el){
                                $(el).select2();
                                /*$(el).select2({ 
                                    templateResult: function(data) {
                                        var r = data.text.split(\'|\');
                                        var row = \'<div class="row">\';
                                        var totalRows = 12 / r.length;
                                        for(i= 0; i < r.length; i++){
                                            row += \'<div class="col-md-\'+ Math.round(totalRows) +\'">\' + r[i] + \'</div>\';
                                        }
                                        row += \'</div>\';
                                        var $result = $(row);
                                        return $result;
                                    },
                                    templateSelection : function(data){
                                        var r = data.text.split(\'|\');
                                        return r[0];
                                    }
                                });*/
                                $(el).change(function(){
                                    var valores = $(el + " option:selected").attr("llenadores");
                                    var campos =  $(el + " option:selected").attr("dinammicos");
                                    var r = valores.split(\'|\');
                                    if(r.length > 1){

                                        var c = campos.split(\'|\');
                                        for(i = 1; i < r.length; i++){
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }
                                    }
                                });
                            }
                        }
                    }';
                        break;

                        case '8':
                            $datoJavascript .="\n". '
                    ,
                    { 
                        name:\''.$guion_c.$key->id.'\', 
                        index:\''.$guion_c.$key->id.'\', 
                        width:70 ,
                        editable: true, 
                        edittype:"checkbox",
                        editoptions: {
                            value:"1:0"
                        } 
                    }';
                        break;
                        
                        default:
              
                        break;
                    }//cierro el Swich $key->tipo_Pregunta
                }//cierro el If $key->id != $GUION__ConsInte__PREGUN_Tip_b && $key->id != $GUION__ConsInte__PREGUN_Rep_b && $key->id != $GUION__ConsInte__PREGUN_Fag_b &&                   $key->id != $GUION__ConsInte__PREGUN_Hag_b && $key->id != $GUION__ConsInte__PREGUN_Com_b
            }//cierro el While $key = $campos_2->fetch_object()
    
            $datoJavascript .= '
            ],
            pager: "#pager" ,
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastsel2){
                    '.$select2_hojadeDatos.'
                }
                lastsel2=rowid;
            },
            rowNum: 50,
            rowList:[50,100],
            loadonce: false,
            sortable: true,
            sortname: \''.$orden.'\',
            sortorder: \'asc\',
            viewrecords: true,
            caption: \'PRUEBAS\',
            editurl:"<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?=$idUsuario?>",
            autowidth: true
            '.$subgrilla.'
        });

        $(\'#tablaDatos\').navGrid("#pager", { add:false, del: true , edit: false });
        $(\'#tablaDatos\').inlineNav(\'#pager\',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        });
      
        //para cuando se Maximice o minimize la pantalla.
        $(window).bind(\'resize\', function() {
            $("#tablaDatos").setGridWidth($(window).width());
        }).trigger(\'resize\'); 
    }

    //JDBD-2020-05-03 : Nueva funcion de filtro Avanzado y Scroll. 
    function llenarListaNavegacion(strScroll_p,intInicio_p,intFin_p){

        var strHTMLTr_t = "";
        var arrNumerosFiltros_t = new Array();

        $(".rows").each(function(i){
            arrNumerosFiltros_t[i]=$(this).attr("numero");
        });

        if (arrNumerosFiltros_t.length > 0) {

            var objFormFiltros_t = new FormData($("#forBusquedaAvanzada")[0]);
            objFormFiltros_t.append("arrNumerosFiltros_t",arrNumerosFiltros_t);
            objFormFiltros_t.append("CallDatosJson","SI");
            objFormFiltros_t.append("strScroll_t",strScroll_p);
            objFormFiltros_t.append("inicio_t",intInicio_p);
            objFormFiltros_t.append("fin_t",intFin_p);
            objFormFiltros_t.append("idUsuario",<?=$idUsuario?>);
            objFormFiltros_t.append("tareaBackoffice",<?=$tareaBackoffice;?>);
            objFormFiltros_t.append("muestra",<?=$muestra;?>);
            objFormFiltros_t.append("tareaTipoDist",<?=$tipoDistribucion;?>);

            $.ajax({
                url         : \'<?=$url_crud;?>\',
                type        : \'POST\',
                data        : objFormFiltros_t,
                cache       : false,
                contentType : false,
                processData : false,
                dataType    : \'json\',
                success  : function(data){

                    $.each(data, function(i, item){
                        strHTMLTr_t += "<tr class=\'CargarDatos\' id=\'"+data[i].id+"\'>";
                        strHTMLTr_t += "<td>";
                        strHTMLTr_t += "<p style=\'font-size:14px;\'><b>"+data[i].camp1+"</b></p>";
                        strHTMLTr_t += "<p style=\'font-size:12px; margin-top:-10px;\'>"+data[i].camp2+"</p>";
                        strHTMLTr_t += "</td>";
                        strHTMLTr_t += "</tr>";
                    });


                    if (strScroll_p == "no") {
                        $("#tablaScroll").html(strHTMLTr_t);

                        //JDBD - Activamos el click a los nuevos <tr>.
                        busqueda_lista_navegacion();

                        if ( $("#"+idTotal).length > 0) {
                            //JDBD - Damos click al al registro siexiste.
                            $("#"+idTotal).click();
                            $("#"+idTotal).addClass(\'active\'); 
                        }else{
                            //JDBD - Damos click al primer registro de la lista.
                            $(".CargarDatos :first").click();
                        }
                    }else{
                        $("#tablaScroll").append(strHTMLTr_t);
                        busqueda_lista_navegacion();
                    }
                }
            });

        }

    }

    //buscar registro en la Lista de navegacion
    function llenar_lista_navegacion(B,A=null,T=null,F=null,E=null){
        var tr = \'\';
        $.ajax({
            url      : \'<?=$url_crud;?>\',
            type     : \'POST\',
            data     : { CallDatosJson : \'SI\', B : B, A : A, T : T, F : F, E : E, idUsuario : <?=$idUsuario?>, tareaBackoffice: <?php echo $tareaBackoffice; ?>, muestra: <?php echo $muestra; ?>, tareaTipoDist: <?php echo $tipoDistribucion ?>},
            dataType : \'json\',
            success  : function(data){
                //Cargar la lista con los datos obtenidos en la consulta
                $.each(data, function(i, item) {
                    tr += "<tr class=\'CargarDatos\' id=\'"+data[i].id+"\'>";
                    tr += "<td>";
                    tr += "<p style=\'font-size:14px;\'><b>"+data[i].camp1+"</b></p>";
                    tr += "<p style=\'font-size:12px; margin-top:-10px;\'>"+data[i].camp2+"</p>";
                    tr += "</td>";
                    tr += "</tr>";
                });
                $("#tablaScroll").html(tr);
                //aplicar funcionalidad a la Lista de navegacion
                busqueda_lista_navegacion();

                //SI el Id existe, entonces le damos click,  para traer sis datos y le damos la clase activa
                if ( $("#"+idTotal).length > 0) {
                    $("#"+idTotal).click();   
                    $("#"+idTotal).addClass(\'active\'); 
                }else{
                    //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                    $(".CargarDatos :first").click();
                }

            } 
        });
    }

    //poner en el formulario de la derecha los datos del registro seleccionado a la izquierda, funcionalidad de la lista de navegacion
    function busqueda_lista_navegacion(){

        $(".CargarDatos").click(function(){
            //remover todas las clases activas de la lista de navegacion
            $(".CargarDatos").each(function(){
                $(this).removeClass(\'active\');
            });
            
            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass(\'active\');
              
              
            var id = $(this).attr(\'id\');

            $("#IdGestion").val(id);

            '.$darlePadreAlHijo_2.'
            //buscar los datos
            $.ajax({
                url      : \'<?=$url_crud;?>\',
                type     : \'POST\',
                data     : { CallDatos : \'SI\', id : id },
                dataType : \'json\',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $.each(data, function(i, item) {
                        '.$functionRecargarFinal;

            $campos_3 = $mysqli->query($Lsql);
            while ($key = $campos_3->fetch_object()){
                if($key->tipo_Pregunta != '8' && $key->tipo_Pregunta != '9'){
                    //JDBD-2020-05-10 : Preguntamos si el campo es algun tipo de Lista.
                    if($key->tipo_Pregunta == '11' || $key->tipo_Pregunta == '6' || $key->tipo_Pregunta == '13'){
                        $conSiGuidet = "SELECT GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__PREGUN_De1_b = ".$key->id." AND GUIDET_ConsInte__PREGUN_Ma1_b IS NULL";
                        $exisRela = $mysqli->query($conSiGuidet);

                        if ($exisRela->num_rows > 0) {
                            $datoJavascript .="\n".'$.ajax({
                            url      : \'<?=$url_crud;?>?Combo_Guion_'.$guion_c.$key->id.'=si\',
                            type     : \'POST\',
                            data     : { q : item.'.$guion_c.$key->id.' },
                            type : \'post\',
                            success  : function(data){
                                $("#'.$guion_c.$key->id.'").html(data);
                            }});';
                        }else{
                            if($key->tipo_Pregunta == '6' && !is_null($key->depende) && $key->depende != 0){
                                $datoJavascript .="\n".' 
                                $("#'.$guion_c.$key->id.'").attr("opt",item.'.$guion_c.$key->id.'); ';
                            }else if ($key->tipo_Pregunta == '11') {
                                $datoJavascript .="\n".' 
                                $("#'.$guion_c.$key->id.'").attr("opt",item.'.$guion_c.$key->id.');
                                $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change");';
                            }else{  
                                $datoJavascript .="\n".' 
                                $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change");
                                $("#opt_"+item.'.$guion_c.$key->id.').prop("checked",true).trigger("change"); ';
                            }
                            
                        }
                    }else{
                        if ($key->tipo_Pregunta == '15') {
                        
                        $datoJavascript .= "\n".'

                        if (item.'.$guion_c.$key->id.'){
                            $("#down'.$guion_c.$key->id.'").attr("adjunto",item.'.$guion_c.$key->id.');
                            var lenURL_t = item.'.$guion_c.$key->id.'.split("/");
                            $("#down'.$guion_c.$key->id.'").val(lenURL_t[lenURL_t.length - 1]);
                        }else{
                            $("#down'.$guion_c.$key->id.'").attr("adjunto","");
                            $("#down'.$guion_c.$key->id.'").val("Sin Adjunto");
                            $("#'.$guion_c.$key->id.'").val("");
                        }';

                        }else if($key->tipo_Pregunta == '10'){
                            $datoJavascript .= '
                            $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change"); ';
                        }else{

                            $datoJavascript .= "\n".'
                            $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.');';
                            
                        }
                    }//cierro este if $key->tipo_Pregunta == '11'
                }else{
                    $datoJavascript .= "\n".'    
                        if(item.'.$guion_c.$key->id.' == "1"){
                           $("#'.$guion_c.$key->id.'").prop(\'checked\', true);
                        }else{
                            $("#'.$guion_c.$key->id.'").prop(\'checked\', false);
                        } ';
                }//cierro el if $key->tipo_Pregunta != '8' && $key->tipo_Pregunta != '9'
            }//cierro el while $key = $campos_3->fetch_object()


            $funcionRecargaTabSub = '';
            if (count($iTsub) > 0) {
                foreach ($iTsub as $IT => $ITS) {
                    $funcionRecargaTabSub .= '
            cargarHijos_'.$ITS.'('.$idSub[$IT].');';    
                }
            }


            $datoJavascript .= '
                        '.$funcionRecargaTabSub.'       
            $("#h3mio").html(item.principal);
                        
                    });

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $(\'#FormularioDatos :input\').each(function(){
                        $(this).attr(\'disabled\', true);
                    });

                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr(\'disabled\', false);
                    $("#edit").attr(\'disabled\', false);
                    $("#delete").attr(\'disabled\', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr(\'disabled\', true);
                    $("#Save").attr(\'disabled\', true);
                },complete : function(data){
                    '.$crearAjax.'
                } 
            });

            $("#hidId").val(id);
            idTotal = $("#hidId").val();
            '.$camposAenfocar.'
        });
    }

    function seleccionar_registro(){
        //Seleccinar loos registros de la Lista de navegacion, 
        if ( $("#"+idTotal).length > 0) {
            $("#"+idTotal).click();   
            $("#"+idTotal).addClass(\'active\'); 
            idTotal = 0;
        }else{
            $(".CargarDatos :first").click();
        } 
        '.$functionDescargarFinal.'
    }
    
    function CalcularFormula(){
        '.$camposAenfocar.'
    }

    <?php } ?>


    '.$funcionCargarFinal.'

    function vamosRecargaLasGrillasPorfavor(id){
        '.$functionLlamarAloshijosLuegoDeCargar.'
    }
    
    function llamarDesdeBtnTelefono(telefono){
        <?php 
            $campana=0;
            if(isset($_GET["campana_crm"])){
                $campana=$_GET["campana_crm"];
            } 
        ?>
        
        var data={
            accion:"llamadaDesdeG",
            telefono: "A<?=$campana?>"+telefono,
            validarScript: false
        };
        parent.postMessage(data, \'*\');
    }   
</script>
<script type="text/javascript">
    $(document).ready(function() {
        '.$strActionLoad.'
        <?php
            if(isset($campSql)){
                //recorro la campaÃ±a para tener los datos que necesito
                /*$resultcampSql = $mysqli->query($campSql);
                while($key = $resultcampSql->fetch_object()){
                    

                    //consulta de datos del usuario
                    $DatosSql = " SELECT ".$key->CAMINC_NomCamPob_b." as campo FROM ".$BaseDatos.".G".$tabla." WHERE G".$tabla."_ConsInte__b=".$_GET[\'user\'];

                    //echo $DatosSql;
                    //recorro la tabla de donde necesito los datos
                    $resultDatosSql = $mysqli->query($DatosSql);
                    if($resultDatosSql){
                        while($objDatos = $resultDatosSql->fetch_object()){ ?>
                            document.getElementById("<?=$key->CAMINC_NomCamGui_b;?>").value = \'<?=trim($objDatos->campo);?>\';
                    <?php  
                        }   
                    }
                    
                } */  
            }
        ?>
        <?php if(isset($_GET[\'user\'])){ ?>
            '.$functionLlamarAloshijosLuegoDeCargar.'
            idTotal = <?php echo $_GET[\'user\'];?>; 
        <?php } ?>
        
    });
</script>';

            fputs($fp , $datoJavascript); 
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea    
            fclose($fp);

            $nombre_fichero = $carpeta."/".$guion."_eventos.js";
            if (!file_exists($nombre_fichero)) {
                $fjs = fopen($carpeta."/".$guion."_eventos.js", "w");
                $nuewJs = '$(function(){'.$funciones_js.' '."\n".'});';
                $nuewJs .= "\n".'
function before_save(){ return true; }'."\n".'
function after_save(){}'."\n".'
function after_save_error(){}';
                $nuewJs .= "\n".'
function before_edit(){}'."\n".'
function after_edit(){}';
                $nuewJs .= "\n".'
function before_add(){}'."\n".'
function after_add(){}';
                $nuewJs .= "\n".'
function before_delete(){}'."\n".'
function after_delete(){}'."\n".'
function after_delete_error(){}';
                fputs($fjs, $nuewJs);
                fclose($fjs);
            }//cierro el if !file_exists($nombre_fichero)

            $nombre_fichero2 = $carpeta."/".$guion."_extender_funcionalidad.php";
            if (!file_exists($nombre_fichero2)) {
                $fjss = fopen($carpeta."/".$guion."_extender_funcionalidad.php", "w");
                $nuewJss = '<?php';
                $nuewJss .= "\n".'
    include(__DIR__."/../../conexion.php");';
                $nuewJss .= "\n".'
    //Este archivo es para agregar funcionalidades al G, y que al momento de generar de nuevo no se pierdan';
                $nuewJss .= "\n".'
    //Cosas como nuevas consultas, nuevos Inserts, Envio de correos, etc, en fin extender mas los formularios en PHP';
                $nuewJss .= "\n".'
    
                ';
                $nuewJss .= "\n".'?>';
                fputs($fjss, $nuewJss);
                fclose($fjss);
            }//cierro el if de !file_exists($nombre_fichero2)   

            //Este es el crud
            $fcrud = fopen($carpeta."/".$guion."_CRUD.php" , "w");
            //chmod($carpeta."/".$guion."_CRUD.php" , 0777);

            //Esta consulta la hago para los campos del select
            $campos_4 = $mysqli->query($Lsql);
            $camposconsulta12 = '';
            $camposconsulta1 = '
            $Lsql = \'SELECT '.$guion.'_ConsInte__b, '.$guion.'_FechaInsercion , '.$guion.'_Usuario ,  '.$guion.'_CodigoMiembro  , '.$guion.'_PoblacionOrigen , '.$guion.'_EstadoDiligenciamiento ,  '.$guion.'_IdLlamada , '.$guion_c.$GUION__ConsInte__PREGUN_Pri_b.' as principal ';
            $camposconsulta12 = $camposconsulta1;
            $joins = '';
            $alfa = 0;
            $camposGrid = '';
            $horas = 0;
            while($key = $campos_4->fetch_object()){

                if($key->tipo_Pregunta != 9){
                    $camposconsulta1 .= ','.$guion_c.$key->id;
        
                    if($key->tipo_Pregunta == '5'){
                        $camposGrid .= ', explode(\' \', $fila->'.$guion_c.$key->id.')[0] ';
                    }else if($key->tipo_Pregunta == '10'){
                        $camposGrid .= ', $hora_'.$alfabeto[$horas].' ';
                        $horas++;
                    }else if($key->tipo_Pregunta =='11'){
                        $CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$key->id." ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
                        $campoSqlE = $mysqli->query($CampoSql);

                        while ($cam = $campoSqlE->fetch_object()) {
                            //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                            $campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$cam->PREGUI_ConsInte__CAMPO__b;
                            //echo $campoObtenidoSql;
                            $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

                            while ($o = $resultCamposObtenidos->fetch_object()) {
                                $camposGrid .= ', ($fila->'.$o->CAMPO__Nombre____b.') ';
                            }//cierro este While $o = $resultCamposObtenidos->fetch_object()
                        }//While $cam = $campoSqlE->fetch_object()

                    }else{
                        $camposGrid .= ', ($fila->'.$guion_c.$key->id.') ';
                    }

                    if($key->tipo_Pregunta == '6'){
                        $camposconsulta12 .= ', '.$alfabeto[$alfa].'.LISOPC_Nombre____b as '.$guion_c.$key->id;
                        $joins .= ' LEFT JOIN \'.$BaseDatos_systema.\'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                        $alfa++;
                    }else if($key->tipo_Pregunta =='11'){
                        $CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$key->id." ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
                        $campoSqlE = $mysqli->query($CampoSql);

                        while ($cam = $campoSqlE->fetch_object()) {
                            //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                            $campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$cam->PREGUI_ConsInte__CAMPO__b;
                            //echo $campoObtenidoSql;
                            $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

                            while ($o = $resultCamposObtenidos->fetch_object()) {
                                $camposconsulta12 .= ', '.$o->CAMPO__Nombre____b;
                            }//While $o = $resultCamposObtenidos->fetch_object()
                        }//While $cam = $campoSqlE->fetch_object()
            
                        $joins .= ' LEFT JOIN \'.$BaseDatos.\'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                    }else{
                        $camposconsulta12 .= ','.$guion_c.$key->id;
                    }
                }//cierro este if $key->tipo_Pregunta != 9
            }//cierro este while $key = $campos_4->fetch_object()

            $camposconsulta1 .= ' FROM \'.$BaseDatos.\'.'.$guion;
            $camposconsulta12 .= ' FROM \'.$BaseDatos.\'.'.$guion;

            $camposconsulta1 .= ' WHERE '.$guion.'_ConsInte__b =\'.$_POST[\'id\'];';

            $LsqlHora = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Tipo______b = 10   ORDER BY PREGUN_OrdePreg__b";


            $esHora = $mysqli->query($LsqlHora);
            $variablesDeLahora = '';
            $horas = 0;
            while ($key = $esHora->fetch_object()) {
                $variablesDeLahora .= "\n".'
                $hora_'.$alfabeto[$horas].' = \'\';
                //esto es para todo los tipo fecha, para que no muestre la parte de la hora
                if(!is_null($fila->'.$guion_c.$key->id.')){
                    $hora_'.$alfabeto[$horas].' = explode(\' \', $fila->'.$guion_c.$key->id.')[1];
                }';
                $horas++;
            }//cierro este while $key = $esHora->fetch_object()

            $crud = '<?php
    ini_set(\'display_errors\', \'On\');
    ini_set(\'display_errors\', 1);
    include(__DIR__."/../../conexion.php");
    include(__DIR__."/../../funciones.php");
    date_default_timezone_set(\'America/Bogota\');';
    if ($strConfirmamosQueElGuionTieneAdjuntos_t > 0) {
        $crud .= '
    if (isset($_GET["adjunto"])) {
        $archivo=$_GET["adjunto"];
        if (is_file($archivo)) {
            $size = strlen($archivo);

            if ($size>0) {
                $nombre=basename($archivo);
                $masa = filesize($archivo);
                header("Content-Description: File Transfer");
                header("Content-type: application/force-download");
                header("Content-disposition: attachment; filename=".$nombre);
                header("Content-Transfer-Encoding: binary");
                header("Expires: 0");
                header("Cache-Control: must-revalidate");
                header("Pragma: public");
                header("Content-Length: " . $masa);
                ob_clean();
                flush();
                readfile($archivo);
            }  

        }else{ 
            // header("Location:" . getenv("HTTP_REFERER"));
            echo "<h1>EL ARCHIVO NO EXISTE O LO HAN BORRADO...<br> !!VUELVE ATRAS</h1>";
        }   

    }
        ';
    }
$crud .= '
    // JDBD - se envia calificacion de la gestion al agente que la creo.
    '.$AjaxEnviarFin.'
    '.$strAjaxIncrementable_t.$responderAjax.'
    if (!empty($_SERVER[\'HTTP_X_REQUESTED_WITH\']) && strtolower($_SERVER[\'HTTP_X_REQUESTED_WITH\']) == \'xmlhttprequest\') {
      //Datos del formulario
      if(isset($_POST[\'CallDatos\'])){
          '.$camposconsulta1.'
            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){'."\n";
            $campos_5 = $mysqli->query($Lsql);
            while($key = $campos_5->fetch_object()){
                if($key->tipo_Pregunta == '10'){
                    $crud .= '  
                $hora = \'\';
                if(!is_null($key->'.$guion_c.$key->id.')){
                    $hora = explode(\' \', $key->'.$guion_c.$key->id.')[1];
                }'."\n";
                    $crud .= '
                $datos[$i][\''.$guion_c.$key->id.'\'] = $hora;'."\n";
                }else if($key->tipo_Pregunta == '5'){
                    $crud .= '
                $datos[$i][\''.$guion_c.$key->id.'\'] = explode(\' \', $key->'.$guion_c.$key->id.')[0];'."\n";
                }else if($key->tipo_Pregunta != '9'){
                    $crud .= '
                $datos[$i][\''.$guion_c.$key->id.'\'] = $key->'.$guion_c.$key->id.';'."\n";
                }//cierro $key->tipo_Pregunta == '10'
            }//cierro el While $key = $campos_5->fetch_object()

            $crud .= '      
                $datos[$i][\'principal\'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }


        //JDBD-2020-05-03 : Datos de la lista de la izquierda
        if(isset($_POST[\'CallDatosJson\'])){

            $strLimit_t = " LIMIT 0, 50";

            //JDBD-2020-05-03 : Preguntamos si esta funcion es llamada por el boton de (Buscar o lupa) o por el scroll.
            if (isset($_POST["strScroll_t"])) {
                if ($_POST["strScroll_t"] == "si") {
                    $strLimit_t = " LIMIT ".$_POST["inicio_t"].", ".$_POST["fin_t"];
                }
            }

            //JDBD-2020-05-03 : Averiguamos si el usuario en session solo puede ver sus propios registros.
            $strRegPro_t = "SELECT PEOBUS_VeRegPro__b AS reg FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$_POST["idUsuario"]." AND PEOBUS_ConsInte__GUION__b = '.$id_a_generar.'";

            $resRegPro_t = $mysqli->query($strRegPro_t);

            if ($resRegPro_t->num_rows > 0) {

                $objRegPro_t = $resRegPro_t->fetch_object();
                
                if ($objRegPro_t->reg != 0) {
                    $strRegProp_t = " AND '.$guion.'_Usuario = ".$_POST["idUsuario"]." ";
                }else{
                    $strRegProp_t = "";
                }

            }else{
                $strRegProp_t = "";
            }

            //JDBD-2020-05-03 : Consulta estandar de los registros del guion.
            $Lsql = "SELECT '.$guion.'_ConsInte__b as id,  '.$camposTabla.' 
                     FROM ".$BaseDatos.".'.$guion.' '.$joinsTabla.' WHERE TRUE ".$strRegProp_t;

            // Si lo que estamos consultando es de tareas de backoffice cambia la consulta
            if(isset($_POST[\'tareaBackoffice\']) && $_POST[\'tareaBackoffice\'] == 1 && isset($_POST[\'muestra\']) && $_POST[\'muestra\'] != 0){

                $Lsql = "SELECT '.$guion.'_ConsInte__b as id,  '.$camposTabla.' FROM ".$BaseDatos.".'.$guion.' '.$joinsTabla.' JOIN ".$BaseDatos.".'.$guion.'_M".$_POST[\'muestra\']." ON '.$guion.'_ConsInte__b = '.$guion.'_M".$_POST[\'muestra\']."_CoInMiPo__b 
                    WHERE ( ('.$guion.'_M".$_POST[\'muestra\']."_Estado____b = 0 OR '.$guion.'_M".$_POST[\'muestra\']."_Estado____b = 1 OR '.$guion.'_M".$_POST[\'muestra\']."_Estado____b = 3) OR ('.$guion.'_M".$_POST[\'muestra\']."_Estado____b = 2 AND '.$guion.'_M".$_POST[\'muestra\']."_FecHorAge_b <= NOW() ) )";

                if($_POST[\'tareaTipoDist\'] != 1){
                    $Lsql .= " AND '.$guion.'_M".$_POST[\'muestra\']."_ConIntUsu_b = ".$_POST["idUsuario"]." ";
                }
            }

            if (isset($_POST["arrNumerosFiltros_t"])) {

                //JDBD-2020-05-03 : Busqueda Avanzada.

                $arrNumerosFiltros_t = explode(",", $_POST["arrNumerosFiltros_t"]);

                $intNumerosFiltros_t = count($arrNumerosFiltros_t);

                if ($intNumerosFiltros_t > 0) {
                    $Lsql .= " AND (";
                    foreach ($arrNumerosFiltros_t as $key => $filtro) {
                        if (is_numeric($_POST["selCampo_".$filtro])) {
                            $Lsql .= operadorYFiltro("'.$guion.'_C".$_POST["selCampo_".$filtro],$_POST["selOperador_".$filtro],$_POST["tipo_".$filtro],$_POST["valor_".$filtro]);
                        }else{
                            $Lsql .= operadorYFiltro($_POST["selCampo_".$filtro],$_POST["selOperador_".$filtro],$_POST["tipo_".$filtro],$_POST["valor_".$filtro]);
                        }

                        if (array_key_exists(($key+1),$arrNumerosFiltros_t)) {
                            if (isset($_POST["selCondicion_".($arrNumerosFiltros_t[$key+1])])) {
                                $Lsql .= $_POST["selCondicion_".($arrNumerosFiltros_t[$key+1])]." ";
                            }
                        }
                    }
                    $Lsql .= ") ";
                }

            }else{

                //JDBD-2020-05-03 : Busqueda Sencilla por la Lupa.

                $B = $_POST["B"];

                if ($B != "" && $B != NULL) {
                    $Lsql .= " AND ('.$ordenTabla.' LIKE \'%".$B."%\' OR '.$campTabla.' LIKE \'%".$B."%\') ";
                }

            }


            $Lsql .= " ORDER BY '.$guion.'_ConsInte__b DESC".$strLimit_t; 

            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;
            while($key = $result->fetch_object()){
                $datos[$i][\'camp1\'] = strtoupper(($key->camp1));
                $datos[$i][\'camp2\'] = strtoupper(($key->camp2));
                $datos[$i][\'id\'] = $key->id;
                $i++;
            }
            echo json_encode($datos);
        }

        if(isset($_POST[\'getListaHija\'])){
            $Lsql = "SELECT LISOPC_ConsInte__b , LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__LISOPC_Depende_b = ".$_POST[\'idPadre\']." AND LISOPC_ConsInte__OPCION_b = ".$_POST[\'opcionID\'];
            $res = $mysqli->query($Lsql);
            echo "<option value=\'0\'>Seleccione</option>";
            while($key = $res->fetch_object()){
                echo "<option value=\'".$key->LISOPC_ConsInte__b."\'>".$key->LISOPC_Nombre____b."</option>";
            }
        }


        //Esto ya es para cargar los combos en la grilla

        if(isset($_GET[\'CallDatosLisop_\'])){
            $lista = $_GET[\'idLista\'];
            $comboe = $_GET[\'campo\'];
            $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$lista." ORDER BY LISOPC_Nombre____b";
            
            $combo = $mysqli->query($Lsql);
            echo \'<select class="form-control input-sm"  name="\'.$comboe.\'" id="\'.$comboe.\'">\';
            echo \'<option value="0">Seleccione</option>\';
            while($obj = $combo->fetch_object()){
                echo "<option value=\'".$obj->OPCION_ConsInte__b."\'>".$obj->OPCION_Nombre____b."</option>";
            }   
            echo \'</select>\'; 
        } 

        '.$funcionesCampoGuion.'


        // esto carga los datos de la grilla CallDatosJson
        if(isset($_GET[\'CallDatosJson\'])){
            $page = $_POST[\'page\'];  // Almacena el numero de pagina actual
            $limit = $_POST[\'rows\']; // Almacena el numero de filas que se van a mostrar por pagina
            $sidx = $_POST[\'sidx\'];  // Almacena el indice por el cual se harÃ¡ la ordenaciÃ³n de los datos
            $sord = $_POST[\'sord\'];  // Almacena el modo de ordenaciÃ³n
            if(!$sidx) $sidx =1;
            //Se hace una consulta para saber cuantos registros se van a mostrar
            $result = $mysqli->query("SELECT COUNT(*) AS count FROM ".$BaseDatos.".'.$guion.'");
            // Se obtiene el resultado de la consulta
            $fila = $result->fetch_array();
            $count = $fila[\'count\'];
            //En base al numero de registros se obtiene el numero de paginas
            if( $count >0 ) {
                $total_pages = ceil($count/$limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages)
                $page=$total_pages;

            //Almacena numero de registro donde se va a empezar a recuperar los registros para la pagina
            $start = $limit*$page - $limit; 
            //Consulta que devuelve los registros de una sola pagina'."\n".$camposconsulta12.$joins.'\';
            if ($_REQUEST["_search"] == "false") {
                $where = " where 1";
            } else {
                $operations = array(
                    \'eq\' => "= \'%s\'",            // Equal
                    \'ne\' => "<> \'%s\'",           // Not equal
                    \'lt\' => "< \'%s\'",            // Less than
                    \'le\' => "<= \'%s\'",           // Less than or equal
                    \'gt\' => "> \'%s\'",            // Greater than
                    \'ge\' => ">= \'%s\'",           // Greater or equal
                    \'bw\' => "like \'%s%%\'",       // Begins With
                    \'bn\' => "not like \'%s%%\'",   // Does not begin with
                    \'in\' => "in (\'%s\')",         // In
                    \'ni\' => "not in (\'%s\')",     // Not in
                    \'ew\' => "like \'%%%s\'",       // Ends with
                    \'en\' => "not like \'%%%s\'",   // Does not end with
                    \'cn\' => "like \'%%%s%%\'",     // Contains
                    \'nc\' => "not like \'%%%s%%\'", // Does not contain
                    \'nu\' => "is null",           // Is null
                    \'nn\' => "is not null"        // Is not null
                ); 
                $value = $mysqli->real_escape_string($_REQUEST["searchString"]);
                $where = sprintf(" where %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
            }
            $Lsql .= $where.\' ORDER BY \'.$sidx.\' \'.$sord.\' LIMIT \'.$start.\',\'.$limit;
            $result = $mysqli->query($Lsql);
            $respuesta = array();
            $respuesta[\'page\'] = $page;
            $respuesta[\'total\'] = $total_pages;
            $respuesta[\'records\'] = $count;
            $i=0;
            while( $fila = $result->fetch_object() ) {  
                '.$variablesDeLahora.'
                $respuesta[\'rows\'][$i][\'id\']=$fila->'.$guion.'_ConsInte__b;
                $respuesta[\'rows\'][$i][\'cell\']=array($fila->'.$guion.'_ConsInte__b '.$camposGrid.');
                $i++;
            }
            // La respuesta se regresa como json
            echo json_encode($respuesta);
        }

        if(isset($_POST[\'CallEliminate\'])){
            if($_POST[\'oper\'] == \'del\'){
                $Lsql = "DELETE FROM ".$BaseDatos.".'.$guion.' WHERE '.$guion.'_ConsInte__b = ".$_POST[\'id\'];
                if ($mysqli->query($Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }
        }

        if(isset($_POST[\'callDatosNuevamente\'])){

            //JDBD-2020-05-03 : Averiguamos si el usuario en session solo puede ver sus propios registros.
            $strRegPro_t = "SELECT PEOBUS_VeRegPro__b AS reg FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$_POST["idUsuario"]." AND PEOBUS_ConsInte__GUION__b = '.$id_a_generar.'";

            $resRegPro_t = $mysqli->query($strRegPro_t);

            if ($resRegPro_t->num_rows > 0) {
                
                $objRegPro_t = $resRegPro_t->fetch_object();

                if ($objRegPro_t->reg != 0) {
                    $strRegProp_t = \' AND '.$guion.'_Usuario = \'.$_POST["idUsuario"].\' \';
                }else{
                    $strRegProp_t = \'\';
                }
                
            }else{
                $strRegProp_t = \'\';
            }


            $inicio = $_POST[\'inicio\'];
            $fin = $_POST[\'fin\'];

            $B = "";

            if (isset($_POST["B"])) {
                $B = $_POST["B"];
            }

            //JDBD-2020-05-03 : Consulta estandar para los registros del guion.
            $Zsql = \'SELECT  '.$guion.'_ConsInte__b as id,  '.$camposTabla.'  FROM \'.$BaseDatos.\'.'.$guion.' WHERE TRUE\'.$strRegProp_t;
            
            // Si lo que estamos consultando es de tareas de backoffice cambia la consulta
            if(isset($_POST[\'tareaBackoffice\']) && $_POST[\'tareaBackoffice\'] == 1 && isset($_POST[\'muestra\']) && $_POST[\'muestra\'] != 0){

                $Zsql = "SELECT '.$guion.'_ConsInte__b as id,  '.$camposTabla.'  FROM ".$BaseDatos.".'.$guion.' '.$joinsTabla.' JOIN ".$BaseDatos.".'.$guion.'_M".$_POST[\'muestra\']." ON '.$guion.'_ConsInte__b = '.$guion.'_M".$_POST[\'muestra\']."_CoInMiPo__b 
                    WHERE ( ('.$guion.'_M".$_POST[\'muestra\']."_Estado____b = 0 OR '.$guion.'_M".$_POST[\'muestra\']."_Estado____b = 1 OR '.$guion.'_M".$_POST[\'muestra\']."_Estado____b = 3) OR ('.$guion.'_M".$_POST[\'muestra\']."_Estado____b = 2 AND '.$guion.'_M".$_POST[\'muestra\']."_FecHorAge_b <= NOW() ) )";

                if($_POST[\'tareaTipoDist\'] != 1){
                    $Zsql .= " AND '.$guion.'_M".$_POST[\'muestra\']."_ConIntUsu_b = ".$_POST["idUsuario"]." ";
                }
            }

            //JDBD-2020-05-03 : Este es el campo de busqueda sencilla que esta al lado de la lupa.
            if ($B != "") {
                $Zsql .= \' AND ('.$ordenTabla.' LIKE "%\'.$B.\'%" OR '.$campTabla.' LIKE "%\'.$B.\'%") \';
            }

            $Zsql .= \' ORDER BY '.$guion.'_ConsInte__b DESC LIMIT \'.$inicio.\' , \'.$fin;
            
            $result = $mysqli->query($Zsql);
            while($obj = $result->fetch_object()){
                echo "<tr class=\'CargarDatos\' id=\'".$obj->id."\'>
                    <td>
                        <p style=\'font-size:14px;\'><b>".strtoupper(($obj->camp1))."</b></p>
                        <p style=\'font-size:12px; margin-top:-10px;\'>".strtoupper(($obj->camp2))."</p>
                    </td>
                </tr>";
            } 
        }
              
        //Inserciones o actualizaciones
        if(isset($_POST["oper"]) && isset($_GET[\'insertarDatosGrilla\'])){
            $Lsql  = \'\';

            $validar = 0;
            $valor=array("$",","," ","%");
            $LsqlU = "UPDATE ".$BaseDatos.".'.$guion.' SET "; 
            $LsqlI = "INSERT INTO ".$BaseDatos.".'.$guion.'(";
            $LsqlV = " VALUES ("; '."\n";

            $campos_7 = $mysqli->query($Lsql);

            while ($key = $campos_7->fetch_object()) {
                $valorPordefecto = "";
                if( $key->id == $GUION__ConsInte__PREGUN_Tip_b ){
                    $crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            if(isset($_POST["tipificacion"])){    
                if($_POST["tipificacion"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $'.$guion_c.$key->id.' = str_replace(\' \', \'\',$_POST["tipificacion"]);
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                    $LsqlI .= $separador." '.$guion_c.$key->id.'";
                    $LsqlV .= $separador.$'.$guion_c.$key->id.';
                    $validar = 1;

                    
                }
            }'."\n";
                }//cierro el if $key->id == $GUION__ConsInte__PREGUN_Tip_b

                if( $key->id == $GUION__ConsInte__PREGUN_Rep_b ){
                    $crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            if(isset($_POST["reintento"])){    
                if($_POST["reintento"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $'.$guion_c.$key->id.' = str_replace(\' \', \'\',$_POST["reintento"]);
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                    $LsqlI .= $separador." '.$guion_c.$key->id.'";
                    $LsqlV .= $separador.$'.$guion_c.$key->id.';
                    $validar = 1;
                }
            }'."\n";
                }//Cierro el if $key->id == $GUION__ConsInte__PREGUN_Rep_b

                if( $key->id == $GUION__ConsInte__PREGUN_Fag_b ){
                    $crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["TxtFechaReintento"])){    
                if($_POST["TxtFechaReintento"] != \'\'){
                    if(validateDate(str_replace(\' \', \'\',$_POST["TxtFechaReintento"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }
                        $'.$guion_c.$key->id.' = "\'".str_replace(\' \', \'\',$_POST["TxtFechaReintento"])." 00:00:00\'";
                        $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                        $LsqlI .= $separador." '.$guion_c.$key->id.'";
                        $LsqlV .= $separador.$'.$guion_c.$key->id.';
                        $validar = 1;
                    }
                }else{
                    if(!isset($_GET["LlamadoExterno"])){
                        echo "Validar el campo Fecha de agenda";
                        exit();
                    }
                }
            }'."\n";
                }//cierro el if $key->id == $GUION__ConsInte__PREGUN_Fag_b 

                if( $key->id == $GUION__ConsInte__PREGUN_Hag_b ){
                    $crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["TxtHoraReintento"])){    
                if($_POST["TxtHoraReintento"] != \'\'){
                    if(validateDate(str_replace(\' \', \'\',$_POST["TxtFechaReintento"])." ".str_replace(\' \', \'\',$_POST["TxtHoraReintento"]))){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }
                        $'.$guion_c.$key->id.' = "\'".str_replace(\' \', \'\',$_POST["TxtFechaReintento"])." ".str_replace(\' \', \'\',$_POST["TxtHoraReintento"])."\'";
                        $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                        $LsqlI .= $separador." '.$guion_c.$key->id.'";
                        $LsqlV .= $separador.$'.$guion_c.$key->id.';
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            echo "Validar el campo de la hora de agenda";
                            exit();
                        }
                    }
                }
            }'."\n";
                }//cierro el if de $key->id == $GUION__ConsInte__PREGUN_Hag_b

                if( $key->id == $GUION__ConsInte__PREGUN_Com_b ){
                    $crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            if(isset($_POST["textAreaComentarios"])){    
                if($_POST["textAreaComentarios"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $'.$guion_c.$key->id.' = "\'".$_POST["textAreaComentarios"]."\'";
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                    $LsqlI .= $separador." '.$guion_c.$key->id.'";
                    $LsqlV .= $separador.$'.$guion_c.$key->id.';
                    $validar = 1;
                }
            }'."\n";
                }//cierro el if de $key->id == $GUION__ConsInte__PREGUN_Com_b


                if( $key->id != $GUION__ConsInte__PREGUN_Tip_b && 
                    $key->id != $GUION__ConsInte__PREGUN_Rep_b &&
                    $key->id != $GUION__ConsInte__PREGUN_Fag_b && 
                    $key->id != $GUION__ConsInte__PREGUN_Hag_b &&
                    $key->id != $GUION__ConsInte__PREGUN_Com_b){
                    
                    $valorPordefecto = $key->PREGUN_Default___b;                

                        if($key->tipo_Pregunta == 5){ // tipo fecha
                            $crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["'.$guion_c.$key->id.'"])){    
                if($_POST["'.$guion_c.$key->id.'"] != \'\'){
                    if(validateDate(str_replace(\' \', \'\',$_POST["'.$guion_c.$key->id.'"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $tieneHora = explode(\' \' , $_POST["'.$guion_c.$key->id.'"]);
                        if(count($tieneHora) > 1){
                            $'.$guion_c.$key->id.' = "\'".$_POST["'.$guion_c.$key->id.'"]."\'";
                        }else{
                            $'.$guion_c.$key->id.' = "\'".str_replace(\' \', \'\',$_POST["'.$guion_c.$key->id.'"])." 00:00:00\'";
                        }


                        $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                        $LsqlI .= $separador." '.$guion_c.$key->id.'";
                        $LsqlV .= $separador.$'.$guion_c.$key->id.';
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            $data=json_encode(array("estado"=>"Error","mensaje"=>"Validar el campo '.$key->titulo_pregunta.'"));
                            echo $data;
                            exit();
                        }
                    }
                }
            }'."\n";
                        }else if($key->tipo_Pregunta == 10){ // tipo timer
                            $crud .= '  
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["'.$guion_c.$key->id.'"])){   
                if($_POST["'.$guion_c.$key->id.'"] != \'\' && $_POST["'.$guion_c.$key->id.'"] != \'undefined\' && $_POST["'.$guion_c.$key->id.'"] != \'null\'){
                    $fecha = date(\'Y-m-d\');
                    if(validateDate($fecha." ".str_replace(\' \', \'\',$_POST["'.$guion_c.$key->id.'"]))){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $'.$guion_c.$key->id.' = "\'".$fecha." ".str_replace(\' \', \'\',$_POST["'.$guion_c.$key->id.'"])."\'";
                        $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.'."";
                        $LsqlI .= $separador." '.$guion_c.$key->id.'";
                        $LsqlV .= $separador.$'.$guion_c.$key->id.';
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            $data=json_encode(array("estado"=>"Error","mensaje"=>"Validar el campo '.$key->titulo_pregunta.'"));
                            echo $data;
                            exit();
                        }
                    }
                }
            }'."\n";
                        }else if($key->tipo_Pregunta == 3){ // tipo Entero
                            $crud .= '  
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
            if(isset($_POST["'.$guion_c.$key->id.'"])){
                if($_POST["'.$guion_c.$key->id.'"] != \'\'){
                    $_POST["'.$guion_c.$key->id.'"]=str_replace($valor,"",$_POST["'.$guion_c.$key->id.'"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $'.$guion_c.$key->id.' = $_POST["'.$guion_c.$key->id.'"];
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.'."";
                    $LsqlI .= $separador." '.$guion_c.$key->id.'";
                    $LsqlV .= $separador.$'.$guion_c.$key->id.';
                    $validar = 1;
                }
            }'."\n";
                        }else if($key->tipo_Pregunta == 4){ // tipo Decimal
                            $crud .= '  
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
            if(isset($_POST["'.$guion_c.$key->id.'"])){
                if($_POST["'.$guion_c.$key->id.'"] != \'\'){
                    $_POST["'.$guion_c.$key->id.'"]=str_replace($valor,"",$_POST["'.$guion_c.$key->id.'"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $'.$guion_c.$key->id.' = $_POST["'.$guion_c.$key->id.'"];
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.'."";
                    $LsqlI .= $separador." '.$guion_c.$key->id.'";
                    $LsqlV .= $separador.$'.$guion_c.$key->id.';
                    $validar = 1;
                }
            }'."\n";

                        }else if($key->tipo_Pregunta == 8){ // tipo Check
                            $crud .= '  
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            $_POST["'.$guion_c.$key->id.'"] = isset($_POST["'.$guion_c.$key->id.'"]) ? 1 : 0;
            if(isset($_POST["'.$guion_c.$key->id.'"])){
                $'.$guion_c.$key->id.' = $_POST["'.$guion_c.$key->id.'"];
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.'."";
                $LsqlI .= $separador." '.$guion_c.$key->id.'";
                $LsqlV .= $separador.$'.$guion_c.$key->id.';

                $validar = 1;
            }'."\n";

                        }else if($key->tipo_Pregunta == 15){ // tipos norrmales

                            $crud .= '                
            if (isset($_FILES["F'.$guion_c.$key->id.'"]["tmp_name"])) {

                if (!file_exists("/Dyalogo/tmp/adjuntos")){
                    mkdir("/Dyalogo/tmp/adjuntos", 0777);
                }

                if (!file_exists("/Dyalogo/tmp/adjuntos/G'.$id_a_generar.'")){
                    mkdir("/Dyalogo/tmp/adjuntos/G'.$id_a_generar.'", 0777);
                }

                if ($_FILES["F'.$guion_c.$key->id.'"]["size"] != 0) {

                    $'.$guion_c.$key->id.' = $_FILES["F'.$guion_c.$key->id.'"]["tmp_name"];

                    $rutaFinal = $_POST["'.$guion_c.$key->id.'"];

                    if (is_uploaded_file($'.$guion_c.$key->id.')) {
                        move_uploaded_file($'.$guion_c.$key->id.', $rutaFinal);
                    }

                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."'.$guion_c.$key->id.' = \'".$rutaFinal."\'";
                    $LsqlI .= $separador."'.$guion_c.$key->id.'";
                    $LsqlV .= $separador."\'".$rutaFinal."\'";
                    $validar = 1;
                }
            }
            '."\n";
    
    
                        }else{ // tipos norrmales


                            if($key->titulo_pregunta == "ORIGEN_DY_WF"){
                                $crud .= '  

            if(isset($_POST["'.$guion_c.$key->id.'"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."'.$guion.'_OrigenUltimoCargue = \'".$_POST["'.$guion_c.$key->id.'"]."\', '.$guion.'_FechaUltimoCargue = NOW() ";
                $LsqlI .= $separador."'.$guion_c.$key->id.', '.$guion.'_OrigenUltimoCargue ";
                $LsqlV .= $separador."\'".$_POST["'.$guion_c.$key->id.'"]."\', \'".$_POST["'.$guion_c.$key->id.'"]."\'";
                $validar = 1;
            }
                '."\n";
                            }else{
                                $crud .= '  

            if(isset($_POST["'.$guion_c.$key->id.'"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."'.$guion_c.$key->id.' = \'".$_POST["'.$guion_c.$key->id.'"]."\'";
                $LsqlI .= $separador."'.$guion_c.$key->id.'";
                $LsqlV .= $separador."\'".$_POST["'.$guion_c.$key->id.'"]."\'";
                $validar = 1;
            }
                '."\n";
                            }


    
    
    
                        }//Cierro el if de los tipos de preguntas

                    }else{ // cierro el if de $key->PREGUN_ContAcce__b != 2
                        switch ($valorPordefecto) {
                            case '501':
                                $crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }
            $'.$guion_c.$key->id.' = "\'".date(\'Y-m-d H:i:s\')."\'";
            $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
            $LsqlI .= $separador." '.$guion_c.$key->id.'";
            $LsqlV .= $separador.$'.$guion_c.$key->id.';
            $validar = 1;
           '."\n";                  
                            break;

                            case '1001':
                                $crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }
            $'.$guion_c.$key->id.' = "\'".date(\'Y-m-d H:i:s\')."\'";
            $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
            $LsqlI .= $separador." '.$guion_c.$key->id.'";
            $LsqlV .= $separador.$'.$guion_c.$key->id.';
            $validar = 1;
           '."\n";  
                            break;

                            case '102':
                                $crud .= '  
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."'.$guion_c.$key->id.' = \'".getNombreUser($_GET[\'token\'])."\'";
                $LsqlI .= $separador."'.$guion_c.$key->id.'";
                $LsqlV .= $separador."\'".getNombreUser($_GET[\'token\'])."\'";
                $validar = 1;
            
             '."\n";
                            break;
                        
                            case '105':
                                $crud .= '  
               /* $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $cmapa = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
                $resCampa = $mysqli->query($cmapa);
                $dataCampa = $resCampa->fetch_array();
                $LsqlU .= $separador."'.$guion_c.$key->id.' = \'".$dataCampa["CAMPAN_Nombre____b"]."\'";
                $LsqlI .= $separador."'.$guion_c.$key->id.'";
                $LsqlV .= $separador."\'".$dataCampa["CAMPAN_Nombre____b"]."\'";
                $validar = 1;*/
            
             '."\n";
                            break;

                            case '3001':

                                $crud .= '  
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."'.$guion_c.$key->id.' = \''. $key->PREGUN_DefaNume__b.'\'";
                $LsqlI .= $separador."'.$guion_c.$key->id.'";
                $LsqlV .= $separador."\''. $key->PREGUN_DefaNume__b.'\'";
                $validar = 1;
            
             '."\n";

                            break;

                            case '3002':

                            //Es el autonumerico
                            $crud .= '  
                if(isset($_POST["'.$guion_c.$key->id.'"])){    
                    if($_POST["'.$guion_c.$key->id.'"] != \'\'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }
                        $'.$guion_c.$key->id.' = "\'".$_POST["'.$guion_c.$key->id.'"]."\'";
                        $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                        $LsqlI .= $separador." '.$guion_c.$key->id.'";
                        $LsqlV .= $separador.$'.$guion_c.$key->id.';
                        $validar = 1;
                    }
                }'."\n";
                            
                            break;

                            default:

                            break;
                        }//cierro el switch ($valorPordefecto) {

            
                }//cierro el if de  $key->id != $GUION__ConsInte__PREGUN_Tip_b && $key->id != $GUION__ConsInte__PREGUN_Rep_b && $key->id != $GUION__ConsInte__PREGUN_Fag_b &&                   $key->id != $GUION__ConsInte__PREGUN_Hag_b &&                   $key->id != $GUION__ConsInte__PREGUN_Com_b
            }//cierro el while de $key = $campos_7->fetch_object();



            if($datoArray['GUION__Tipo______b'] == 1){
                $LsqlReintento = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_Texto_____b = 'Reintento' AND PREGUN_ConsInte__GUION__b = ".$id_a_generar.";";
                $LsqlReintento = $mysqli->query($LsqlReintento);
                $LsqlReintento = $LsqlReintento->fetch_object();
                $datoReintento = $LsqlReintento->PREGUN_ConsInte__b;

                $crud .= '
                //JDBD - Llenado de Reintento y Clasificacion.
                if(isset($_POST["MonoEf"])){
                    
                    $LmonoEfLSql = "SELECT MONOEF_Contacto__b FROM ".$BaseDatos_systema.".MONOEF WHERE MONOEF_ConsInte__b = ".$_POST[\'MonoEf\'];
                    
                    if ($resMonoEf = $mysqli->query($LmonoEfLSql)) {
                        if ($resMonoEf->num_rows > 0) {

                            $dataMonoEf = $resMonoEf->fetch_object();

                            $conatcto = $dataMonoEf->MONOEF_Contacto__b;

                            $separador = "";
                            if($validar == 1){
                                $separador = ",";
                            }

                            $LsqlU .= $separador."G'.$id_a_generar.'_Clasificacion = ".$conatcto;
                            $LsqlI .= $separador."G'.$id_a_generar.'_Clasificacion";
                            $LsqlV .= $separador.$conatcto;
                            $validar = 1;

                        }
                    }
                }            
                ';
            }

            $crud .= '
            // Agregamos el paso origen cuando creamos un registro
            $pasoOrigenId = 0;

            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $LsqlI .= $separador."'.$guion.'_PoblacionOrigen";
            $LsqlV .= $separador."\'".$pasoOrigenId."\'";
            $validar = 1;
            ';

            $crud .= '
            if(isset($_GET[\'id_gestion_cbxx2\'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."'.$guion.'_IdLlamada = \'".$_GET[\'id_gestion_cbxx2\']."\'";
                $LsqlI .= $separador."'.$guion.'_IdLlamada";
                $LsqlV .= $separador."\'".$_GET[\'id_gestion_cbxx2\']."\'";
                $validar = 1;
            }


            $padre = NULL;
            if(isset($_POST["padre"])){    
                if($_POST["padre"] != \'0\' && $_POST[\'padre\'] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //primero hay que ir y buscar los campos
                    $Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST[\'formpadre\']." AND GUIDET_ConsInte__GUION__Det_b = ".$_POST[\'formhijo\'];

                    $GuidRes = $mysqli->query($Lsql);
                    $campo = null;
                    while($ky = $GuidRes->fetch_object()){
                        $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                    }
                    $valorG = "'.$guion_c.'";
                    $valorH = $valorG.$campo;
                    $LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                    $LsqlI .= $separador." ".$valorH;
                    $LsqlV .= $separador.$_POST[\'padre\'] ;
                    $validar = 1;
                }
            }';

            if($datoArray['GUION__Tipo______b'] == 2){
                $crud .= '
            if(isset($_POST[\'oper\'])){
                if($_POST["oper"] == \'add\' ){
                    $LsqlI .= ", '.$guion.'_Usuario , '.$guion.'_FechaInsercion";
                    $LsqlV .= ", ".$_GET[\'usuario\']." , \'".date(\'Y-m-d H:i:s\')."\'";
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == \'edit\' ){
                    $Lsql = $LsqlU." WHERE '.$guion.'_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == \'del\' ){
                    $Lsql = "DELETE FROM ".$BaseDatos.".'.$guion.' WHERE '.$guion.'_ConsInte__b = ".$_POST[\'id\'];
                    $validar = 1;
                }
            }';
                
                
            }else{
                $crud .= '
            if(isset($_POST[\'oper\'])){
                if($_POST["oper"] == \'add\' ){
                    $LsqlI .= ", '.$guion.'_Usuario , '.$guion.'_FechaInsercion, '.$guion.'_CodigoMiembro";
                    if(!isset($_GET[\'CodigoMiembro\']) && !is_numeric($_GET[\'CodigoMiembro\'])){
                        $_GET[\'CodigoMiembro\']=-1;
                    }
                    $LsqlV .= ", ".$_GET[\'usuario\']." , \'".date(\'Y-m-d H:i:s\')."\', ".$_GET[\'CodigoMiembro\'];
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == \'edit\' ){
                    $Lsql = $LsqlU." WHERE '.$guion.'_ConsInte__b =".$_POST["id"]; 
                    //echo $Lsql;die();
                }else if($_POST["oper"] == \'del\' ){
                    $Lsql = "DELETE FROM ".$BaseDatos.".'.$guion.' WHERE '.$guion.'_ConsInte__b = ".$_POST[\'id\'];
                    $validar = 1;
                }
            }';
            }

            

            $crud .= '
            //si trae algo que insertar inserta

            //echo $Lsql;
            if($validar == 1){';

            if($datoArray['GUION__Tipo______b'] == 2){
                $crud .= '
                if ($mysqli->query($Lsql) === TRUE) {
                    if($_POST["oper"] == \'add\' ){
                        $dato= $mysqli->insert_id;
                        echo json_encode(array("estado"=>"ok","mensaje"=>$mysqli->insert_id));
                        $UpdContext="UPDATE ".$BaseDatos.".'.$guion.' SET '.$guion.'_UltiGest__b =-14, '.$guion.'_GesMasImp_b =-14, '.$guion.'_TipoReintentoUG_b =0, '.$guion.'_TipoReintentoGMI_b =0, '.$guion.'_ClasificacionUG_b =3, '.$guion.'_ClasificacionGMI_b =3, '.$guion.'_EstadoUG_b =-14, '.$guion.'_EstadoGMI_b =-14, '.$guion.'_CantidadIntentos =0, '.$guion.'_CantidadIntentosGMI_b =0 WHERE '.$guion.'_ConsInte__b = ".$dato;
                        $UpdContext=$mysqli->query($UpdContext);
                    }else if($_POST["oper"] == \'edit\'){
                        $dato = $_POST["id"];
                        // Hago que me devuelva el id para mostrar los datos editados
                        echo json_encode(array("estado"=>"ok","mensaje"=>$_POST["id"]));
                        //echo $_POST["id"];
                    }else{
                        
                        echo "1";           
                    }
                    
                    // el operador debe ser edit o add para realizar la ejecucion de la flecha
                    if($_POST["oper"] == \'add\' || $_POST["oper"] == \'edit\'){

                        // Validamos que lo que trae sea backoffice
                        if(isset($_POST[\'estareabackoffice\']) && $_POST[\'estareabackoffice\'] > 0){
                            $tareaBackofficeId = $_POST[\'estareabackoffice\'];
                            // Necesito ese paso en especifico
                            $sqlPaso = "SELECT TAREAS_BACKOFFICE_ConsInte__b AS id, TAREAS_BACKOFFICE_ConsInte__ESTPAS_b AS pasoId FROM ".$BaseDatos_systema.".TAREAS_BACKOFFICE WHERE TAREAS_BACKOFFICE_ConsInte__b = {$tareaBackofficeId} LIMIT 1";
                            $resPaso = $mysqli->query($sqlPaso);
    
                            if($resPaso && $resPaso->num_rows > 0){
                                $dataPaso = $resPaso->fetch_object();
                                // Este es el que dispara el proceso
                                DispararProceso($dataPaso->pasoId, $dato);
                                $agenteJ = (isset($_GET[\'usuario\'])) ? $_GET[\'usuario\'] : null;
                                $dataJourneyBO = ["sentido" => "Entrante", "tipificacion" => -303, "agente" => $agenteJ];
                                insertarJourney($dato, '.$id_a_generar.' ,$dataPaso->pasoId, $dataJourneyBO );

                            }
    
                        }

                        // Validamos si existe en alguna extrategia un paso de cargue manual para disparar la flecha desde dicho paso


                            // Necesito los pasos especificos

                            $sqlPasoSubForm = "SELECT ESTPAS_ConsInte__b as pasoId FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b IN (SELECT ESTRAT_ConsInte__b FROM {$BaseDatos_systema}.ESTRAT WHERE ESTRAT_ConsInte_GUION_Pob = '.$id_a_generar.') AND ESTPAS_Tipo______b = 21 AND ESTPAS_activo = \'-1\'";
                            $resPasoSubForm = $mysqli->query($sqlPasoSubForm);
    
                            if($resPasoSubForm && $resPasoSubForm->num_rows > 0){

                                    // Para el journey solo inserto una vez por cargue manual
                                    $pasoCargueJ = null;

                                while($dataPasoSubForm = $resPasoSubForm->fetch_object()){
                                        if($pasoCargueJ == null) $pasoCargueJ = $dataPasoSubForm->pasoId;
                                    // Este es el que dispara el proceso
                                    DispararProceso($dataPasoSubForm->pasoId, $dato);
                                }

                                    $dataJourney = ["sentido" => "Entrante", "tipificacion" => -302, "clasificacion" => 3, "tipoReintento" => 0];
                                    insertarJourney($dato, '.$id_a_generar.' ,$pasoCargueJ, $dataJourney );
                            }



                    }

                } else {
                    echo json_encode(array("estado"=>"Error","mensaje"=>"Se genero un error al guardar la información"));
                    $queryCondia="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
                    VALUES(\"".$Lsql."\",\"".$mysqli->error."\",\'Insercion Script\')";
                    $mysqli->query($queryCondia);                    
                   // echo "Error Haciendo el proceso los registros : " . $mysqli->error;
                }';
            }else{
                $crud .= '
                if ($mysqli->query($Lsql) === TRUE) {
                    if($_POST["oper"] == \'add\' ){
                        $UltimoID = $mysqli->insert_id;
                        echo json_encode(array("estado"=>"ok","mensaje"=>$mysqli->insert_id));
                        //echo $mysqli->insert_id;
                    }else{
                        if(isset($_POST["id"]) && $_POST["id"] != \'0\' ){
                            $UltimoID = $_POST["id"];
                            echo json_encode(array("estado"=>"ok","mensaje"=>$UltimoID));
                            //echo $UltimoID;
                        }
                    }

                    

                } else {
                    echo json_encode(array("estado"=>"Error","mensaje"=>"Se genero un error al guardar la información"));
                    $queryCondia="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
                    VALUES(\"".$Lsql."\",\"".$mysqli->error."\",\'Insercion Script\')";
                    $mysqli->query($queryCondia);                    
                   // echo "Error Haciendo el proceso los registros : " . $mysqli->error;
                }';
            }          


             $crud .= '
            }        

        }
    }

    if (isset($_GET["ConsultarHuesped"])) {
        $IdGuion = $_POST["IdGuion"];
        $ConsultaID = "SELECT GUION__ConsInte__PROYEC_b FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b= \'". $IdGuion ."\';";
        if($ResultadoID = $mysqli->query($ConsultaID)) {
            $CantidadResultados = $ResultadoID->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoID->fetch_assoc()) {
                    $IdProyecto= $FilaResultado[\'GUION__ConsInte__PROYEC_b\'];
                    $php_response= $IdProyecto;
                    echo($php_response);
                    mysqli_close($mysqli);
                    exit;
                } 
            }else {
                //Sin Resultados
                $php_response = array("msg" => "Nada");
                mysqli_close($mysqli);
                echo json_encode($php_response);
                exit;
            }
        }else {
            mysqli_close($mysqli);
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }
                
    }

  '.$funcionDeCargadoDelaGrillaFinal."\n".'
  '.$funcionDeguardadoDeLagrillaFinal.'
?>
';    
            
            fputs($fcrud , $crud);      
            fclose($fcrud);   
        }else{
            echo "no se puede generar si no me envias nada";
        }
    }