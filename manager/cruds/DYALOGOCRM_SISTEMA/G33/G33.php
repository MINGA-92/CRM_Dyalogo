<?php include_once('Agendador.php') ?>
<style>
    .info{
        opacity: .3;
        cursor: pointer;
        /* border: solid 1px gray;
        border-radius: 45%; */
    }
</style>
<!-- INVOCAR CARGADOR -->
<div class="modal fade-in" id="editarDatos" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Nuevo agendador</h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div id="divIframe" style="overflow-x: scroll;"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<?php if(!isset($_GET['view'])) : ?>
<div class="content-wrapper">
<?php endif; ?>

<?php if(isset($_GET['view'])) : ?>
<div style="background:#ecf0f5">
<?php endif; ?>
    <!-- Content Header (Page header) -->
    <?php if(!isset($_GET['view'])) : ?>
    <section class="content-header">
        <h1>
            Edición de Agendadores 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li>Agendador</li>
        </ol>
    </section>
    <?php endif; ?>

    <!-- Main content -->
    <section class="content">
        <!-- CAJA CONTENEDOR -->
        <div class="box box-primary">
            <div class="box-header"></div>
            
            <!-- CUERPO DEL MODULO-->
            <div class="box-body">
                <div class="row">

                    <!-- SECCION LISTA NAVEGACIÓN -->
                    <?php if(!isset($_GET['view'])) : ?>
                        <div class="col-md-3" id="div_lista_navegacion">
                            <div class="input-group input-group-sm" style="width: auto;">
                                <input type="text" name="table_search_lista_navegacion" class="form-control input-sm pull-right" placeholder="<?php echo $str_busqueda;?>" id="table_search_lista_navegacion">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" id="BtnBusqueda_lista_navegacion"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <br />
                            <!-- FIN BUSQUEDA EN LA LISTA DE NAVEGACION-->

                            <!-- LISTA DE NAVEGACION -->
                            <div class="table-responsive no-padding" id="txtPruebas" style="height:553px; overflow-x:hidden; overflow-y:scroll;">
                                <table class="table table-hover" id="tablaScroll"></table>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- SECCIONES DEL MODULO -->
                    <?php if(!isset($_GET['view'])) : ?>
                        <div class="col-md-9" id="div_formularios">
                    <?php endif; ?>

                    <?php if(isset($_GET['view'])) : ?>
                        <div class="col-md-12" id="div_formularios">
                    <?php endif; ?>

                        <!-- SECCION BOTONES -->
                        <?php if(!isset($_GET['view'])) : ?>
                            <div>
                                <button class="btn btn-default" id="add" onclick="btnAdd()">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button class="btn btn-default" id="delete" onclick="operBtn('delete')">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button class="btn btn-default" id="edit" onclick="operBtn('edit')">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-default" id="Save" onclick="saveAgendador()">
                                    <i class="fa fa-save"></i>
                                </button>
                                <button class="btn btn-default" id="cancel" onclick="btnCancel()">
                                    <i class="fa fa-close"></i>
                                </button>
                                <!-- <a class="btn btn-default" title="Vista Previa" id="vistaPrevia" target="_blank" href="#">
                                    <i class="fa  fa-eye"></i>
                                </a> -->
                            </div>
                            <!-- FIN SECCION BOTONES -->
                            <br />
                        <?php endif; ?>

                        <!-- BOTONES PARA CUANDO SE LLAME DESDE UN IFRAME -->
                        <?php if(isset($_GET['view'])) : ?>
                            <div>
                                <button class="btn btn-default" id="Save" onclick="saveAgendador()">
                                    <i class="fa fa-save"></i>
                                </button>
                            </div>
                            <!-- FIN BOTONES -->
                            <!-- CUERPO DEL FORMULARIO CAMPOS-->
                            <br />
                        <?php endif; ?>

                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data" action="#" method="post">
                                <input type="hidden" name="id" id="hidId" value=''>
                                <input type="hidden" name="oper" id="oper" value=''>
                                <input type="hidden" id="GUION__ConsInte__b" value='0'>
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- CONFIGURACIÓN GENERAL -->
                                        <div class="panel box box-primary box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_24">
                                                        CONFIGURACIÓN GENERAL
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-8 col-8 col-lg-8 col-sm-7 col-xl-10">
                                                        <!-- CAMPO TIPO TEXTO -->
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_Nombre____b"><?php echo $str_nombre______g_;?>*</label>
                                                            <input type="text" class="form-control input-sm required formulario" id="AGENDADOR_Nombre____b" value="" name="AGENDADOR_Nombre____b" placeholder="<?php echo $str_nombre______g_;?>">
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                    </div>
                                                    <div class="col-md-4 col-4 col-lg-4 col-sm-5 col-xl-2">
                                                        <label for="">Id a invocar en el WS</label>
                                                        <div class="input-group">
                                                            <input type="text" id="copiarId" disabled class="form-control input-sm">
                                                            <div class="input-group-btn">
                                                                <button type="button" class="btn btn-default btn-sm formulario" id="copiarAlPortapapeles" disabled>Copiar id</button>
                                                            </div>
                                                        </div><!-- /btn-group -->
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_ConsInte__GUION__Dis_b">Lista de citas disponibles (el sistema crea la estructura automáticamente y el administrador debe cargar los datos)</label>
                                                            <i class="fa fa-question-circle-o info" aria-hidden="true"  data-toggle="tooltip" data-placement="top" title="Esta tabla es para cargar la lista de citas disponibles, las cuales serán ofrecidas y agendadas a quienes las soliciten"></i>
                                                            <div class="input-group">
                                                                <input type="text" id="AGENDADOR_ConsInte__GUION__Dis_b" name="AGENDADOR_ConsInte__GUION__Dis_b" disabled class="form-control input-sm">
                                                                <div class="input-group-btn">
                                                                    <button type="button" class="btn btn-default btn-sm formulario" id="cargue" onclick="cargueDatos()" disabled>Cargar datos</button>
                                                                </div><!-- /btn-group -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="AGENDADOR_FilRec_b" name="AGENDADOR_FilRec_b" class="formulario">
                                                                    Permitir filtrar por recurso
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="AGENDADOR_FilTip_b" name="AGENDADOR_FilTip_b" class="formulario">
                                                                    Permitir filtrar por tipo de recurso
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="AGENDADOR_FilUbi_b" name="AGENDADOR_FilUbi_b" class="formulario">
                                                                    Permitir filtrar por ubicación de recurso
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <p class="text-muted">Si el tema son citas médicas, los recursos son los médicos, si son citas de ventas, los recursos son los vendedores, etc.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- PERSONAS QUE SOLICITAN AGENDAS -->
                                        <div class="panel box box-primary box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_24">
                                                        Personas que solicitan agendas
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="box-body">

                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6">
                                                        <!-- CAMPO TIPO TEXTO -->
                                                        <div class="form-group">
                                                            <div>
                                                                <label for="AGENDADOR_ConsInte__GUION__Pob_b">Base de datos de las personas que solicitan las citas*</label>
                                                                <i class="fa fa-question-circle-o info" aria-hidden="true"  data-toggle="tooltip" data-placement="top" title="Es la base donde estan los pacientes, una vez creado el agendador, esta no se podra cambiar"></i>
                                                            </div>
                                                            <select name="AGENDADOR_ConsInte__GUION__Pob_b" id="AGENDADOR_ConsInte__GUION__Pob_b" class="form-control select2 required formulario" onchange="changeBD()">
                                                                <option value="0">Seleccione</option>
                                                                <?=Agendador::getBd()?>
                                                            </select>
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_ConsInte__PREGUN_IdP_b">Identificación persona agendada*</label>
                                                            <select name="AGENDADOR_ConsInte__PREGUN_IdP_b" id="AGENDADOR_ConsInte__PREGUN_IdP_b" class="form-control select2 required formulario">
                                                                <option value="0">Seleccione</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="validacion" class="formulario" value="1" onclick="checkEstado()">
                                                                    Ofrecerle citas a cualquiera que la solicite
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="validacion" class="formulario" value="2" onclick="checkEstado()">
                                                                    Ofrecer citas solo a las personas que existan en la base de datos, buscándolas por el campo identificación definido en esta sección
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="validacion" class="formulario" value="3" onclick="checkEstado()">
                                                                    Ofrecer citas solo a las personas que existan en la base de datos y además cumplan con las siguientes condiciones
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row estado" hidden>
                                                    <div class="col-md-3">
                                                        <div class="form-goup">
                                                            <label for="ambiente">Campo sobre el que aplica la condición</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="tecnologia">Tipo de validación</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="tecnologia">Dato a validar</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="eliminar">Eliminar</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="filas" class="estado" hidden>
                                                </div>
                                                <hr>
                                                <div class="row estado" hidden>
                                                    <input type="hidden" id="contador" value="0">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <button id="new_row" class="btn btn-block btn-success btn-xs">Agregar fila</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- <div class="row"> ESTO ME LO HICIERON QUITAR :(
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_ConsInte__PREGUN_EstP_b">Campo que almacena el estado</label>
                                                            <select name="AGENDADOR_ConsInte__PREGUN_EstP_b" id="AGENDADOR_ConsInte__PREGUN_EstP_b" class="form-control select2 formulario">
                                                                <option value="0">Seleccione</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_EstadoReq____b">Estado</label>
                                                            <input type="text" name="AGENDADOR_EstadoReq____b" id="AGENDADOR_EstadoReq____b" class="form-control formulario">
                                                        </div>
                                                    </div> 
                                                </div> -->
                                            </div>
                                        </div>

                                        <!-- CAMPOS OPCIONALES PARA ENVIO DE INFORMACIÓN -->
                                        <div class="panel box box-primary box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_25">
                                                        CAMPOS OPCIONALES PARA ENVIO DE INFORMACIÓN
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_ConsInte__PREGUN_NomP_b">Nombre de la persona agendada</label>
                                                            <select name="AGENDADOR_ConsInte__PREGUN_NomP_b" id="AGENDADOR_ConsInte__PREGUN_NomP_b" class="form-control select2 formulario">
                                                                <option value="0">Seleccione</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_ConsInte__PREGUN_CelP_b">Celular persona agendada</label>
                                                            <select name="AGENDADOR_ConsInte__PREGUN_CelP_b" id="AGENDADOR_ConsInte__PREGUN_CelP_b" class="form-control select2 formulario">
                                                                <option value="0">Seleccione</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_ConsInte__PREGUN_MailP_b">Correo de la persona agendada</label>
                                                            <select name="AGENDADOR_ConsInte__PREGUN_MailP_b" id="AGENDADOR_ConsInte__PREGUN_MailP_b" class="form-control select2 formulario">
                                                                <option value="0">Seleccione</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CONFIGURACION DE WEBFORM -->
                                        <div class="panel box box-primary box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">
                                                    <a data-toggle="collapse">
                                                        CONFIGURACION DE WEBFORM
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for=""></label>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="AGENDADOR_Webform_b" id="AGENDADOR_Webform_b" class="formulario" onchange="webForm()">
                                                                    Usar agendador desde webform
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" id='wfId' name='wfId' value="0">
                                                    <input type="hidden" id='idEstpas' name='idEstpas' value="0">
                                                    <input type="hidden" id='webOper' name='webOper' value="add">
                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_CantCitas__b">Título del webform</label>
                                                            <input type="text" class="form-control input-sm" name="wfNombre" id="wfNombre" class="formulario">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_CantCitas__b">Imagen que se quiere mostrar </label>
                                                            <input type="file" name="logo_form" id="logoForm" class="form-control formulario">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_CantCitas__b">Hoja de estilos (.css)</label>
                                                            <input type="file" name="css" id="css" class="form-control formulario" accept=".css">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_CantCitas__b">Origen</label>
                                                            <input type="text" class="form-control input-sm" name="Web_Origen" id="Web_Origen" class="formulario">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8 col-xs-8">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_CantCitas__b" style="margin-top: 30px;">
                                                                Formulario web:
                                                                <a href="" id="urlWeb" target="_blank"></a>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CAMPOS DE AVANZADOS -->
                                        <div class="panel box box-primary box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_24">
                                                        CONFIGURACIÓN AVANZADA
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_CantCitas__b">Cantidad de citas maximas a ofrecer</label>
                                                            <input type="number" class="form-control input-sm" name="AGENDADOR_CantCitas__b" id="AGENDADOR_CantCitas__b" class="AGENDADOR_CantCitas__b formulario">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for=""></label>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="AGENDADOR_OferHoy_b" name="AGENDADOR_OferHoy_b" id="AGENDADOR_OferHoy_b" class="formulario">
                                                                    No ofrecer mas de una cita de cada fecha
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_Estrat_b">Estrategia a usar</label>
                                                            <select name="AGENDADOR_Estrat_b" id="AGENDADOR_Estrat_b" class="form-control select2 formulario" onchange="changeEstrat()">
                                                                <option value="0">Seleccione</option>
                                                                <?=Agendador::getEstrat()?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="AGENDADOR_Estpas_b">paso a usar</label>
                                                            <select name="AGENDADOR_Estpas_b" id="AGENDADOR_Estpas_b" class="form-control select2 formulario">
                                                                <option value="0">Seleccione</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- FIN DE LAS SECCIONES DEL MODULO -->
                </div>
            </div>
            <!-- FIN CUERPO DEL MODULO-->
        </div>
        <!-- FIN CAJA CONTENEDOR -->
    </section>
</div>
<?php include_once('G33_Eventos.php') ?>