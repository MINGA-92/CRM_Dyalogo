if(isset($_POST['idioma'])){
                switch ($_POST['idioma']) {
                    case 'en':
                        include ('../../../idiomas/text_en.php');
                        break;

                    case 'es':
                        include ('../../../idiomas/text_es.php');
                        break;

                    default:
                        include ('../../../idiomas/text_en.php');
                        break;
                }
            }

            $id = $_POST['id'];  
            $numero = $id;
            $SQL = "SELECT G7_ConsInte__b, G7_C33, G7_C34, G7_C35, G7_C36, G7_C37, G7_C38 FROM ".$BaseDatos_systema.".G7 ";
            $SQL .= " WHERE G7_C60 = ".$numero." AND G7_C38 = 1 "; 
            $SQL .= " ORDER BY G7_C34 ASC";
            $result = $mysqli->query($SQL);
            $i = 0;
            while( $fila = $result->fetch_object() ) {
                $option = '';
                if($fila->G7_C36 == 1){
                    $option .= '<option value="1" selected>'.$str_seccion_a_1_s_.'</option>';
                }else{
                    $option .= '<option value="1">'.$str_seccion_a_1_s_.'</option>';
                }

                if($fila->G7_C36 == 2){
                    $option .= '<option value="2" selected>'.$str_seccion_a_2_s_.'</option>';
                }else{
                    $option .= '<option value="2">'.$str_seccion_a_2_s_.'</option>';
                }

                if($fila->G7_C36 == 3){
                    $option .= '<option value="3" selected>'.$str_seccion_a_4_s_.'</option>';
                }else{
                    $option .= '<option value="3">'.$str_seccion_a_4_s_.'</option>'; 
                }

                if($fila->G7_C36 == 4){
                    $option .= '<option value="4" selected>'.$str_seccion_a_3_s_.'</option>'; 
                }else{
                    $option .= '<option value="4">'.$str_seccion_a_3_s_.'</option>';     
                }

                if($fila->G7_C36 == 5){
                    $option .= '<option value="5" selected>'.$str_seccion_a_5_s_.'</option>'; 
                }else{
                    $option .= '<option value="5">'.$str_seccion_a_5_s_.'</option>';     
                }

                echo '<div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#secciones_'.$fila->G7_ConsInte__b.'">
                                    SECCION '.($i+1).'
                                    
                                </a>
                            </h4>
                            <div class="box-title pull-right">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-1">
                                                <input class="form-control" type="text" name="EditarSeccion_Nombre_'.$i.'" title="'.$str_nombre______S_.'" placeholder="'.$str_nombre______S_.'" style="border:none;" required="true" value="'.$fila->G7_C33.'">
                                                <input type="hidden" name="EditarSeccion'.$i.'">
                                            </div>
                                            <div class="col-md-3 col-xs-1">
                                                <select class="form-control" name="EditarSeccion_tipo_'.$i.'"  style="border:none;" required="true" title="'.$str_apariencia__S_.'" placeholder="'.$str_apariencia__S_.'">
                                                    '.$option.'
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-xs-1">
                                                <input class="form-control" type="number" value="2" name="EditarSeccion_columnas_'.$i.'" title="'.$str_Numcolumns__S_.'" placeholder="'.$str_Numcolumns__S_.'" style="border:none;" required="true" value="'.$fila->G7_C35.'">
                                            </div>
                                            <div class="col-md-1 col-xs-1" >
                                                <button type="button" class="btn btn btn-danger btn-sm NuevoEliminarSeccion2" title="Eliminar Seccion" valueToEliminar="'.$fila->G7_ConsInte__b.'">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div id="secciones_'.$fila->G7_ConsInte__b.'" class="panel-collapse collapse">
                            <div class="box-body">
                                <div class="form-group">
                                    <table style="width: 100%; text-align:center" id="tbl_Campos" class="table  dt-responsive"> 
                                        <thead>
                                            <tr>
                                                <th> Campo </th>
                                                <th> Tipo </th>
                                                <th> Lista </th>
                                                <th style="text-align:center;""> Avanzado </th>
                                                <th> Acciones </th>
                                            </tr>
                                        </thead>
                                        <tbody id="NuevosCampos'.$fila->G7_ConsInte__b.'" class="NuevosCampos">
                                            <tr>
                                                <td>
                                                    <input class="form-control" type="text" name="NuevoCampo['.$i.'][nombre]" id="NuevoNombre'.$i.'" placeholder="Campo" required="true">
                                                    <input type="hidden" name="NuevoCampo['.$i.'][seccion]" value="'.$i.'">
                                                </td>
                                                <td>
                                                    <select class="form-control TipoDato" name="NuevoCampo['.$i.'][tipo]" idFila="'.$i.'">
                                                        <option value="1">Texto</option>
                                                        <option value="2">Memo</option>
                                                        <option value="3">Numerico</option>
                                                        <option value="4">Decimal</option>
                                                        <option value="5">Fecha</option>
                                                        <option value="10">Hora</option>
                                                        <option value="6">Lista</option>
                                                        <option value="11">Guión</option>
                                                        <option value="8">Casilla de verificación</option>
                                                        <option value="9">Libreto / Label</option>  
                                                        <option value="12">Maestro - Detalle</option>   
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control APPListas" disabled name="NuevoCampo['.$i.'][lista]" id="Lista'.$i.'">  
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="hidden" id="NuevoObligatorio'.$i.'" name="NuevoCampo['.$i.'][obligatorio]">
                                                    <input type="hidden" id="NuevoUnico'.$i.'" name="NuevoCampo['.$i.'][unico]">
                                                    <input type="hidden" id="NuevoEncriptado'.$i.'" name="NuevoCampo['.$i.'][encriptado]">
                                                    <input type="hidden" id="NuevoBusqueda'.$i.'" name="NuevoCampo['.$i.'][busqueda]">
                                                    <button type="button" class="btn btn-warning btn-sm pull-center OpcionesAvanzadas" row="'.$i.'" TipoModal="Nuevo" data-toggle="modal" data-target="#SubModal">
                                                        <i class="fa fa-gears"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button title="Eliminar campo" class="btn btn-danger btn-sm btnEliminarCampo" NuevosCampos="'.$i.'">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button class="btn btn-primary pull-right newCampos" seccion="'.$fila->G7_ConsInte__b.'" type="button">
                                        <i class="fa fa-plus"></i>&nbsp; Agregar Campo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>';
                    $i++;
            } 