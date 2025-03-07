
<!-- SECCION ENCABEZADO -->
<?php 
    $tiempoDesdeInicio = date('Y-m-d H:i:s');
    if(isset($_GET['formulario'])){

        echo '<input type="hidden" name="inpIdFormulario" id="inpIdFormulario" value="'.$_GET['formulario'].'">';

        $Lsql = "SELECT G5_C29 FROM ".$BaseDatos_systema.".G5 WHERE G5_ConsInte__b = ".$_GET['formulario'];

        $resX = $mysqli->query($Lsql);
        $isBackOffice = NULL;
        if($resX){
            $dat = $resX->fetch_array();
            if($dat['G5_C29'] == '4'){
                $isBackOffice = 'SI';
            }
        }

        $gByModulo=false;
        $sqlByModulo=$mysqli->query("SELECT GUION_ByModulo_b FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__b={$_GET['formulario']}");
        if($sqlByModulo && $sqlByModulo->num_rows == 1){
            $sqlByModulo=$sqlByModulo->fetch_object();
            if($sqlByModulo->GUION_ByModulo_b == 1){
                $gByModulo=true;
            }
        }
    }else{
        echo '<input type="hidden" name="inpIdFormulario" id="inpIdFormulario" value="0">';
    }
?>

</br>


<div class="modal fade-in" id="modDatosJourney" data-backdrop="static" data-keyboard="false" role="dialog" style="display: none;">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <iframe id="ifrDatosJourney" src="" style="width: 100%; height: 565px;" class="form-control">
                              
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12" id="contenido">
        <?php if(!isset($_GET['view'])){ ?>


        <div class="nav-tabs-custom" id="navegacion">
            <!--  SECCION : NAVEGACION - TABS -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1-1" id="tabFormulario" data-toggle="tab">FORMULARIO</a></li>
                <li style="visibility: hidden;"><a href="#tab_2-2" id="tabHojaDeDatos" data-toggle="tab">HOJA DE DATOS</a></li>
            </ul>
            <!--  FIN NAVEGACION - TABS -->

            <!-- SECCION : CONTENIDOS -->
            <form id="forBusquedaAvanzada" action="#" method="post" role="form" novalidate>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#busquedaAvanzada" aria-expanded="true" class="">Filtros</a>
                        </h3>
                        <input type="hidden" name="inpCantFiltros" id="inpCantFiltros" value="1">
                    </div>
                    <div id="busquedaAvanzada" class="panel-collapse collapse" aria-expanded="true" style="">
                        <div class="box-body">  
                            <div id="divFiltros">
                                <div class="row">
                                    <div class="col-md-5 col-xs-5">
                                        <div class="form-group">
                                            <label>CAMPO</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                        <div class="form-group">
                                            <label>OPERADOR</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                        <div class="form-group">
                                            <label>VALOR</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-1">
                                        <div class="form-group">
                                            <label></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row rows" id="row_1" numero="1">
                                    <div class="col-md-5 col-xs-5">
                                        <div class="form-group">
                                            <select class="form-control input-sm campoFiltro" name="selCampo_1" id="selCampo_1" numero="1">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                        <div class="form-group">
                                            <select class="form-control input-sm" name="selOperador_1" id="selOperador_1">
                                                <option value="0">Seleccione</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                        <div class="form-group" id="divValor_1">
                                            <input type="text" class="form-control input-sm" id="valor_1" name="valor_1" placeholder="VALOR">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-1">
                                        <div class="form-group">
                                            <button class="form-control btn btn-warning btn-sm" type="button" id="resetFiltradorAvanzado"><i class="fa fa-refresh"></i> Limpiar</button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="tipo_1" name="tipo_1" value="0">
                                </div>
                            </div>
                            <button class="btn btn-primary pull-right" title="Buscar" id="BuscarAvanzado" type="button" >
                                <i class="fa">&nbsp;Buscar</i>
                            </button>
                            <button class="btn btn-success pull-right" id="btnNuevoFiltro" type="button">
                                <i class="fa fa-plus">&nbsp;Nuevo Filtro</i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="tab-content" id="contenido_navegacion">
                <!-- SECCION VISTA FORMULARIO-->
                <div class="tab-pane active" id="tab_1-1">
                    <div class="row">
                        <!-- SECCION LISTA NAVEGACIÓN -->
                        <div class="col-md-3" id="div_lista_navegacion">
                            <!-- BUSQUEDA MANUAL DE LAS BACKOFFICE -->
    
                            <?php  
                                if($isBackOffice == 'SI'){
                                    include_once ('G'.$_GET['formulario'].'/G'.$_GET['formulario'].'_Busqueda_Manual.php');
                                }
                            ?>
                            <div class="input-group input-group-sm" style="width: auto;">
                                <input type="text" name="table_search_lista_navegacion" class="form-control input-sm pull-right" placeholder="Busqueda" id="table_search_lista_navegacion">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" id="BtnBusqueda_lista_navegacion"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <br/>
                            <!-- FIN BUSQUEDA EN LA LISTA DE NAVEGACION-->
                            <!-- LISTA DE NAVEGACION -->
                            <div class="table-responsive no-padding" id="txtPruebas" style="height:553px; overflow-x:hidden; overflow-y:scroll;">
                                <table class="table table-hover" id="tablaScroll">
                                    <?php

                                        $strIconoBackOffice = '';
                                        
                                        while($obj = $result->fetch_object()){
                                            $color = '';
                                            $strIconoBackOffice = '';
                                            if($isBackOffice == 'SI'){
                                                if($obj->estado == 'En gestión' || $obj->estado == 'En gestión por devolución'){
                                                    $color = 'color:blue;';
                                                    $strIconoBackOffice = 'En gestión';
                                                }else if($obj->estado == 'Cerrada'){
                                                    $color = 'color:green;';
                                                    $strIconoBackOffice = 'Cerrada';
                                                }else if($obj->estado == 'Devuelta'){
                                                    $color = 'color:red;';
                                                    $strIconoBackOffice = 'Devuelta';
                                                }else if($obj->estado == 'Sin gestión'){
                                                    $color = 'color:orange;';
                                                    $strIconoBackOffice = 'Sin gestión';
                                                }
                                            }

                                            echo "<tr class='CargarDatos' id='".$obj->id."'>
                                                    <td>
                                                        <p style='font-size:14px;'><b>".mb_strtoupper($obj->camp1)."</b></p>
                                                        <p style='font-size:12px; margin-top:-10px;'>".mb_strtolower($obj->camp2)." <span style='position: relative;right: 2px;float: right;font-size:10px;".$color."'>".$strIconoBackOffice."</i></p>
                                                    </td>
                                                </tr>";
                                        } 
                                    ?>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-9" id="div_formularios">
            <?php }else{ ?>
                      <div class="col-md-12" id="div_formularios">
            <?php } ?>
                        <!-- SECCION : VISTA FORMULARIO -->
                        <div>
                            <!-- SECCION BOTONES -->
                            <?php 

                            if(!isset($_GET['view']) && $isBackOffice == NULL && !isset($_GET['tareabackoffice'])){ 
                            ?>
                                <?php if( $PEOBUS_Adiciona__b != 0){ ?>
                                <button title="Crear" class="btn btn-default" id="add">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <?php }else{ ?>
                                    <button title="Crear" class="btn btn-default"  id="add2" disabled>
                                        <i class="fa fa-plus"></i> 
                                    </button>
                                <?php  } ?>
                                <?php if( $PEOBUS_Borrar____b != 0){ ?>
                                <button title="Eliminarrrr" class="btn btn-default"  id="delete">
                                    <i class="fa fa-trash"></i> 
                                </button>
                                <?php }else{ ?>
                                    <button title="Eliminar" class="btn btn-default"  id="delete2" disabled>
                                        <i class="fa fa-trash"></i> 
                                    </button>
                                <?php  } ?>
                                <?php if( $PEOBUS_Escritur__b != 0){ ?>
                                <button title="Editar" class="btn btn-default" id="edit" >
                                    <i class="fa fa-edit"></i>
                                </button>
                                <?php }else{ ?>
                                    <button title="Editar" class="btn btn-default"  id="edit2" disabled>
                                        <i class="fa fa-edit"></i> 
                                    </button>
                                <?php  } ?>
                                
                                <button title="Guardar" class="btn btn-default" id="Save" disabled>
                                    <i class="fa fa-save"></i>
                                </button>
                                <button title="Cancelar" class="btn btn-default" id="cancel" disabled>
                                    <i class="fa fa-close"></i>
                                </button>
                                
                                <button title="Ver Journey" class="btn btn-default" id="verDatosJourney">
                                    <i class="fa fa-table"></i>
                                </button>

                                <?php if(isset($_GET['formulario']) && $_GET['formulario'] == '722'){ ?>
                                    <a title="Exportar" class="btn btn-default" id="ExportarPqrs" href="formularios/G722/G722_extender_funcionalidad.php?getPqrs=true">
                                        <i class="fa fa-file-excel-o"></i>
                                    </a>
                                <?php } ?>
                        <?php } else if(isset($_GET['view']) && isset($_GET['registroId'])){ ?>
                                <?php if($gByModulo){?>
                                    <button class="btn btn-default"  id="cancelAgenda">
                                        <i class="fa fa-trash"></i> 
                                    </button>
                                    <button class="btn btn-default" id="Save" disabled style="display:none">
                                        <i class="fa fa-save"></i>
                                    </button>
                                <?php }else{ ?>
                                <?php if( $PEOBUS_Adiciona__b != 0){ ?>
                                <button class="btn btn-default" id="add">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <?php }else{ ?>
                                    <button class="btn btn-default"  id="add2" disabled>
                                        <i class="fa fa-plus"></i> 
                                    </button>
                                <?php  } ?>
                                <?php if( $PEOBUS_Borrar____b != 0){ ?>
                                <button class="btn btn-default"  id="delete">
                                    <i class="fa fa-trash"></i> 
                                </button>
                                <?php }else{ ?>
                                    <button class="btn btn-default"  id="delete2" disabled>
                                        <i class="fa fa-trash"></i> 
                                    </button>
                                <?php  } ?>
                                <?php if( $PEOBUS_Escritur__b != 0){ ?>
                                <button class="btn btn-default" id="edit" >
                                    <i class="fa fa-edit"></i>
                                </button>
                                <?php }else{ ?>
                                    <button class="btn btn-default"  id="edit2" disabled>
                                        <i class="fa fa-edit"></i> 
                                    </button>
                                <?php  } ?>
                                
                                <button class="btn btn-default" id="Save" disabled>
                                    <i class="fa fa-save"></i>
                                </button>
                                <button class="btn btn-default" id="cancel" disabled>
                                    <i class="fa fa-close"></i>
                                </button>
                                <?php } ?>
                        <?php }else if(isset($_GET['view']) && isset($_GET['yourfather']) ) { ?>

                                <button class="btn btn-default" id="Save">
                                    <i class="fa fa-save"></i>
                                </button>
                                <button class="btn btn-default" id="cancel">
                                    <i class="fa fa-close"></i>
                                </button>
                        <?php }else if(isset($_GET['tareabackoffice'])){?>
                            
                                <button title="Crear" class="btn btn-default"  id="add2" disabled>
                                    <i class="fa fa-plus"></i> 
                                </button>                                
                    
                                <button title="Eliminar" class="btn btn-default"  id="delete2" disabled>
                                    <i class="fa fa-trash"></i> 
                                </button>

                                <button title="Editar" class="btn btn-default" id="edit" >
                                    <i class="fa fa-edit"></i>
                                </button>
                                
                                <button title="Guardar" class="btn btn-default" id="Save" disabled>
                                    <i class="fa fa-save"></i>
                                </button>
                                <button title="Cancelar" class="btn btn-default" id="cancel" disabled>
                                    <i class="fa fa-close"></i>
                                </button>
                                <button title="Ver Journey" class="btn btn-default" id="verDatosJourney">
                                    <i class="fa fa-table"></i>
                                </button>
                        <?php }?>
                        
                        </div>
                        <br/>
                        <!-- FIN BOTONES -->
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" action="#" method="post" role="form" novalidate>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="strCanal_t" id="strCanal_t" value="<?php if (isset($_GET['canal'])) { echo $_GET['canal']; }else{ echo "sin canal"; } ?>">
                                        <input type="hidden" name="id_gestion_cbx" id="id_gestion_cbx" value="<?php if(isset($_GET['id_gestion_cbx'])){ echo $_GET['id_gestion_cbx']; }else{ echo "0"; } ?>">
                                        <input type="hidden" name="cbx_sentido" id="cbx_sentido" value="<?php if(isset($_GET['sentido'])){ echo $_GET['sentido']; }else{ echo "0"; } ?>">
                                        <input type="hidden" name="cbx_canal__" id="cbx_canal__" value="<?php if(isset($_GET['id_gestion_cbx'])) { echo explode('_', $_GET['id_gestion_cbx'])[0]; }else{ echo "0"; } ?>">
                                        <input type="hidden" name="datoContacto" id="datoContacto" value="<?php if(isset($_GET['ani'])){ echo $_GET['ani']; }else{ echo "0"; } ?>">
                                        <input type="hidden" name="idLlamada" id="idLlamada" value="<?php if(isset($_GET['id_gestion_cbx'])){ echo explode('_',$_GET['id_gestion_cbx'])[1]; }else{ echo "0"; } ?>">
                                        <input type="hidden" name="codigoMiembro" id="codigoMiembro" value="<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "-1";  } ?>">
                                        <input type="hidden" name="script" id="script" value="<?php if (isset($Guion)) { echo $Guion; }else{ echo "Sin Guion"; } ?>">
                                        <input type="hidden" name="inscript" id="inscript" value="script">
                                        <input type="hidden" name="tiempoInicio" id="tiempoInicio" value="<?php echo $tiempoDesdeInicio; ?>">
                                        <input type="hidden" name="origenGestion" id="origenGestion" value="<?php if(isset($_GET['origen'])){ echo $_GET['origen']; }else{ echo "sin origen"; } ?>">

                                        <?php if(isset($_GET['view'])) { ?>
                                            <div class='row' style="display: flex; justify-content: end; column-gap: 3rem;">
                                                <div class='col-md-1'>
                                                    <span id='verDatosJourney' style='background-color : #009FE3;cursor: pointer;border-radius: 2px;box-shadow: -2pt 2pt 0 rgba(0,0,0,.4);font-size : 17px;/* display: flex; */' class='badge'><i class='fa fa-table' aria-hidden='true'></i> <u>Journey</u></span>
                                                </div>
                                            </div>
                                        <?php } ?>
