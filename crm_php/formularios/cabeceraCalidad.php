<!-- SECCION ENCABEZADO -->
<?php 
    $tiempoDesdeInicio = date('Y-m-d H:i:s');
    if(isset($_GET['formulario'])){
        $Lsql = "SELECT G5_C29 FROM ".$BaseDatos_systema.".G5 WHERE G5_ConsInte__b = ".$_GET['formulario'];

        $resX = $mysqli->query($Lsql);
        $isBackOffice = NULL;
        if($resX){
            $dat = $resX->fetch_array();
            if($dat['G5_C29'] == '4'){
                $isBackOffice = 'SI';
            }
        }
    }
?>

</br>
<div class="row" >
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
                            <!-- JDBD filtro por agente -->
                            <div class="input-group input-group-sm" style="width: 100%;">
                                <select class="form-control input-sm" name="busquedaAgente" id="busquedaAgente">
                                <option value = "0">Agente</option>
                                <?php 

                                    $lisCamp = "SELECT U.USUARI_ConsInte__b AS id, U.USUARI_Nombre____b AS nom FROM ".$BaseDatos_systema.".ASITAR JOIN ".$BaseDatos_systema.".USUARI AS U ON U.USUARI_ConsInte__b = ASITAR.ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b IN (SELECT CAMPAN_ConsInte__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__GUION__Gui_b = ".$_GET["formulario"].");";

                                    $obj = $mysqli->query($lisCamp);

                                    while($obje = $obj->fetch_object()){
                                        echo "<option value = '".$obje->id."'>".$obje->nom."</option>";
                                    }

                                 ?>
                                </select>
                            </div>
                            <!-- JDBD filtro por tipificacion -->
                            <div class="input-group input-group-sm" style="width: 100%;">
                                    <select class="form-control input-sm " name="tipificacion" id="busquedaTipificacion">
                                    <option value="0">Tipificacion</option>
                                    <?php 

                                        $GuionColTip ="SELECT GUION__ConsInte__PREGUN_Tip_b AS colTip FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$_GET["formulario"];

                                        $obj = $mysqli->query($GuionColTip);

                                        $fetColTip = $obj->fetch_array();

                                        $PregColTip = "SELECT PREGUN_ConsInte__OPCION_B AS opcion FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$fetColTip["colTip"];

                                        $obj = $mysqli->query($PregColTip);

                                        $fetOpcions = $obj->fetch_array(); 

                                        $ListOpcions = "SELECT LISOPC_ConsInte__b AS val,LISOPC_Nombre____b AS nom FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$fetOpcions["opcion"];

                                        $obj = $mysqli->query($ListOpcions);

                                        while($obje = $obj->fetch_object()){
                                            echo "<option value = '".$obje->val."'>".$obje->nom."</option>";
                                        }




                                     ?>
                                    </select>
                            </div>
                            <!-- JDBD Estado calidad -->
                            <div class="input-group input-group-sm" style="width: 100%;">
                                <select class="form-control input-sm " name="estadoCalidad" id="estadoCalidad">
                                <option value="0">Estado calidad</option>
                                <option value="-201">Calificado</option>
                                <option value="-202">Calificado con respuesta del agente</option>
                                <option value="-203">Sin revisar</option>
                                </select>
                            </div>
                            <!-- JDBD fecha insercion -->
                            <div class="input-group input-group-sm" style="width: 100%;">
                                <input type="text" class="form-control input-sm" id="busquedaFecInsercion" value="" name="busquedaFecInsercion" placeholder="Fecha creacion">
                            </div>

                            <div class="input-group input-group-sm" style="width: 100%;">
                                <input type="text" name="table_search_lista_navegacion" class="form-control input-sm pull-right" placeholder="Busqueda" id="table_search_lista_navegacion">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" id="BtnBusqueda_lista_navegacion"><i class="fa fa-search"></i></button>
                                </div>
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" id="refrescarFiltros"><i class="fa fa-eraser"></i></button>
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

                            if(!isset($_GET['view']) && $isBackOffice == NULL){ 
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

                                <?php if(isset($_GET['formulario']) && $_GET['formulario'] == '722'){ ?>
                                    <a title="Exportar" class="btn btn-default" id="ExportarPqrs" href="formularios/G722/G722_extender_funcionalidad.php?getPqrs=true">
                                        <i class="fa fa-file-excel-o"></i>
                                    </a>
                                <?php } ?>
                        <?php } else if(isset($_GET['view']) && isset($_GET['registroId'])){ ?>
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
                        <?php }else if(isset($_GET['view']) && isset($_GET['yourfather']) ) { ?>

                                <button class="btn btn-default" id="Save">
                                    <i class="fa fa-save"></i>
                                </button>
                                <button class="btn btn-default" id="cancel">
                                    <i class="fa fa-close"></i>
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
                                        <input type="hidden" name="id_gestion_cbx" id="id_gestion_cbx" value="<?php if(isset($_GET['id_gestion_cbx'])){ echo $_GET['id_gestion_cbx']; }else{ echo "0"; } ?>">
                                        <input type="hidden" name="cbx_sentido" id="cbx_sentido" value="<?php if(isset($_GET['sentido'])){ echo $_GET['sentido']; }else{ echo "0"; } ?>">
                                        <input type="hidden" name="cbx_canal__" id="cbx_canal__" value="<?php if(isset($_GET['id_gestion_cbx'])) { echo explode('_', $_GET['id_gestion_cbx'])[0]; }else{ echo "0"; } ?>">
                                        <input type="hidden" name="datoContacto" id="datoContacto" value="<?php if(isset($_GET['ani'])){ echo $_GET['ani']; }else{ echo "0"; } ?>">
                                        <input type="hidden" name="idLlamada" id="idLlamada" value="0">
                                        <input type="hidden" name="" id="codigoMiembro" value="<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "-1";  } ?>">
                                        <input type="hidden" name="" id="script" value="script">
                                        <input type="hidden" name="" id="tiempoInicio" value="<?php echo $tiempoDesdeInicio; ?>">
                                                 
      

    
