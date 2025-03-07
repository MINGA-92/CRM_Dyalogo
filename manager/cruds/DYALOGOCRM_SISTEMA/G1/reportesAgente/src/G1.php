<?php
    Utils::isadmin();
    $base_dir =  __DIR__;
   //SECCION : Definicion urls
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php";
   $url_crudG2 = base_url."cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN
   $Zsql = "SELECT USUARI_ConsInte__b as id, USUARI_Nombre____b as camp1, USUARI_Correo___b as camp2,USUARI_Bloqueado_b as estado FROM ".$BaseDatos_systema.".USUARI WHERE USUARI_ConsInte__PROYEC_b = ".$_SESSION['HUESPED']." AND USUARI_ConsInte__PERUSU_b IS NULL ORDER BY USUARI_Bloqueado_b DESC, USUARI_Nombre____b";
   $result = $mysqli->query($Zsql);

   $horaInicialPorDefecto1='00:00:00';
   $horaFinalPorDefecto1='00:00:00';
   $horaInicialPorDefecto2='00:00:00';
   $horaFinalPorDefecto2='00:00:00';
   $horaInicialPorDefecto3='00:00:00';
   $horaFinalPorDefecto3='00:00:00';
   $horaEntradaPorDefecto="00:00:00";
   $horaSalidaPorDefecto="00:00:00";
   $MallaTurnosHorarioPorDefecto=0;
   $MallaTurnosRequerida=0;

   $Lsql = "SELECT * FROM dyalogo_telefonia.dy_tipos_descanso WHERE id =
   (SELECT pausa_por_defecto_1 FROM  dyalogo_general.huespedes WHERE id = ".$_SESSION['HUESPED'].") LIMIT 1";
               
    if( ( $resultPausa = $mysqli->query($Lsql)) == TRUE ){
        $arrayTipoPausaDefecto1 = [];
        while ($key = $resultPausa->fetch_object()) {
            $horaInicialPorDefecto1=$key->hora_inicial_por_defecto;
            $horaFinalPorDefecto1=$key->hora_final_por_defecto;
            $cadena=$key->tipo;

            if (strpos($cadena, '-') !== false) {
                $tipo = explode("-", $cadena);
                $cadena =$tipo[1];
            }
            
            $arrayTipoPausaDefecto1[0] = $key->id;
            $arrayTipoPausaDefecto1[1] = $cadena;
        }
    }

    $Lsql = "SELECT * FROM dyalogo_telefonia.dy_tipos_descanso WHERE id =
    (SELECT pausa_por_defecto_2 FROM  dyalogo_general.huespedes WHERE id = ".$_SESSION['HUESPED'].") LIMIT 1";
 
                
     if( ( $resultPausa = $mysqli->query($Lsql)) == TRUE ){
         $arrayTipoPausaDefecto2 = [];
         while ($key = $resultPausa->fetch_object()) {
             $horaInicialPorDefecto2=$key->hora_inicial_por_defecto;
             $horaFinalPorDefecto2=$key->hora_final_por_defecto;
             $cadena=$key->tipo;
 
             if (strpos($cadena, '-') !== false) {
                 $tipo = explode("-", $cadena);
                 $cadena =$tipo[1];
             }
             
             $arrayTipoPausaDefecto2[0] = $key->id;
             $arrayTipoPausaDefecto2[1] = $cadena;
         }
     }

     $Lsql = "SELECT * FROM dyalogo_telefonia.dy_tipos_descanso WHERE id =
     (SELECT pausa_por_defecto_3 FROM  dyalogo_general.huespedes WHERE id = ".$_SESSION['HUESPED'].") LIMIT 1";
  
                 
      if( ( $resultPausa = $mysqli->query($Lsql)) == TRUE ){
          $arrayTipoPausaDefecto3 = [];
          while ($key = $resultPausa->fetch_object()) {
              $horaInicialPorDefecto3=$key->hora_inicial_por_defecto;
              $horaFinalPorDefecto3=$key->hora_final_por_defecto;
              $cadena=$key->tipo;
  
              if (strpos($cadena, '-') !== false) {
                  $tipo = explode("-", $cadena);
                  $cadena =$tipo[1];
              }
              
              $arrayTipoPausaDefecto3[0] = $key->id;
              $arrayTipoPausaDefecto3[1] = $cadena;
          }
      }

      //datos por defecto del huesped
      $Lsql = "SELECT * FROM  dyalogo_general.huespedes WHERE id = ".$_SESSION['HUESPED'];
      if( ( $resultHuesped = $mysqli->query($Lsql)) == TRUE ){
          $arrayHuesped= $resultHuesped->fetch_array();
          $MallaTurnosHorarioPorDefecto=$arrayHuesped['malla_turno_horario_por_defecto'];
          $MallaTurnosRequerida=$arrayHuesped['malla_turno_requerida'];
          $horaEntradaPorDefecto=$arrayHuesped['hora_entrada_por_defecto'];
          $horaSalidaPorDefecto=$arrayHuesped['hora_salida_por_defecto'];
          $fotoObligatoria = $arrayHuesped['foto_usuario_obligatoria'];
      }

    // Carga de datos de tabla GUION_
    $sqlFormularios = 'SELECT GUION__ConsInte__b AS id, GUION__Nombre____b AS nombre FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__PROYEC_b = '.$_SESSION['HUESPED'].' ORDER BY nombre ASC;';
    $resultForulario = $mysqli->query($sqlFormularios);
    $opcionesFormularioGUION = '<option value="" disabled selected>Seleccionar</option>';
    while($fila = $resultForulario->fetch_array()) {
      $opcionesFormularioGUION .= '<option value="'.$fila['id'].'">'.addslashes($fila['nombre']).'</option>';
    }
   
?>

<style>
    .lista table{
        margin: 0;
    }
    .titulo-dragdrop{
        background: #f1f1f1;
        color: #858585;
        border: 1px solid #eaeaea;
        font-weight: bold;
        padding: 6px;
        margin-bottom: 0;
    }

    .lista table{
        margin: 0;
    }
    .titulo-dragdrop{
        background: #f1f1f1;
        color: #858585;
        border: 1px solid #eaeaea;
        font-weight: bold;
        padding: 6px;
        margin-bottom: 0;
    }
</style>
<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_usuarios;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li><?php echo $str_usuarios;?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php } ?>
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">

                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <?php if(!isset($_GET['view'])){ ?>
                    <!-- SECCION LISTA NAVEGACIÓN -->
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
                            <table class="table table-hover" id="tablaScroll">

                                <?php
                 
                                    while($obj = $result->fetch_object()){
                                        $inactivo='';
                                        if($obj->estado == -1){
                                            $inactivo='INACTIVO';
                                        }    
                                        echo "<tr class='CargarDatos' id='".url::urlSegura($obj->id)."'>
                                                <td>
                                                    <p style='font-size:14px;'><b>{$obj->camp1}</b> <strong style='float:right; color:red'>{$inactivo}</strong></p>
                                                    <p style='font-size:12px; margin-top:-10px;'>{$obj->camp2}</p>
                                                </td>
                                            </tr>";
                                    } 
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-9" id="div_formularios">
                        <!-- SECCION BOTONES -->
                        <div>
                            <button class="btn btn-default" id="add">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button class="btn btn-default" id="delete">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button class="btn btn-default" id="edit">
                                <i class="fa fa-edit"></i>
                            </button>

                            <button class="btn btn-default" id="Save" disabled>
                                <i class="fa fa-save"></i>
                            </button>
                            <button class="btn btn-default" id="cancel" disabled>
                                <i class="fa fa-close"></i>
                            </button>

                            <button class="btn btn-default" id="regenerar" disabled>
                                <i class="fa fa-key"></i>
                            </button>

                            <button class="btn btn-default" id="cargueMassivo" title="<?php echo $str_CargarMassivo__;?>">
                                <i class="fa fa-arrow-circle-up"></i>
                            </button>
                        </div>
                        <!-- FIN BOTONES -->
                        <?php }else{ ?>
                        <div class="col-md-12" id="div_formularios">
                            <div>
                                <button class="btn btn-default" id="Save">
                                    <i class="fa fa-save"></i>
                                </button>
                                <button class="btn btn-default" id="cancel">
                                    <i class="fa fa-close"></i>
                                </button>
                            </div>
                            <?php } ?>
                            <!-- CUERPO DEL FORMULARIO CAMPOS-->
                            <div>
                                <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data" action="#" method="post">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h3 id='h3mio' style='color : rgb(110, 197, 255);'></h3>
                                            <div class="row">
                                                <!--<div class="col-md-6 col-xs-6">
                                                <div class="form-group">
                                                    <label></label>
                                                    <input type="text" class="form-control" name="CodigoUsuario" id="CodigoUsuario" placeholder="">
                                                </div>
                                            </div>-->
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label><?php echo $str_nombre_usuario;?></label>
                                                        <input type="text" class="form-control  validador" name="NombreUsuario" id="NombreUsuario" disabled placeholder="Nombre del usuario">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label><?php echo $str_correo;?></label>
                                                        <input type="email" class="form-control validador" placeholder="<?php echo $str_correo;?>" disabled name="Correo" id="Correo">
                                                    </div>
                                                </div>
                                                <input type="hidden" name="passwordActual" id="passwordActual">
                                                <!--<div class="col-md-6 col-xs-6">
                                                <div class="form-group">
                                                    <label><?php echo $str_passwo_usuario;?></label>
                                                    <input type="password" class="form-control" name="txtPassword" id="txtPassword" disabled placeholder="Contraseña"> 
                                                     
                                                    <span class="help-block"><?php echo $str_mesaage_password;?></span>
                                                </div>
                                            </div>-->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label><?php echo $str_identificacion;?></label>
                                                        <input type="text" class="form-control validador" name="IdentificacionUsuario" disabled id="IdentificacionUsuario" placeholder="<?php echo $str_identificacion;?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label><?php echo $str_cargo;?></label>
                                                        <select class="form-control validador" placeholder="<?php echo $str_cargo;?>" disabled name="Cargo" id="Cargo">
                                                            <option value="0"><?php echo $str_seleccione; ?></option>
                                                            <option value="agente"><?php echo $str_cargo_1_________?></option>
                                                            <option value="backoffice"><?php echo $str_cargo_2_________?></option>
                                                            <option value="calidad"><?php echo $str_cargo_3_________?></option>
                                                            <!--                                                        <option value="supervision"><?php echo $str_cargo_4_________?></option>-->
                                                            <option value="administrador"><?php echo $str_cargo_5_________?></option>
                                                            <!--                                                        <option value="superadministrador"><?php echo $str_cargo_6_________?></option>-->
                                                            <option value="administradorlimitado"><?php echo $str_cargo_7_________?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label>Estado</label>
                                                        <select name="estadoUsuario" id="estadoUsuario" class="form-control">
                                                            <option value="0">Activo</option>
                                                            <option value="-1">Inactivo</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-xs-12 msgcontrasena">
                                                    <div class="form-group" style="text-align: center;">
                                                        <label>
                                                            <?php echo $str_passGEn_usuarios; ?>
                                                        </label>

                                                    </div>
                                                </div>
                                            </div>

                                            <hr />
                                        </div>

                                        <div class="col-md-3">
                                            <div class="box box-primary">
                                                <div class="box-body box-profile">
                                                    <img id="avatar3" class="profile-user-img img-responsive img-circle" src="<?=base_url?>assets/img/Kakashi.fw.png" alt="User profile picture">

                                                    <h3 class="profile-username text-center" id="NombreUsers"></h3>

                                                    <p class="text-muted text-center" id="CargoUsers"></p>
                                                    <input type="file" name="inpFotoPerfil" id="inpFotoPerfil" class="form-control validador">
                                                    <input type="hidden" name="hidOculto" id="hidOculto" value="0">
                                                    <span class="label bg-gray">
                                                        <?php echo ($fotoObligatoria) ? 'Obligatoria' : 'Opcional' ?>
                                                    </span>
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                        </div>
                                    </div>

                                    

                                        <?php // echo $_SESSION['CARGO'] === "super-administrador" ? "" : "hidden" ?>

                                        <div>
                                            <?php $file = "{$base_dir}/reportesAgente/src/viewReporteAgente.php";
                                            // echo "ruta => {$file}";
                                            include_once($file); ?>
                                        </div>
                                        




                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#datosLaborales" style="margin-right:10px;" class="collapsed" aria-expanded="false">
                                                    Datos laborales
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="nav-tabs-custom collapse" class="panel-collapse collapse" id="datosLaborales">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Tipo Contrato</label>
                                                        <select name="tipoContrato" id="tipoContrato" class="form-control">
                                                            <option value="0">Seleccionar</option>
                                                            <option value="1">A través de un intermediario</option>
                                                            <option value="2"> Contratista</option>
                                                            <option value="3"> Indefinido</option>
                                                            <option value="4"> Obra o labor</option>
                                                            <option value="5"> Temporal</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Fecha Inicio Contrato</label>
                                                        <input type="date" name="fechaIniContr" id="fechaIniContr" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Fecha Fin Contrato</label>
                                                        <input type="date" name="fechaFinContr" id="fechaFinContr" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel box box-primary" id="Horario">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#mallaTurno" style="margin-right:10px;">
                                                    Malla de turno
                                                </a>
                                            </h4>
                                        </div>
                                        <!-- Custom Tabs (Pulled to the right) -->
                                        <div class="nav-tabs-custom" class="panel-collapse" id="mallaTurno">
                                            <div class="row" style="margin:15px 0px;">
                                                <div class="col-md-12 col-xs-12" style="padding:0px;">
                                                    <div class="col-md-3 seleccionarMalla">
                                                        <div class="form-group">
                                                            <select class="form-control input-sm Select2" style="width: 100%;" name="malla_turno" id="malla_turno">
                                                                <option value="0">SELECCIONE</option>
                                                            </select>
                                                            <a id="nuevaMalla" data-toggle="modal" data-target="#modalMallaTurno" class="pull-left AddmodalMalllaTurno">Nueva</a>
                                                            <a id="editarMalla" data-toggle="modal" data-target="#modalMallaTurno" class="pull-right EditmodalMalllaTurno">Editar</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary btn-sm pull-right" onclick="llenarPausaSistema()" data-toggle="modal" data-target="#modalPausas"><i class="fa fa-edit" style="margin-right: 5px;"></i>Editar tipos de pausas</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <ul class="nav nav-tabs pull-right">
                                                <li><a href="#tab_3-3" data-toggle="tab"> PAUSAS SIN HORARIO </a></li>
                                                <li><a href="#tab_2-2" data-toggle="tab">OTRAS PAUSAS CON HORARIO </a></li>
                                                <li class="active"><a href="#tab_1-1" data-toggle="tab">HORARIO BASE</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1-1">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div style="overflow-x: auto">
                                                                <table class="table table-bordered " width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Sesión</th>
                                                                            <th colspan="2">
                                                                                <select name="breakselect[]" id="breakselect1" style="width:175px;">
                                                                                    <option value="<?php echo $arrayTipoPausaDefecto1[0]; ?>"><?php echo $arrayTipoPausaDefecto1[1]; ?></option>
                                                                                </select>
                                                                            </th>
                                                                            <th colspan="2">
                                                                                <select name="breakselect[]" id="breakselect2" style="width:175px;">
                                                                                    <option value="<?php echo $arrayTipoPausaDefecto2[0]; ?>"><?php echo $arrayTipoPausaDefecto2[1]; ?></option>
                                                                                </select>
                                                                            </th>
                                                                            <th colspan="2">
                                                                                <select name="breakselect[]" id="breakselect3" style="width:175px;">
                                                                                    <option value="<?php echo $arrayTipoPausaDefecto3[0]; ?>"><?php echo $arrayTipoPausaDefecto3[1]; ?></option>
                                                                                </select>
                                                                            </th>
                                                                            <th>Sesión</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th style="font-size:12px;">Día</th>
                                                                            <th style="font-size:12px;">Hora Entrada</th>
                                                                            <th style="font-size:12px;">Hora Inicio</th>
                                                                            <th style="font-size:12px;">Hora Fin</th>
                                                                            <th style="font-size:12px;">Hora Inicio</th>
                                                                            <th style="font-size:12px;">Hora Fin</th>
                                                                            <th style="font-size:12px;">Hora Inicio</th>
                                                                            <th style="font-size:12px;">Hora Fin</th>
                                                                            <th style="font-size:12px;">Hora salida</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="filasMalla">
                                                                        <tr>
                                                                            <td>Lunes</td>
                                                                            <td><input type="text" class="requerido  malla entrada has-error" id="HorIniLun" name="HorIniLun"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni1" id="breakHorIniLun1" name="breakHorIniLun[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin1" id="breakHorFinLun1" name="breakHorFinLun[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni2" id="breakHorIniLun2" name="breakHorIniLun[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin2" id="breakHorFinLun2" name="breakHorFinLun[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni3" id="breakHorIniLun3" name="breakHorIniLun[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin3" id="breakHorFinLun3" name="breakHorFinLun[]"></td>
                                                                            <td><input type="text" class="requerido  malla salida" id="HorFinLun" name="HorFinLun"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Martes</td>
                                                                            <td><input type="text" class="requerido  malla entrada" id="HorIniMar" name="HorIniMar"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni1" id="breakHorIniMar1" name="breakHorIniMar[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin1" id="breakHorFinMar1" name="breakHorFinMar[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni2" id="breakHorIniMar2" name="breakHorIniMar[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin2" id="breakHorFinMar2" name="breakHorFinMar[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni3" id="breakHorIniMar3" name="breakHorIniMar[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin3" id="breakHorFinMar3" name="breakHorFinMar[]"></td>
                                                                            <td><input type="text" class="requerido  malla salida" id="HorFinMar" name="HorFinMar"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Miercoles</td>
                                                                            <td><input type="text" class="requerido  malla entrada" id="HorIniMie" name="HorIniMie"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni1" id="breakHorIniMie1" name="breakHorIniMie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin1" id="breakHorFinMie1" name="breakHorFinMie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni2" id="breakHorIniMie2" name="breakHorIniMie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin2" id="breakHorFinMie2" name="breakHorFinMie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni3" id="breakHorIniMie3" name="breakHorIniMie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin3" id="breakHorFinMie3" name="breakHorFinMie[]"></td>
                                                                            <td><input type="text" class="requerido  malla salida" id="HorFinMie" name="HorFinMie"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Jueves</td>
                                                                            <td><input type="text" class="requerido  malla entrada" id="HorIniJue" name="HorIniJue"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni1" id="breakHorIniJue1" name="breakHorIniJue[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin1" id="breakHorFinJue1" name="breakHorFinJue[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni2" id="breakHorIniJue2" name="breakHorIniJue[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin2" id="breakHorFinJue2" name="breakHorFinJue[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni3" id="breakHorIniJue3" name="breakHorIniJue[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin3" id="breakHorFinJue3" name="breakHorFinJue[]"></td>
                                                                            <td><input type="text" class="requerido  malla salida" id="HorFinJue" name="HorFinJue"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Viernes</td>
                                                                            <td><input type="text" class="requerido  malla entrada" id="HorIniVie" name="HorIniVie"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni1" id="breakHorIniVie1" name="breakHorIniVie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin1" id="breakHorFinVie1" name="breakHorFinVie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni2" id="breakHorIniVie2" name="breakHorIniVie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin2" id="breakHorFinVie2" name="breakHorFinVie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaIni3" id="breakHorIniVie3" name="breakHorIniVie[]"></td>
                                                                            <td><input type="text" class="requerido  malla pausaFin3" id="breakHorFinVie3" name="breakHorFinVie[]"></td>
                                                                            <td><input type="text" class="requerido  malla salida" id="HorFinVie" name="HorFinVie"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Sabado</td>
                                                                            <td><input type="text" class="malla entrada" id="HorIniSab" name="HorIniSab"></td>
                                                                            <td><input type="text" class="malla pausaIni1" id="breakHorIniSab1" name="breakHorIniSab[]"></td>
                                                                            <td><input type="text" class="malla pausaFin1" id="breakHorFinSab1" name="breakHorFinSab[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorIniSab2" name="breakHorIniSab[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorFinSab2" name="breakHorFinSab[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorIniSab3" name="breakHorIniSab[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorFinSab3" name="breakHorFinSab[]"></td>
                                                                            <td><input type="text" class="malla salida" id="HorFinSab" name="HorFinSab"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Domingo</td>
                                                                            <td><input type="text" class="malla" id="HorIniDom" name="HorIniDom"></td>
                                                                            <td><input type="text" class="malla" id="breakHorIniDom1" name="breakHorIniDom[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorFinDom1" name="breakHorFinDom[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorIniDom2" name="breakHorIniDom[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorFinDom2" name="breakHorFinDom[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorIniDom3" name="breakHorIniDom[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorFinDom3" name="breakHorFinDom[]"></td>
                                                                            <td><input type="text" class="malla" id="HorFinDom" name="HorFinDom"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Festivo</td>
                                                                            <td><input type="text" class="malla" id="HorIniFes" name="HorIniFes"></td>
                                                                            <td><input type="text" class="malla" id="breakHorIniFes1" name="breakHorIniFes[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorFinFes1" name="breakHorFinFes[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorIniFes2" name="breakHorIniFes[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorFinFes2" name="breakHorFinFes[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorIniFes3" name="breakHorIniFes[]"></td>
                                                                            <td><input type="text" class="malla" id="breakHorFinFes3" name="breakHorFinFes[]"></td>
                                                                            <td><input type="text" class="malla" id="HorFinFes" name="HorFinFes"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_2-2">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div style="overflow-x: auto">
                                                                <table class="table table-bordered " width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th colspan="2" class="centrado">Lunes</th>
                                                                            <th colspan="2" class="centrado">Martes</th>
                                                                            <th colspan="2" class="centrado">Miércoles</th>
                                                                            <th colspan="2" class="centrado">Jueves</th>
                                                                            <th colspan="2" class="centrado">viernes</th>
                                                                            <th colspan="2" class="centrado">Sábado</th>
                                                                            <th colspan="2" class="centrado">Domingo</th>
                                                                            <th colspan="2" class="centrado">Festivo</th>
                                                                            <th></th>
                                                                        </tr>
                                                                        <tr>

                                                                            <th>Pausa</th>
                                                                            <th class="tamano">Hora inicio</th>
                                                                            <th class="tamano">Hora fin</th>
                                                                            <th class="tamano">Hora incio</th>
                                                                            <th class="tamano">Hora fin</th>
                                                                            <th class="tamano">Hora inicio</th>
                                                                            <th class="tamano">Hora fin</th>
                                                                            <th class="tamano">Hora inicio</th>
                                                                            <th class="tamano">Hora fin</th>
                                                                            <th class="tamano">Hora inicio</th>
                                                                            <th class="tamano">Hora fin</th>
                                                                            <th class="tamano">Hora inicio</th>
                                                                            <th class="tamano">Hora fin</th>
                                                                            <th class="tamano">Hora inicio</th>
                                                                            <th class="tamano">Hora fin</th>
                                                                            <th class="tamano">Hora inicio</th>
                                                                            <th class="tamano">Hora fin</th>
                                                                            <th></th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="pausaConHorarioFijo">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-right">
                                                        <button class="btn btn-primary btn-sm" id="agregarHorarioFijo1" style="margin-left: 10px;margin-bottom: 50px;">
                                                            <i class="fa fa-plus" style="margin-right: 5px;"></i>Agregar Pausa Con Horario Fijo
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_3-3">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div style="overflow-x: auto">
                                                                <table class="table table-bordered " width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th colspan="2" class="centrado">Lunes</th>
                                                                            <th colspan="2" class="centrado">Martes</th>
                                                                            <th colspan="2" class="centrado">Miércoles</th>
                                                                            <th colspan="2" class="centrado">Jueves</th>
                                                                            <th colspan="2" class="centrado">viernes</th>
                                                                            <th colspan="2" class="centrado">Sábado</th>
                                                                            <th colspan="2" class="centrado">Domingo</th>
                                                                            <th colspan="2" class="centrado">Festivo</th>
                                                                            <th></th>
                                                                        </tr>
                                                                        <tr>

                                                                            <th>Pausa</th>
                                                                            <th class="tamano">Cantidad Max</th>
                                                                            <th class="tamano">Duración Max</th>
                                                                            <th class="tamano">Cantidad Max</th>
                                                                            <th class="tamano">Duración Max</th>
                                                                            <th class="tamano">Cantidad Max</th>
                                                                            <th class="tamano">Duración Max</th>
                                                                            <th class="tamano">Cantidad Max</th>
                                                                            <th class="tamano">Duración Max</th>
                                                                            <th class="tamano">Cantidad Max</th>
                                                                            <th class="tamano">Duración Max</th>
                                                                            <th class="tamano">Cantidad Max</th>
                                                                            <th class="tamano">Duración Max</th>
                                                                            <th class="tamano">Cantidad Max</th>
                                                                            <th class="tamano">Duración Max</th>
                                                                            <th class="tamano">Cantidad Max</th>
                                                                            <th class="tamano">Duración Max</th>
                                                                            <th></th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="pausaSinHorarioFijo">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-right">
                                                        <button class="btn btn-primary btn-sm" id="agregarHorarioFijo0" style="margin-left: 10px;margin-bottom: 50px;">
                                                            <i class="fa fa-plus" style="margin-right: 5px;"></i>Agregar Pausa Sin Horario Fijo
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.tab-content -->
                                        </div>
                                        <!-- nav-tabs-custom -->

                                    </div>

                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#permisosBOC" style="margin-right:10px;">
                                                    Permisos de backoffice y calidad
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="permisosBOC" class="panel-collapse collapse">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12 table-responsive">
                                                        <table class="table table-condensed" id="table-permisosBOC">
                                                            <thead>
                                                                <tr class="active">
                                                                    <th>Formulario</th>
                                                                    <th>Ver solo registros asignados</th>
                                                                    <th>Editar</th>
                                                                    <th>Agregar</th>
                                                                    <th>Eliminar</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-success btn-sm pull-right" onclick="agregarFilaPermisoBOC()" style="margin-bottom: 15px;"><i class="fa fa-plus"></i> Agregar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Seccion tareas salientes -->
                                    <div class="panel box box-primary" id="panelTareaSaliente">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#tareasTipoSaliente" style="margin-right:10px;">
                                                    Tareas de tipo saliente
                                                </a>
                                            </h4>
                                        </div>

                                        <div id="tareasTipoSaliente" class="panel-collapse collapse">
                                            <div class="box-body">

                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="callout callout-warning">
                                                            <p>Para que los cambios apliquen el agente debe cerrar sesión y volver a iniciar.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Inicio del drag and drop -->
                                                <div class="form-group row" id="dragAndDropTareaSaliente">
                                                    
                                                    <div class="col-md-5 col-xs-12 col-sm-5">
                                                        <div class="input-group">
                                                            <input type="text" name="buscadorDisponibleTareaSaliente" id="buscadorDisponibleTareaSaliente" class="form-control">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-search"></i>
                                                            </span>
                                                        </div>
                                                        <p class="text-center titulo-dragdrop">Disponibles</p>
                                                        <ul id="disponibleTareaSalientes" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                                    margin-bottom: 10px;
                                                                    overflow: auto;   
                                                                    -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                                            
                                                        </ul>
                                                    </div>

                                                    <div class="col-md-2 col-xs-12 col-sm-2 text-center" style="padding-top:100px;">
                                                        <button type="button" id="btnDerechaTareaSaliente" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">></button> <br>
                                                        <!-- <button type="button" id="btnTodoDerechaTareaSaliente" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">>></button> <br> -->
                                                        
                                                        <button type="button" id="btnIzquierdaTareaSaliente" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><</button> <br>
                                                        <!-- <button type="button" id="btnTodoIzquierdaTareaSaliente" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><<</button> -->
                                                    </div>

                                                    <div class="col-md-5 col-xs-12 col-sm-5">
                                                        <div class="input-group">
                                                            <input type="text" name="buscadorSeleccionadoTareaSaliente" id="buscadorSeleccionadoTareaSaliente" class="form-control">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-search"></i>
                                                            </span>
                                                        </div>
                                                        <p class="text-center titulo-dragdrop">Seleccionados</p>
                                                        <ul id="seleccionadoTareaSalientes" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                                    margin-bottom: 10px;
                                                                    overflow: auto;   
                                                                    -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                                        
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Seccion tareas entrantes -->
                                    <div class="panel box box-primary" id="panelTareaEntrante">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#tareasTipoEntrante" style="margin-right:10px;">
                                                    Tareas de tipo entrante
                                                </a>
                                            </h4>
                                        </div>

                                        <div id="tareasTipoEntrante" class="panel-collapse collapse">
                                            <div class="box-body">

                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="callout callout-warning">
                                                            <p>Para que los cambios apliquen el agente debe cerrar sesión y volver a iniciar.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Inicio del drag and drop -->
                                                <div class="form-group row" id="dragAndDropTareaEntrante">
                                                    
                                                    <div class="col-md-5 col-xs-12 col-sm-5">
                                                        <div class="input-group">
                                                            <input type="text" name="buscadorDisponibleTareaEntrante" id="buscadorDisponibleTareaEntrante" class="form-control">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-search"></i>
                                                            </span>
                                                        </div>
                                                        <p class="text-center titulo-dragdrop">Disponibles</p>
                                                        <ul id="disponibleTareaEntrante" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                                    margin-bottom: 10px;
                                                                    overflow: auto;   
                                                                    -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                                            
                                                        </ul>
                                                    </div>

                                                    <div class="col-md-2 col-xs-12 col-sm-2 text-center" style="padding-top:100px;">
                                                        <button type="button" id="btnDerechaTareaEntrante" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">></button> <br>
                                                        <!-- <button type="button" id="btnTodoDerechaTareaEntrante" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">>></button> <br> -->
                                                        
                                                        <button type="button" id="btnIzquierdaTareaEntrante" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><</button> <br>
                                                        <!-- <button type="button" id="btnTodoIzquierdaTareaEntrante" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><<</button> -->
                                                    </div>

                                                    <div class="col-md-5 col-xs-12 col-sm-5">
                                                        <div class="input-group">
                                                            <input type="text" name="buscadorSeleccionadoTareaEntrante" id="buscadorSeleccionadoTareaEntrante" class="form-control">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-search"></i>
                                                            </span>
                                                        </div>
                                                        <p class="text-center titulo-dragdrop">Seleccionados</p>
                                                        <ul id="seleccionadoTareaEntrante" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                                    margin-bottom: 10px;
                                                                    overflow: auto;   
                                                                    -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                                        
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-xs-12 col-sm-4 col-md-push-7 col-sm-push-6 col-xs-push-2">
                                                        <button type="button" class="btn btn-link btn-xs" onclick="abrirModalPrioridadCampana('entrante')">Cambiar prioridad de atención dentro de las campañas entrantes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="id" id="hidId" value='0'>
                                    <input type="hidden" name="oper" id="oper" value='add'>
                                    <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>'>
                                    <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>'>
                                    <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>'>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(!isset($_GET['view'])){ ?>
    </section>
</div>
<?php } ?>

<!-- Modal pausas-->
<div class="modal fade" id="modalPausas" role="dialog">
    <div class="modal-dialog" style="width: 90%;">

        <!-- Modal content-->
        <div class="modal-content">
            <form id="formPausas" enctype="multipart/form-data" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">PAUSAS</h4>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div id="txtPruebas" style="height: 400px; overflow-x:hidden; overflow-y:scroll;">
                            <div class="table-responsive no-padding">

                           
                            <table class="table table-hover" id="tablaScroll">
                                    <thead>
                                        <tr class="active">
                                            <th>Nombre</th>
                                            <th>Clasificación</th>
                                            <th>Tipo de programación</th>
                                            <th hidden >Hora Inicial Por Defecto / Duración máxima al día(minutos)</th>
                                            <th hidden >Hora Final Por Defecto / Cantidad máxima al día</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pausaSistema">

                                    </tbody>
                                </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-4 col-xs-3 text-left">
                            <button type="button" class="btn btn-success" onclick="agregarFilaPausaSistema()">Agregar Pausa</button>
                        </div>
                        <div class="col-md-8 col-xs-9 text-right">
                            <button type="button" class="btn btn-primary" onclick="actualizaPausas()">Guardar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>


                </div>
            </form>
        </div>

    </div>
</div>

<!-- Modal pregunta pausas -->
<div class="modal fade-in" id="modalPreguntaPausas" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat"><?php echo $str_configuracion_LE;?></h4>
            </div>
            <div class="modal-body">
                <p style="text-align: justify;">
                    <p>¿Con qué pausa deben quedar los registros que tienen este tipo de pausa?</p>
                    <div class="form-group">
                        <select id="selectPausas" class="form-control">
                        </select>
                        <input type="hidden" name="idValorDel" id="idValorDel" value="0">
                    </div>
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-primary btn " type="button" id="btnGuardarEliminacionPausas">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
            </div>
        </div>
    </div>
</div>

<!---->
<div class="modal fade-in" id="SubirUsuariosMassivo" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_CargarMassivo__; ?></h4>
            </div>
            <form id="formularioMasivo" data-toggle="validator" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Primero Debes descargar la plantilla modelo</label>
                        <a href="/DyalogoImagenes/usuario.xlsx" download="modelocargue.xlsx" class="btn">Obtener Plantilla</a>
                    </div>
                    <div class="form-group">
                        <label><?php echo $str_CargarMassivoL_; ?></label>
                        <input type="file" required="true" name="txtUsuariosMontar" id="txtUsuariosMontar" class="form-control">
                    </div>
                    <div class="alert alert-info alert-dismissible" id="alertMasiva" style="display: none;">

                    </div>
                    <div class="box box-primary" id="ReprtidosBox" style="display: none;">
                        <div class="box-header">
                            <h3 class="box-title"><?php echo $str_FallasporRepeti;?></h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $str_nombre_usuario;?></th>
                                        <th><?php echo $str_correo;?></th>
                                        <th><?php echo $str_cargo;?></th>
                                        <th><?php echo $str_identificacion;?></th>
                                    </tr>
                                </thead>
                                <tbody id="CuerpoFallasRepetidas">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="box box-primary" id="FallasBox" style="display: none;">
                        <div class="box-header">
                            <h3 class="box-title"><?php echo $str_FallasporBaseda;?></h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $str_nombre_usuario;?></th>
                                        <th><?php echo $str_correo;?></th>
                                        <th><?php echo $str_cargo;?></th>
                                        <th><?php echo $str_identificacion;?></th>
                                    </tr>
                                </thead>
                                <tbody id="CuerpoFallasBD">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn-primary btn " type="button" id="guardarFormMasivo">
                        <?php echo $str_guardar;?>
                    </button>
                    <button class="btn btn-default pull-right" type="button" data-dismiss="modal" id="cancelocargue">
                        <?php echo $str_cancela;?>
                    </button>
                    <button class="btn btn-default pull-right" type="button" data-dismiss="modal" id="cierrocargue">
                        Aceptar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- MODAL PARA EDITAR Y/O CREAR UNA MALLA DE TURNOS-->
<div class="modal fade" id="modalMallaTurno" role="dialog">
    <div class="modal-dialog" style="width: 90%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form id="formPausasDefecto" enctype="multipart/form-data" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title titulo-malla">NUEVA MALLA DE TURNOS</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nombre de la malla</label>
                                <input type="text" name="nombreMalla" id="nombreMalla" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pull-right" style="display: inline-grid;">
                                <label for="">Eliminar malla</label>
                                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarMalla();"><i class="fa fa-trash"></i></button>                                
                            </div>
                            <div class="form-group pull-right" style="display: inline-grid;margin-right: 30px;"> 
                               <label for="">limpiar</label>
                                <button type="button" class="btn btn-sm btn-warning" id="limpiarMalla"><i class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#mallaTurnoDefecto" style="margin-right:10px;">
                                    CONFIGURACIÓN DE HORARIO
                                </a>
                            </h4>
                        </div>
                        <!-- Custom Tabs (Pulled to the right) -->
                        <div class="nav-tabs-custom" class="panel-collapse" id="mallaTurnoDefecto">
                            <ul class="nav nav-tabs pull-right">
                                <li><a href="#sinhorarioDefecto" data-toggle="tab"> PAUSAS SIN HORARIO </a></li>
                                <li><a href="#conhorarioDefecto" data-toggle="tab">OTRAS PAUSAS CON HORARIO </a></li>
                                <li class="active"><a href="#horarioDefecto" data-toggle="tab">HORARIO BASE</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="horarioDefecto">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="overflow-x: auto">
                                                <table class="table table-bordered " width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Sesión</th>
                                                            <th colspan="2">
                                                                <select name="breakselectDefecto[]" id="breakselectDefecto1" style="width:175px;">
                                                                    <option value="<?php echo $arrayTipoPausaDefecto1[0]; ?>"><?php echo $arrayTipoPausaDefecto1[1]; ?></option>
                                                                </select>
                                                            </th>
                                                            <th colspan="2">
                                                                <select name="breakselectDefecto[]" id="breakselectDefecto2" style="width:175px;">
                                                                    <option value="<?php echo $arrayTipoPausaDefecto2[0]; ?>"><?php echo $arrayTipoPausaDefecto2[1]; ?></option>
                                                                </select>
                                                            </th>
                                                            <th colspan="2">
                                                                <select name="breakselectDefecto[]" id="breakselectDefecto3" style="width:175px;">
                                                                    <option value="<?php echo $arrayTipoPausaDefecto3[0]; ?>"><?php echo $arrayTipoPausaDefecto3[1]; ?></option>
                                                                </select>
                                                            </th>
                                                            <th>Sesión</th>
                                                        </tr>
                                                        <tr>
                                                            <th style="font-size:12px;">Día</th>
                                                            <th style="font-size:12px;">Hora Entrada</th>
                                                            <th style="font-size:12px;">Hora Inicio</th>
                                                            <th style="font-size:12px;">Hora Fin</th>
                                                            <th style="font-size:12px;">Hora Inicio</th>
                                                            <th style="font-size:12px;">Hora Fin</th>
                                                            <th style="font-size:12px;">Hora Inicio</th>
                                                            <th style="font-size:12px;">Hora Fin</th>
                                                            <th style="font-size:12px;">Hora salida</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="filasMalla">
                                                        <tr>
                                                            <td>Lunes</td>
                                                            <td><input type="text" class="required  mallaPredefinida entradaDefecto" id="HorIniLunDefecto" name="HorIniLunDefecto"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni1Defecto" id="breakHorIniLun1Defecto" name="breakHorIniLunDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin1Defecto" id="breakHorFinLun1Defecto" name="breakHorFinLunDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni2Defecto" id="breakHorIniLun2Defecto" name="breakHorIniLunDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin2Defecto" id="breakHorFinLun2Defecto" name="breakHorFinLunDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni3Defecto" id="breakHorIniLun3Defecto" name="breakHorIniLunDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin3Defecto" id="breakHorFinLun3Defecto" name="breakHorFinLunDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida salidaDefecto" id="HorFinLunDefecto" name="HorFinLunDefecto"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Martes</td>
                                                            <td><input type="text" class="required  mallaPredefinida entradaDefecto" id="HorIniMarDefecto" name="HorIniMarDefecto"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni1Defecto" id="breakHorIniMar1Defecto" name="breakHorIniMarDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin1Defecto" id="breakHorFinMar1Defecto" name="breakHorFinMarDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni2Defecto" id="breakHorIniMar2Defecto" name="breakHorIniMarDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin2Defecto" id="breakHorFinMar2Defecto" name="breakHorFinMarDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni3Defecto" id="breakHorIniMar3Defecto" name="breakHorIniMarDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin3Defecto" id="breakHorFinMar3Defecto" name="breakHorFinMarDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida salidaDefecto" id="HorFinMarDefecto" name="HorFinMarDefecto"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Miercoles</td>
                                                            <td><input type="text" class="required  mallaPredefinida entradaDefecto" id="HorIniMieDefecto" name="HorIniMieDefecto"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni1Defecto" id="breakHorIniMie1Defecto" name="breakHorIniMieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin1Defecto" id="breakHorFinMie1Defecto" name="breakHorFinMieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni2Defecto" id="breakHorIniMie2Defecto" name="breakHorIniMieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin2Defecto" id="breakHorFinMie2Defecto" name="breakHorFinMieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni3Defecto" id="breakHorIniMie3Defecto" name="breakHorIniMieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin3Defecto" id="breakHorFinMie3Defecto" name="breakHorFinMieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida salidaDefecto" id="HorFinMieDefecto" name="HorFinMieDefecto"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jueves</td>
                                                            <td><input type="text" class="required  mallaPredefinida entradaDefecto" id="HorIniJueDefecto" name="HorIniJueDefecto"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni1Defecto" id="breakHorIniJue1Defecto" name="breakHorIniJueDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin1Defecto" id="breakHorFinJue1Defecto" name="breakHorFinJueDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni2Defecto" id="breakHorIniJue2Defecto" name="breakHorIniJueDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin2Defecto" id="breakHorFinJue2Defecto" name="breakHorFinJueDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni3Defecto" id="breakHorIniJue3Defecto" name="breakHorIniJueDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin3Defecto" id="breakHorFinJue3Defecto" name="breakHorFinJueDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida salidaDefecto" id="HorFinJueDefecto" name="HorFinJueDefecto"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Viernes</td>
                                                            <td><input type="text" class="required  mallaPredefinida entradaDefecto" id="HorIniVieDefecto" name="HorIniVieDefecto"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni1Defecto" id="breakHorIniVie1Defecto" name="breakHorIniVieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin1Defecto" id="breakHorFinVie1Defecto" name="breakHorFinVieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni2Defecto" id="breakHorIniVie2Defecto" name="breakHorIniVieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin2Defecto" id="breakHorFinVie2Defecto" name="breakHorFinVieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaIni3Defecto" id="breakHorIniVie3Defecto" name="breakHorIniVieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida pausaFin3Defecto" id="breakHorFinVie3Defecto" name="breakHorFinVieDefecto[]"></td>
                                                            <td><input type="text" class="required  mallaPredefinida salidaDefecto" id="HorFinVieDefecto" name="HorFinVieDefecto"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sabado</td>
                                                            <td><input type="text" class="mallaPredefinida entradaDefecto" id="HorIniSabDefecto" name="HorIniSabDefecto"></td>
                                                            <td><input type="text" class="mallaPredefinida pausaIni1Defecto" id="breakHorIniSab1Defecto" name="breakHorIniSabDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida pausaFin1Defecto" id="breakHorFinSab1Defecto" name="breakHorFinSabDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorIniSab2Defecto" name="breakHorIniSabDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorFinSab2Defecto" name="breakHorFinSabDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorIniSab3Defecto" name="breakHorIniSabDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorFinSab3Defecto" name="breakHorFinSabDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida salidaDefecto" id="HorFinSabDefecto" name="HorFinSabDefecto"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Domingo</td>
                                                            <td><input type="text" class="mallaPredefinida" id="HorIniDomDefecto" name="HorIniDomDefecto"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorIniDom1Defecto" name="breakHorIniDomDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorFinDom1Defecto" name="breakHorFinDomDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorIniDom2Defecto" name="breakHorIniDomDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorFinDom2Defecto" name="breakHorFinDomDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorIniDom3Defecto" name="breakHorIniDomDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorFinDom3Defecto" name="breakHorFinDomDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="HorFinDomDefecto" name="HorFinDomDefecto"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Festivo</td>
                                                            <td><input type="text" class="mallaPredefinida" id="HorIniFesDefecto" name="HorIniFesDefecto"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorIniFes1Defecto" name="breakHorIniFesDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorFinFes1Defecto" name="breakHorFinFesDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorIniFes2Defecto" name="breakHorIniFesDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorFinFes2Defecto" name="breakHorFinFesDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorIniFes3Defecto" name="breakHorIniFesDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="breakHorFinFes3Defecto" name="breakHorFinFesDefecto[]"></td>
                                                            <td><input type="text" class="mallaPredefinida" id="HorFinFesDefecto" name="HorFinFesDefecto"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="conhorarioDefecto">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="overflow-x: auto">
                                                <table class="table table-bordered " width="100%" style="min-width: 1600px;">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th colspan="2" class="centrado">Lunes</th>
                                                            <th colspan="2" class="centrado">Martes</th>
                                                            <th colspan="2" class="centrado">Miércoles</th>
                                                            <th colspan="2" class="centrado">Jueves</th>
                                                            <th colspan="2" class="centrado">viernes</th>
                                                            <th colspan="2" class="centrado">Sábado</th>
                                                            <th colspan="2" class="centrado">Domingo</th>
                                                            <th colspan="2" class="centrado">Festivo</th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>

                                                            <th>Pausa</th>
                                                            <th class="tamano">Hora inicio</th>
                                                            <th class="tamano">Hora fin</th>
                                                            <th class="tamano">Hora incio</th>
                                                            <th class="tamano">Hora fin</th>
                                                            <th class="tamano">Hora inicio</th>
                                                            <th class="tamano">Hora fin</th>
                                                            <th class="tamano">Hora inicio</th>
                                                            <th class="tamano">Hora fin</th>
                                                            <th class="tamano">Hora inicio</th>
                                                            <th class="tamano">Hora fin</th>
                                                            <th class="tamano">Hora inicio</th>
                                                            <th class="tamano">Hora fin</th>
                                                            <th class="tamano">Hora inicio</th>
                                                            <th class="tamano">Hora fin</th>
                                                            <th class="tamano">Hora inicio</th>
                                                            <th class="tamano">Hora fin</th>
                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <tbody id="pausaConHorarioFijoPredefinida">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-primary btn-sm" id="agregarHorarioFijo1Predefinida" style="margin-left: 10px;margin-bottom: 50px;">
                                            <i class="fa fa-plus" style="margin-right: 5px;"></i>Agregar Pausa Con Horario Fijo
                                        </button>
                                    </div>
                                </div>
                                <div class="tab-pane" id="sinhorarioDefecto">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="overflow-x: auto">
                                                <table class="table table-bordered " width="100%" style="min-width: 1600px;">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th colspan="2" class="centrado">Lunes</th>
                                                            <th colspan="2" class="centrado">Martes</th>
                                                            <th colspan="2" class="centrado">Miércoles</th>
                                                            <th colspan="2" class="centrado">Jueves</th>
                                                            <th colspan="2" class="centrado">viernes</th>
                                                            <th colspan="2" class="centrado">Sábado</th>
                                                            <th colspan="2" class="centrado">Domingo</th>
                                                            <th colspan="2" class="centrado">Festivo</th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>

                                                            <th>Pausa</th>
                                                            <th class="tamano">Cantidad Max</th>
                                                            <th class="tamano">Duración Max</th>
                                                            <th class="tamano">Cantidad Max</th>
                                                            <th class="tamano">Duración Max</th>
                                                            <th class="tamano">Cantidad Max</th>
                                                            <th class="tamano">Duración Max</th>
                                                            <th class="tamano">Cantidad Max</th>
                                                            <th class="tamano">Duración Max</th>
                                                            <th class="tamano">Cantidad Max</th>
                                                            <th class="tamano">Duración Max</th>
                                                            <th class="tamano">Cantidad Max</th>
                                                            <th class="tamano">Duración Max</th>
                                                            <th class="tamano">Cantidad Max</th>
                                                            <th class="tamano">Duración Max</th>
                                                            <th class="tamano">Cantidad Max</th>
                                                            <th class="tamano">Duración Max</th>
                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <tbody id="pausaSinHorarioFijoPredefinida">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-primary btn-sm" id="agregarHorarioFijo0Predefinida" style="margin-left: 10px;margin-bottom: 50px;">
                                            <i class="fa fa-plus" style="margin-right: 5px;"></i>Agregar Pausa Sin Horario Fijo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- nav-tabs-custom -->

                    </div>
                    <div class="panel box box-primary asignarAgentes">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#seccionAgentes">
                                    ASIGNAR AGENTES
                                </a>
                            </h4>
                        </div>
                        <div id="seccionAgentes" class="panel-collapse collapse in" style="padding: 15px;">
                            <div class="form-group row" id="dragAndDrop">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" name="buscadorDisponible" id="buscadorDisponible" class="form-control">
                                        <span class="input-group-addon">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                    <p class="text-center titulo-dragdrop"><strong>Agentes no asignados</strong></p>
                                    <ul id="disponible" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                    margin-bottom: 10px;
                                                    overflow: auto;   
                                                    -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">

                                    </ul>
                                </div>
                                <div class="col-md-2 text-center" style="padding-top:100px">
                                    <button type="button" id="derecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">></button> <br>
                                    <button type="button" id="todoDerecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">>></button> <br>

                                    <button type="button" id="izquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><</button><br>
                                    <button type="button" id="todoIzquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><<</button>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" name="buscadorSeleccionado" id="buscadorSeleccionado" class="form-control">
                                        <span class="input-group-addon">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                    <p class="text-center titulo-dragdrop"><strong>Agentes asignados a esta malla</strong></p>
                                    <ul id="seleccionado" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                    margin-bottom: 10px;
                                                    overflow: auto;   
                                                    -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-primary pull-left" id="saveMalla">Guardar</button>
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                            <input type="hidden" id="operMalla" name="operMalla" value="">
                            <input type="hidden" id="idMalla" name="idMalla" value="">
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<!-- FIN DE LA MODAL DE MALLA DE TURNOS-->

<!-- Modal para prioridades de campanas -->
<div class="modal fade" id="modalPrioridadCampana">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cerrar-modal-prioridad-campana">&times;</button>
                <h4 class="modal-title">Cambiar orden de ejecucion de las campañas</h4>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="prioridadCampanaForm">

                    <input type="hidden" name="sentidoPrioridadCampana" id="sentidoPrioridadCampana">

                    <div class="row">
                        <div class="col-md-12">
                            <label id="textoPrioridadCampana"></label>
                            <input type="number" name="prioridadCampana" id="prioridadCampana" class="form-control input-sm" value="1">
                        </div>

                        <div class="col-md-12 text-center">
                            <button type="button" id="btnPrioridadCampana" class="btn btn-primary" style="margin-top: 10px;" onclick="aplicarPrioridadesCampana()">Aceptar</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //NBG 2020-03-22 ocultar mensaje de contraseña enviada al correo
    $('.msgcontrasena').hide();

    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
    var cont = 0;

    var options = { //hh:mm 24 hour format only, defaults to current time
        'timeFormat': 'H:i:s',
        'minTime': '00:00:00',
        'maxTime': '23:59:00',
        'step': '5',
        'showDuration': true
    };

    var options2 = { //hh:mm 24 hour format only, defaults to current time
        'timeFormat': 'H:i:s',
        'minTime': '00:00:00',
        'maxTime': '23:59::00',
        'step': '1',
        'showDuration': true
    };
    /**YCR 2019-10-01
     *funcion que llena la pausa con horario por defecto
     */
    $(document).on("click", "#pausaConHorarioFijo .selectPausaFija", function() {

        var MallaTurnosHorarioPorDefecto = '<?php echo  $MallaTurnosHorarioPorDefecto;?>';
        if (MallaTurnosHorarioPorDefecto == '1') {
            var idFilaPausa = $(this).next().val();
            var idPausa = $(this).val();
            $.ajax({
                url: '<?=$url_crud;?>?callHorarioPordefecto=si',
                type: 'POST',
                data: {
                    'idPausa': idPausa,
                    'tipo': '1'
                },
                dataType: 'json',
                success: function(data) {
                    var horaInicio = '';
                    var horaFin = '';
                    if (data.horaInicio != 'null') {
                        horaInicio = data.horaInicio;
                    }
                    if (data.horaFin != 'null') {
                        horaFin = data.horaFin;
                    }

                    $("#PHorIniLun" + idFilaPausa).val(horaInicio);
                    $("#PHorFinLun" + idFilaPausa).val(horaFin);
                    $("#PHorIniMar" + idFilaPausa).val(horaInicio);
                    $("#PHorFinMar" + idFilaPausa).val(horaFin);
                    $("#PHorIniMie" + idFilaPausa).val(horaInicio);
                    $("#PHorFinMie" + idFilaPausa).val(horaFin);
                    $("#PHorIniJue" + idFilaPausa).val(horaInicio);
                    $("#PHorFinJue" + idFilaPausa).val(horaFin);
                    $("#PHorIniVie" + idFilaPausa).val(horaInicio);
                    $("#PHorFinVie" + idFilaPausa).val(horaFin);
                    $("#PHorIniSab" + idFilaPausa).val(horaInicio);
                    $("#PHorFinSab" + idFilaPausa).val(horaFin);
                    $("#PHorIniDom" + idFilaPausa).val(horaInicio);
                    $("#PHorFinDom" + idFilaPausa).val(horaFin);
                    $("#PHorIniFes" + idFilaPausa).val(horaInicio);
                    $("#PHorFinFes" + idFilaPausa).val(horaFin);


                }
            });
        }
    });
    /**YCR 2019-10-01
     *funcion que llena la pausa sin horario por defecto
     */
    $(document).on("click", "#pausaSinHorarioFijo .selectPausaSinHorario", function() {
        var MallaTurnosHorarioPorDefecto = '<?php echo  $MallaTurnosHorarioPorDefecto;?>';
        if (MallaTurnosHorarioPorDefecto == '1') {
            var idFilaPausa = $(this).next().val();
            var idPausa = $(this).val();
            $.ajax({
                url: '<?=$url_crud;?>?callHorarioPordefecto=si',
                type: 'POST',
                data: {
                    'idPausa': idPausa,
                    'tipo': '0'
                },
                dataType: 'json',
                success: function(data) {
                    var cantMax = '';
                    var durMax = '';
                    if (data.canMax != 'null') {
                        cantMax = data.cantMax;
                    }
                    if (data.durMax != 'null') {
                        durMax = data.durMax;
                    }

                    $("#PCLun" + idFilaPausa).val(cantMax);
                    $("#PDMLun" + idFilaPausa).val(durMax);
                    $("#PCMar" + idFilaPausa).val(cantMax);
                    $("#PDMMar" + idFilaPausa).val(durMax);
                    $("#PCMie" + idFilaPausa).val(cantMax);
                    $("#PDMMie" + idFilaPausa).val(durMax);
                    $("#PCJue" + idFilaPausa).val(cantMax);
                    $("#PDMJue" + idFilaPausa).val(durMax);
                    $("#PCVie" + idFilaPausa).val(cantMax);
                    $("#PDMVie" + idFilaPausa).val(durMax);
                    $("#PCSab" + idFilaPausa).val(cantMax);
                    $("#PDMSab" + idFilaPausa).val(durMax);
                    $("#PCDom" + idFilaPausa).val(cantMax);
                    $("#PDMDom" + idFilaPausa).val(durMax);
                    $("#PCFes" + idFilaPausa).val(cantMax);
                    $("#PDMFes" + idFilaPausa).val(durMax);


                }
            });
        }
    });
    /**YCR 2019-10-02
     * Funcion que permite habilitar o desabiltar campos, segun el tipo de programacion seleccionada
     */
    $(document).on("change", "#pausaSistema .selectTipoProgramacion", function() {
        var tipoProgramacion = $(this).val();
        var idTipoDescanso = $(this).next().val();
        if (tipoProgramacion == '0') {
            $("#horaInicioDefecto" + idTipoDescanso).attr('readonly', true);
            $("#horaFinalDefecto" + idTipoDescanso).attr('readonly', true);
            $("#duracionMax" + idTipoDescanso).attr('readonly', false);
            $("#cantidadMax" + idTipoDescanso).attr('readonly', false);
            $("#horaInicioDefecto" + idTipoDescanso).val('');
            $("#horaFinalDefecto" + idTipoDescanso).val('');
        }

        if (tipoProgramacion == '1') {
            $("#duracionMax" + idTipoDescanso).attr('readonly', true);
            $("#cantidadMax" + idTipoDescanso).attr('readonly', true);
            $("#horaInicioDefecto" + idTipoDescanso).attr('readonly', false);
            $("#horaFinalDefecto" + idTipoDescanso).attr('readonly', false);
            $("#duracionMax" + idTipoDescanso).val('');
            $("#cantidadMax" + idTipoDescanso).val('');
        }


    });

    
    $(document).on("click", "#pausaSinHorarioFijoPredefinida .selectPausaSinHorarioPredefinida", function() {
            var MallaTurnosHorarioPorDefecto = '<?php echo  $MallaTurnosHorarioPorDefecto;?>';
            if (MallaTurnosHorarioPorDefecto == '1') {
                var idFilaPausa = $(this).next().val();
                var idPausa = $(this).val();
                $.ajax({
                    url: '<?=$url_crud;?>?callHorarioPordefecto=si',
                    type: 'POST',
                    data: {
                        'idPausa': idPausa,
                        'tipo': '0'
                    },
                    dataType: 'json',
                    success: function(data) {
                        var cantMax = '';
                        var durMax = '';
                        if (data.canMax != 'null') {
                            cantMax = data.cantMax;
                        }
                        if (data.durMax != 'null') {
                            durMax = data.durMax;
                        }

                        $("#predefinidaPCLun" + idFilaPausa).val(cantMax);
                        $("#predefinidaPDMLun" + idFilaPausa).val(durMax);
                        $("#predefinidaPCMar" + idFilaPausa).val(cantMax);
                        $("#predefinidaPDMMar" + idFilaPausa).val(durMax);
                        $("#predefinidaPCMie" + idFilaPausa).val(cantMax);
                        $("#predefinidaPDMMie" + idFilaPausa).val(durMax);
                        $("#predefinidaPCJue" + idFilaPausa).val(cantMax);
                        $("#predefinidaPDMJue" + idFilaPausa).val(durMax);
                        $("#predefinidaPCVie" + idFilaPausa).val(cantMax);
                        $("#predefinidaPDMVie" + idFilaPausa).val(durMax);
                        $("#predefinidaPCSab" + idFilaPausa).val(cantMax);
                        $("#predefinidaPDMSab" + idFilaPausa).val(durMax);
                        $("#predefinidaPCDom" + idFilaPausa).val(cantMax);
                        $("#predefinidaPDMDom" + idFilaPausa).val(durMax);
                        $("#predefinidaPCFes" + idFilaPausa).val(cantMax);
                        $("#predefinidaPDMFes" + idFilaPausa).val(durMax);


                    }
                });
            }
        });
    
    $(document).on("click", "#pausaConHorarioFijoPredefinida .selectPausaFijaPredefinida", function() {

        var MallaTurnosHorarioPorDefecto = '<?php echo  $MallaTurnosHorarioPorDefecto;?>';
        if (MallaTurnosHorarioPorDefecto == '1') {
            var idFilaPausa = $(this).next().val();
            var idPausa = $(this).val();
            $.ajax({
                url: '<?=$url_crud;?>?callHorarioPordefecto=si',
                type: 'POST',
                data: {
                    'idPausa': idPausa,
                    'tipo': '1'
                },
                dataType: 'json',
                success: function(data) {
                    var horaInicio = '';
                    var horaFin = '';
                    if (data.horaInicio != 'null') {
                        horaInicio = data.horaInicio;
                    }
                    if (data.horaFin != 'null') {
                        horaFin = data.horaFin;
                    }

                    $("#predefinidaPHorIniLun" + idFilaPausa).val(horaInicio);
                    $("#predefinidaPHorFinLun" + idFilaPausa).val(horaFin);
                    $("#predefinidaPHorIniMar" + idFilaPausa).val(horaInicio);
                    $("#predefinidaPHorFinMar" + idFilaPausa).val(horaFin);
                    $("#predefinidaPHorIniMie" + idFilaPausa).val(horaInicio);
                    $("#predefinidaPHorFinMie" + idFilaPausa).val(horaFin);
                    $("#predefinidaPHorIniJue" + idFilaPausa).val(horaInicio);
                    $("#predefinidaPHorFinJue" + idFilaPausa).val(horaFin);
                    $("#predefinidaPHorIniVie" + idFilaPausa).val(horaInicio);
                    $("#predefinidaPHorFinVie" + idFilaPausa).val(horaFin);
                    $("#predefinidaPHorIniSab" + idFilaPausa).val(horaInicio);
                    $("#predefinidaPHorFinSab" + idFilaPausa).val(horaFin);
                    $("#predefinidaPHorIniDom" + idFilaPausa).val(horaInicio);
                    $("#predefinidaPHorFinDom" + idFilaPausa).val(horaFin);
                    $("#predefinidaPHorIniFes" + idFilaPausa).val(horaInicio);
                    $("#predefinidaPHorFinFes" + idFilaPausa).val(horaFin);


                }
            });
        }
    });
    
    $(function() {


        //coloca formato tipo hora
        $(".mallaPredefinida").timepicker(options);
        $(".malla").timepicker(options);
        $("#malla_turno").select2();
        cargarMallas();
        
        $("#agregarHorarioFijo1Predefinida").on("click", function(e) {
            e.preventDefault();
            agregarFilaPausaPredefinida(1);

        });
        
        $("#agregarHorarioFijo0Predefinida").on("click", function(e) {
            e.preventDefault();
            agregarFilaPausaPredefinida(0);

        });
        
        $("#nuevaMalla").on('click', function(e) {
            $("#operMalla").val('add');
            $(".mallaPredefinida").val('');
            $("#pausaConHorarioFijoPredefinida").html('');
            $("#pausaSinHorarioFijoPredefinida").html('');
            $("#nombreMalla").html('');
            $(".asignarAgentes").css('display','none');
        });        
        
        $("#limpiarMalla").on('click', function(e) {
            $(".mallaPredefinida").val('');
            $("#pausaConHorarioFijoPredefinida").html('');
            $("#pausaSinHorarioFijoPredefinida").html('');
        });

        $(".EditmodalMalllaTurno").on('click', function(e) {
            var id = $("#malla_turno").val();
            var texto=$('#malla_turno option:selected').html(); 
            $("#operMalla").val('edit');
            $("#idMalla").val(id);
            $(".titulo-malla").html('MALLA DE TURNOS: '+texto);
            renderMalla(id);
        });

        $("#saveMalla").on('click', function(e) {
            var i = 0;
            var valido = 0;
            $(".required").each(function() {
                if ($(this).val() == '') {
                    $(this).addClass('validacion');
                    valido = 1;
                } else {
                    $(this).removeClass('validacion');
                }
                i++;
            });
            if (valido == 1) {
                alertify.error('Los campos enmarcados en rojo, son obligatorios para malla de turno');
            } else {
                guardarMalla(null);
            }
        });

        $("#Cargo").on("change", function(e) {

            var option = $("#Cargo option:selected").val();
            var accion = $("#oper").val();


            if (option == "agente" || option == 'supervision') {
                $("#Horario").removeClass("hidden");
                $("#Horario").addClass("visible");

                $('#pausaConHorarioFijo').html("");
                $('#pausaSinHorarioFijo').html("");
                $('.requerido').removeClass('validacion');
                $('.validador').removeClass('validacion');
                $(".malla").val('');

                //llenamos campos por defecto
                //horario por defecto
                var MallaTurnosHorarioPorDefecto = '<?php echo  $MallaTurnosHorarioPorDefecto;?>';
                if (MallaTurnosHorarioPorDefecto == '1') {

                    $(".pausaIni1").val('<?php echo  $horaInicialPorDefecto1; ?>');
                    $(".pausaFin1").val('<?php echo  $horaFinalPorDefecto1; ?>');
                    $(".pausaIni2").val('<?php echo  $horaInicialPorDefecto2; ?>');
                    $(".pausaFin2").val('<?php echo  $horaFinalPorDefecto2; ?>');
                    $(".pausaIni3").val('<?php echo  $horaInicialPorDefecto3; ?>');
                    $(".pausaFin3").val('<?php echo  $horaFinalPorDefecto3; ?>');
                    $(".entrada").val('<?php echo  $horaEntradaPorDefecto; ?>');
                    $(".salida").val('<?php echo  $horaSalidaPorDefecto; ?>');
                }


            } else {
                //oculta seccion malla de  turnos
                $("#Horario").removeClass("visible");
                $("#Horario").addClass("hidden");
                $('#pausaConHorarioFijo').html("");
                $('#pausaSinHorarioFijo').html("");
                $('.requerido').removeClass('validacion');
                $('.validador').removeClass('validacion');
                $(".malla").val('');


            }

        });

        $("#agregarHorarioFijo1").on("click", function(e) {
            e.preventDefault();
            agregarFilaPausa(1);

        });
        
        $("#agregarHorarioFijo0").on("click", function(e) {
            e.preventDefault();
            agregarFilaPausa(0);

        });

        $("#backofice").iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $("#cargueMassivo").click(function() {
            $("#formularioMasivo")[0].reset();
            $("#ReprtidosBox").hide();
            $("#FallasBox").hide();
            $("#alertMasiva").hide();
            $("#SubirUsuariosMassivo").modal();
            $("#cancelocargue").show();
            $("#guardarFormMasivo").show();
            $("#cierrocargue").hide();
        });

        $('#inpFotoPerfil').on('change', function(e) {
            var imax = $(this).attr('valor');
            var imagen = this.files[0];
            console.log(imagen);
            /* Validar el tipo de imagen */
            if (imagen['type'] != 'image/jpeg') {
                $('#inpFotoPerfil').val('');
                swal({
                    title: "Error al subir el archivo",
                    text: "El archivo debe estar en formato JPG",
                    type: "error",
                    confirmButtonText: "Cerrar"
                });
            } else if (imagen['size'] > 2000000) {
                $('#inpFotoPerfil').val('');
                swal({
                    title: "Error al subir el archivo",
                    text: "El archivo no debe pesar mas de 2MB",
                    type: "error",
                    confirmButtonText: "Cerrar"
                });
            } else {
                if (imagen['type'] == 'image/jpeg') {
                    var datosImagen = new FileReader();
                    datosImagen.readAsDataURL(imagen);

                    $(datosImagen).on("load", function(event) {
                        var rutaimagen = event.target.result;
                        $("#avatar3").attr("src",rutaimagen);
                        $("#hidOculto").val(1);
                    });
                }
            }
        });

        $("#usuarios").addClass('active');
        busqueda_lista_navegacion();
        $(".CargarDatos :first").click();

        $("#btnLlamadorAvanzado").click(function() {
            $('#busquedaAvanzada_ :input').each(function() {
                $(this).attr('disabled', false);
            });
        });

        $("#Save").click(function() {
            before_save();
            var valido = 0;

            if ($("#NombreUsuario").val().length < 1) {
                alertify.error('<?php echo $str_name_message_E__;?>');
                $("#NombreUsuario").focus();
                valido = 1;
                $("#NombreUsuario").addClass('validacion');
            } else {
                $("#NombreUsuario").removeClass('validacion');
            }

            if ($("#Correo").val().length < 1) {
                alertify.error('<?php echo $str_mail_message_E__;?>');
                $("#Correo").focus();
                valido = 1;
                $("#Correo").addClass('validacion');
            } else {
                $("#Correo").removeClass('validacion');

                // if (!$("#Correo").val().trim().match(/^[a-zA-Z0-9\._-]+@.+\..+$/)) {
                if (!$("#Correo").val().trim().match(/^[a-zA-Z0-9\._-]{3,}@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}[.]?([a-zA-Z]{2,4})?$/)) {
                    alertify.error('<?php echo $str_mail_message_E2_;?>');
                    $("#Correo").focus();
                    valido = 1;
                    $("#Correo").addClass('validacion');
                } else {
                    $("#Correo").removeClass('validacion');
                }
                // }
            }

            if ($("#IdentificacionUsuario").val().length < 1) {
                alertify.error('<?php echo $str_iden_message_E__;?>');
                $("#IdentificacionUsuario").focus();
                valido = 1;
                $("#IdentificacionUsuario").addClass('validacion');
            } else {
                $("#IdentificacionUsuario").removeClass('validacion');
            }

            if ($("#Cargo").val() == 0) {
                alertify.error('<?php echo $str_carg_message_E__;?>');
                $("#Cargo").focus();
                valido = 1;
                $("#Cargo").addClass('validacion');
            } else {
                $("#Cargo").removeClass('validacion');
            }

            if ($("#oper").val() == "add") {
                if ($("#hidOculto").val() == '0' && '<?php echo $fotoObligatoria;?>' == 1) {
                    alertify.error('<?php echo $str_foto_message_E__;?>');
                    $("#inpFotoPerfil").focus();
                    valido = 1;
                    $("#inpFotoPerfil").addClass('validacion');
                } else {
                    $("#inpFotoPerfil").removeClass('validacion');
                }
            }

            $(".permisoForularioRequerido").each(function() {
                if (!$(this).val()) {
                    $(this).addClass('validacion');
                    $(this).focus();
                    alertify.error('El campo formulario es requerido');
                    valido = 1;
                } else {
                    $(this).removeClass('validacion');
                }
            });

            var MallaTurnosRequerida = '<?php echo  $MallaTurnosRequerida;?>';
            if (MallaTurnosRequerida == '1') {

                var opcion = $("#Cargo option:selected").val();
                if (opcion == "agente" || opcion == 'supervision') {
                    var i = 0;
                    $(".requerido").each(function() {
                        if ($(this).val() == '') {
                            $(this).addClass('validacion');
                            valido = 1;
                        } else {
                            $(this).removeClass('validacion');
                        }
                        i++;
                    });
                    if (valido == 1) {
                        alertify.error('Los campos enmarcados en rojo, son  obligatorios para  malla de turno');
                    }
                }

            }


            if (valido == '0') {
                var form = $("#FormularioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#FormularioDatos")[0]);
                formData.append('CodigoUsuario', $("#Correo").val());
                $.ajax({
                    url: '<?=$url_crud;?>?guardarDatos=1',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    //una vez finalizado correctamente
                    success: function(data) {
                        if (data.code == '1') {

                            //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                            if ($("#oper").val() == 'add') {
                                idTotal = data.id;
                            } else {
                                idTotal = $("#hidId").val();
                            }
                            <?php if(isset($_GET['view'])){ ?>
                            window.location.href = "finalizado.php";
                            <?php }  ?>
                            //Limpiar formulario
                            form[0].reset();
                            after_save();
                            llenar_lista_navegacion('');
                            alertify.success(data.message);
                        } else if (data.code == '4') {
                            alertify.error('<?php echo $str_message_Lo5_; ?>');
                        } else {
                            //Algo paso, hay un error
                            alertify.error(data.message);
                        }
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    },
                    //si ha ocurrido un error
                    error: function() {
                        after_save_error();
                        alertify.error('<?php echo $error_de_red;?>');
                    }
                });
            }

        });


        $("#tablaScroll").on('scroll', function() {
            //alert('Si llegue');
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

                $.post("<?=$url_crud;?>", {
                    inicio: inicio,
                    fin: fin,
                    callDatosNuevamente: 'si'
                }, function(data) {
                    if (data != "") {
                        $("#TablaIzquierda").append(data);
                        inicio += fin;
                        busqueda_lista_navegacion();
                    }
                });
            }
        });

        //SECCION FUNCIONALIDAD BOTONES

        //Funcionalidad del boton + , add
        $("#add").click(function() {
            $('.msgcontrasena').show();
            //oculta seccion malla de turnos
            $("#Horario").removeClass("visible");
            $("#Horario").addClass("hidden");
            $(".seleccionarMalla").css('display','none');

            //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);
            $("#Save").attr('disabled', false);

            // Oculto las secciones de agregar campana
            $("#panelTareaSaliente").hide();
            $("#panelTareaEntrante").hide();

            //Inializacion campos vacios por defecto
            $('#FormularioDatos :input').each(function() {
                if ($(this).is(':checkbox')) {
                    if ($(this).is(':checked')) {
                        $(this).attr('checked', false);
                    }
                    $(this).attr('disabled', false);
                } else {
                    $(this).val('');
                    $(this).attr('disabled', false);
                }

            });

            $(".select2").each(function() {
                $(this).val(0).change();
            });

            $("#hidId").val(0);
            $("#h3mio").html('');
            $("#hidOculto").val(0);

            $("#avatar3").attr('src', '<?=base_url?>assets/img/Kakashi.fw.png');
            //Le informa al crud que la operaciòn a ejecutar es insertar registro
            document.getElementById('oper').value = "add";

            $("#campanasRegistradas").html("");

            before_add();
            //select por defecto
            $("#breakselect1").val('<?php echo $arrayTipoPausaDefecto1[0]; ?>');
            $("#breakselect2").val('<?php echo $arrayTipoPausaDefecto2[0]; ?>');
            $("#breakselect3").val('<?php echo $arrayTipoPausaDefecto3[0]; ?>');
            $("#estadoUsuario").val('0').change();
            $("#tipoContrato").val('0').change();
            $("#fechaIniContr").val('<?php echo date('Y-m-d');?>');

            // En la seccion de permisos de backoffice se limpia la tabla
            $("#table-permisosBOC tbody").html('');


        });

        jQuery.fn.reset = function() {
            $(this).each(function() {
                this.reset();
            });
        }

        //funcionalidad del boton editar
        $("#edit").click(function() {

            //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);
            $("#Save").attr('disabled', false);


            //Le informa al crud que la operaciòn a ejecutar es editar registro
            $("#oper").val('edit');
            //Habilitar todos los campos para edicion
            $('#FormularioDatos :input').each(function() {
                $(this).attr('disabled', false);
            });

            before_edit();

        });

        //funcionalidad del boton seleccionar_registro
        $("#cancel").click(function() {
            //Se le envia como paraetro cero a la funcion seleccionar_registro
            $('.msgcontrasena').hide();
            $(".seleccionarMalla").css('display','inherit');
            $("#add").attr('disabled', false);
            $("#edit").attr('disabled', false);
            $("#delete").attr('disabled', false);

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', true);
            $("#Save").attr('disabled', true);


            //Le informa al crud que la operaciòn a ejecutar es editar registro
            $("#oper").val('edit');
            //Habilitar todos los campos para edicion
            $('#FormularioDatos :input').each(function() {
                $(this).attr('disabled', true);
            });

            seleccionar_registro(0);
            //Se inicializa el campo oper, nuevamente
            $("#oper").val(0);

            <?php if(isset($_GET['view'])){ ?>
            window.location.href = "cancelar.php";
            <?php }  ?>
        });

        //funcionalidad del boton eliminar
        $("#delete").click(function() {

            $.ajax({
                url: '<?php echo $url_crud;?>',
                type: 'post',
                data: {
                    dameCampanas: true,
                    id_usuario: $("#hidId").val()
                },
                dataType: 'json',
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                complete: function() {
                    $.unblockUI();
                },
                success: function(data) {
                    if (data.code === 0) {
                        //Se solicita confirmacion de la operacion, para asegurarse de que no sea por error
                        alertify.confirm("<?php echo $str_message_delete_1;?> " + $("#NombreUsuario").val() + "?", function(e) {
                            //Si la persona acepta
                            if (e) {
                                var id = $("#hidId").val();
                                //se envian los datos, diciendo que la oper es "del"
                                $.ajax({
                                    url: '<?=$url_crud;?>?guardarDatos=si',
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        oper: 'del'
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        $.blockUI({
                                            baseZ: 2000,
                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                        });
                                    },
                                    complete: function() {
                                        $.unblockUI();
                                        llenar_lista_navegacion('');
                                    },
                                    success: function(data) {
                                        if (data.code == '1') {
                                            //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo                             
                                            llenar_lista_navegacion('');

                                        } else {
                                            //Algo paso, hay un error
                                            alert(data);
                                        }
                                    }
                                });

                            } else {

                            }
                        });
                    } else {
                        var campnas = '';
                        $.each(data.datos, function(i, items) {
                            campnas += items.campanas + ', ';
                        });

                        alertify.confirm("<?php echo $str_message_delete_4;?> " + $("#NombreUsuario").val() + " <?php echo $str_message_delete_2;?>" + campnas + "<?php echo $str_message_delete_3;?>", function(e) {
                            if (e) {
                                var id = $("#hidId").val();
                                //se envian los datos, diciendo que la oper es "del"
                                $.ajax({
                                    url: '<?=$url_crud;?>?guardarDatos=si',
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        oper: 'del'
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        $.blockUI({
                                            baseZ: 2000,
                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                        });
                                    },
                                    complete: function() {
                                        $.unblockUI();
                                    },
                                    success: function(data) {
                                        if (data.code == '1') {
                                            //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo                             
                                            llenar_lista_navegacion('');

                                        } else {
                                            //Algo paso, hay un error
                                            alert(data);
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            });

        });


        //datos Hoja de busqueda
        $("#BtnBusqueda_lista_navegacion").click(function() {
            //alert($("#table_search_lista_navegacion").val());
            llenar_lista_navegacion($("#table_search_lista_navegacion").val());
        });

        //Cajaj de texto de bus queda
        $("#table_search_lista_navegacion").keypress(function(e) {
            if (e.keyCode == 13) {
                llenar_lista_navegacion($(this).val());
            }
        });

        //preguntar cuando esta vacia la tabla para dejar solo los botones correctos habilitados
        var g = $("#tablaScroll").html();
        if (g === '') {
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);
        }

        $("#regenerar").click(function() {
            var valido = 0;
            if ($("#Correo").val().length < 1) {
                alertify.error('<?php echo $str_mail_message_E__;?>');
                $("#Correo").focus();
                valido = 1;
            } else {
                // if (!$("#Correo").val().trim().match(/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/)) {

                //     if (!$("#Correo").val().trim().match(/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}[.][a-zA-Z]{2,4}$/)) {
                //         alertify.error('<?php echo $str_mail_message_E2_;?>');
                //         $("#Correo").focus();
                //         valido = 1;
                //     }
                // }
            }

            if (valido == 0) {
                alertify.confirm("<?php echo $str_message_generico_G;?>", function(e) {
                    if (e) {
                        $.ajax({
                            url: "<?=$url_crud;?>?recuperarPassWord=true",
                            type: 'post',
                            data: {
                                correo: $("#Correo").val()
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
                                if (data.estado == 'ok') {
                                    alertify.success(data.mensaje);
                                } else {
                                    alertify.error(data.mensaje);
                                }
                            }

                        });
                    } else {

                    }
                });
            }
        });

        $('#txtUsuariosMontar').on('change', function(e) {
            /* primero validar que sea solo excel */
            var imagen = this.files[0];
            //console.log(imagen);
            if (imagen['type'] != "application/vnd.ms-excel" && imagen['type'] != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                $("#txtUsuariosMontar").val('');
                swal({
                    title: "Error al subir el archivo",
                    text: "El archivo debe estar en formato XLS o XLSX",
                    type: "error",
                    confirmButtonText: "Cerrar"
                });
            } else if (imagen['size'] > 5000000) {
                $("#txtUsuariosMontar").val('');
                swal({
                    title: "Error al subir el archivo",
                    text: "El archivo no debe pesar mas de 5MB",
                    type: "error",
                    confirmButtonText: "Cerrar"
                });
            }
        });

        $("#guardarFormMasivo").click(function() {
            var form = $("#formularioMasivo");
            if (form.valid()) {
                var formData = new FormData($("#formularioMasivo")[0]);
                formData.append('breakselect1', $('#breakselect1').val());
                formData.append('breakselect2', $('#breakselect2').val());
                formData.append('breakselect3', $('#breakselect3').val());
                formData.append('horaInicialPorDefecto1', '09:45:00');
                formData.append('horaFinalPorDefecto1', '10:00:00');
                formData.append('horaInicialPorDefecto2', '12:00:00');
                formData.append('horaFinalPorDefecto2', '13:00:00');
                formData.append('horaInicialPorDefecto3', '15:45:00');
                formData.append('horaFinalPorDefecto3', '16:00:00');
                formData.append('horaEntradaPorDefecto', '08:00:00');
                formData.append('horaFinalPorDefecto', '18:00:00');
                $.ajax({
                    url: '<?=$url_crud;?>?insertarMassivo=si',
                    type: 'post',
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: "<?php echo $str_message_wait  ;?>"
                        });
                    },
                    success: function(data) {
                        if (data.Fallas != 0) {
                            var tabla = '';
                            $.each(data.arrayFallas, function(i, item) {
                                tabla += '<tr>';
                                tabla += '<td>' + i + '</td>';
                                tabla += '<td>' + item.nombres + '</td>';
                                tabla += '<td>' + item.correo + '</td>';
                                tabla += '<td>' + item.cargo + '</td>';
                                tabla += '<td>' + item.identificacion + '</td>';
                                tabla += '</tr>';
                            });

                            $("#CuerpoFallasBD").html(tabla);
                            $("#FallasBox").show();
                        }

                        if (data.repetidos != 0) {
                            var tabla = '';
                            $.each(data.arrayRepetidos, function(i, item) {
                                tabla += '<tr>';
                                tabla += '<td>' + i + '</td>';
                                tabla += '<td>' + item.nombres + '</td>';
                                tabla += '<td>' + item.correo + '</td>';
                                tabla += '<td>' + item.cargo + '</td>';
                                tabla += '<td>' + item.identificacion + '</td>';
                                tabla += '</tr>';
                            });

                            $("#CuerpoFallasRepetidas").html(tabla);
                            $("#ReprtidosBox").show();
                        }

                        if (data.exitos > 0) {
                            $('#cancelocargue').hide();
                            $('#guardarFormMasivo').hide();
                            $('#cierrocargue').show();
                        }

                        $("#alertMasiva").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-info"></i> <?php echo $str_inicioMensage__; ?></h4><?php echo $str_inicioMensage2_; ?> ' + data.total + ', <?php echo $str_inicioMensage3_; ?> ' + data.exitos + ', <?php echo $str_inicioMensage4_; ?> ' + data.Fallas + ', <?php echo $str_inicioMensage5_; ?> ' + data.repetidos);
                        $("#alertMasiva").show();
                    },
                    complete: function() {
                        $.unblockUI();
                    },
                    error: function() {
                        alertify.error('<?php echo $error_de_proceso; ?>');
                    }
                })
            }
        });
    });

    // FUNCIONES PARA LA MALLA DE TURNOS PREDEFINIDA
    function cargarMallas(mallaSeleccionada = null) {

        console.log(' cargarMallas->mallaSeleccionada:>> ', mallaSeleccionada);
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                cargarMallas: 'si'
            },
            dataType: 'json',
            //una vez finalizado correctamente
            success: function(data) {
                if (data) {
                    if (data.estado == '2') {
                        $("#malla_turno").html(data.html);

                        if(mallaSeleccionada){
                            $("#malla_turno").val(mallaSeleccionada).change();
                        }

                    } else if (data.estado == '1') {
                        alertify.error('Hubo un error al cargar las mallas de turnos');
                    } else {
                        alertify.error('Por favor vuelva a iniciar sesión en el sistema');
                    }
                } else {
                    alertify.error('Error al cargar las mallas de turnos');
                }
            },
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            complete: function() {
                $.unblockUI();
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error('<?php echo $error_de_red;?>');
            }
        });
    }

    function guardarMalla() {
        console.log('guardarMalla :>> ');
        var formData = new FormData($("#formPausasDefecto")[0]);
        formData.append('guardarMalla', 'si');
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            success: function(data) {
                if(data.estado=='ok'){
                    alertify.success(data.mensaje);
                    $(".asignarAgentes").css('display','inherit');

                    let mallaSeleccionada = $("#malla_turno").val();
                    cargarMallas(mallaSeleccionada);
                    console.log('guardarMalla :>> ', mallaSeleccionada);
                }
            },
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            complete: function() {
                $.unblockUI();
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error('<?php echo $error_de_red;?>');
            }
        });
    }

    function renderMalla(id) {
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                renderMalla: 'si',
                id: id
            },
            dataType: 'json',
            success: function(data) {
                if(data.estado=='ok'){
                    $.each(data.mensaje, function(item, value) {
                        $("#"+item).val(value);
                    });
                    $("#modalMallaTurno").modal();
                    $(".asignarAgentes").css('display','inherit');
                    cargarAgentesMalla(id);
                    cargarPausasPredefinida(0);
                    cargarPausasPredefinida(1);
                }else{
                    alertify.error(data.mensaje);
                }

            },
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            complete: function() {
                $.unblockUI();
            },
            error: function() {
                alertify.error('<?php echo $error_de_red;?>');
            }
        });
    }
    
    function cargarAgentesMalla(id){
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                cargarAgentesMalla:'si',
                idMalla: id
            },
            dataType: 'json',
            success: function(data) {
                if(data.estado=='ok'){
                    $('#disponible').html(data.agentes['listaAgentesNo']);
                    $('#seleccionado').html(data.agentes['listaAgentesSi']);
                }
            },
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            complete: function() {
                $.unblockUI();
            },
            error: function() {
                alertify.error('<?php echo $error_de_red;?>');
            }
        });
    }
    
    function agregarFilaPausaPredefinida(tipo) {
        var pausa1 = '<?php echo $arrayTipoPausaDefecto1[0] ?>';
        var pausa2 = '<?php echo $arrayTipoPausaDefecto2[0] ?>';
        var pausa3 = '<?php echo $arrayTipoPausaDefecto3[0] ?>';
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                agregarFilaPausaPredefinida: 'SI',
                tipo: tipo,
                'pausa1': pausa1,
                'pausa2': pausa2,
                'pausa3': pausa3
            },
            dataType: 'json',
            success: function(data) {
                if (data.status == 'lleno') {
                    if (tipo == 1) {
                        var select = '<select class="form-control selectPausaFijaPredefinida" style="width:180px;"  name="selectTipo1Predefinida[]"><option value="0">seleccionar</option>';
                        $.each(data.tipoPausa, function(i, item) {
                            select += '<option value="' + item.id + '">' + item.tipo + '</option>';
                        });
                        select += '</select>';

                        var fila = '<tr class="selected pausaFijaPredefinida" id="filaPredefinida' + data.ultimoId + '">'
                            +
                            '<td class="hidden"><input type="text" name="idUsuPauTipo1Predefinida[]"  value="' + data.ultimoId + '"></input></td>' +
                            '<td >' + select + '<input type="hidden" value="' + data.ultimoId + '"></input></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorIniLun[]" id="predefinidaPHorIniLun' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorFinLun[]" id="predefinidaPHorFinLun' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorIniMar[]" id="predefinidaPHorIniMar' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorFinMar[]" id="predefinidaPHorFinMar' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorIniMie[]" id="predefinidaPHorIniMie' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorFinMie[]" id="predefinidaPHorFinMie' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorIniJue[]" id="predefinidaPHorIniJue' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorFinJue[]" id="predefinidaPHorFinJue' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorIniVie[]" id="predefinidaPHorIniVie' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorFinVie[]" id="predefinidaPHorFinVie' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorIniSab[]" id="predefinidaPHorIniSab' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorFinSab[]" id="predefinidaPHorFinSab' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorIniDom[]" id="predefinidaPHorIniDom' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorFinDom[]" id="predefinidaPHorFinDom' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorIniFes[]" id="predefinidaPHorIniFes' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="predefinidaPHorFinFes[]" id="predefinidaPHorFinFes' + data.ultimoId + '"  ></td>' +
                            '<td><button type="button" class="btn btn-danger" onclick="eliminarFilaPausaPredefinida(' + data.ultimoId + ');"><i class="fa fa-trash"></i></button></td>'
                            +
                            '</tr>';
                        //agregamos fila ala tabla
                        $("#pausaConHorarioFijoPredefinida").append(fila);
                        //le colocamos las opciones de timepicker
                        aplicarTimePicker(data.ultimoId, tipo);
                    }
                    if (tipo == 0) {
                        var select = '<select class="form-control selectPausaSinHorarioPredefinida" style="width:180px;"  name="selectTipo0Predefinida[]"><option value="0">seleccionar</option>';
                        $.each(data.tipoPausa, function(i, item) {
                            select += '<option value="' + item.id + '">' + item.tipo + '</option>';
                        });
                        select += '</select>'
                        var fila = '<tr class="selected pausaNoFija" id="filaPredefinida' + data.ultimoId + '">'
                            +
                            '<td class="hidden"><input type="text" name="idUsuPauTipo0Predefinida[]" value="' + data.ultimoId + '"></input></td>' +
                            '<td >' + select + '<input type="hidden" value="' + data.ultimoId + '"></input></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="predefinidaPCLun[]" id="predefinidaPCLun' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="predefinidaPDMLun[]" id="predefinidaPDMLun' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="predefinidaPCMar[]" id="predefinidaPCMar' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="predefinidaPDMMar[]" id="predefinidaPDMMar' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="predefinidaPCMie[]" id="predefinidaPCMie' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="predefinidaPDMMie[]" id="predefinidaPDMMie' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="predefinidaPCJue[]" id="predefinidaPCJue' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="predefinidaPDMJue[]" id="predefinidaPDMJue' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="predefinidaPCVie[]" id="predefinidaPCVie' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="predefinidaPDMVie[]" id="predefinidaPDMVie' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="predefinidaPCSab[]" id="predefinidaPCSab' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="predefinidaPDMSab[]" id="predefinidaPDMSab' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="predefinidaPCDom[]" id="predefinidaPCDom' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="predefinidaPDMDom[]" id="predefinidaPDMDom' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="predefinidaPCFes[]" id="predefinidaPCFes' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="predefinidaPDMFes[]" id="predefinidaPDMFes' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><button type="button" class="btn btn-danger" onclick="eliminarFilaPausaPredefinida(' + data.ultimoId + ');"><i class="fa fa-trash"></i></button></td>'
                            +
                            '</tr>';
                        //agregamos fila ala tabla
                        $("#pausaSinHorarioFijoPredefinida").append(fila);
                        //aplicamos plugin timePicker
                        aplicarTimePicker(data.ultimoId, tipo);
                    }
                } else {
                    alertify.error('No hay pausas disponibles');
                }
            }
        });
    }
    
    function eliminarFilaPausaPredefinida(index) {
            $.ajax({
                url: '<?=$url_crud;?>',
                type: 'POST',
                data: {
                    eliminarFilaPausaPredefinida: 'SI',
                    index: index
                },
                success: function(data) {
                    if (data == 'true') {
                        $("#filaPredefinida" + index).remove();
                        alertify.success('Se elimino la pausa exitosamente');
                    } else {
                        alert('ocurrio un error inesperado,intentato de nuevo');
                    }
                }
            });
        }
    
    function cargarPausasPredefinida(tipo) {
        var pausa1 = '<?php echo $arrayTipoPausaDefecto1[0] ?>';
        var pausa2 = '<?php echo $arrayTipoPausaDefecto2[0] ?>';
        var pausa3 = '<?php echo $arrayTipoPausaDefecto3[0] ?>';
        var idMalla = $("#malla_turno").val();

        $.ajax({
            url: '<?=$url_crud;?>?cargarPausasPredefinida=si',
            type: 'POST',
            data: {
                'pausa1': pausa1,
                'pausa2': pausa2,
                'pausa3': pausa3,
                'idMalla': idMalla,
                'tipo': tipo
            },
            dataType: 'json',
            success: function(data) {
                if (tipo == 1) {
                    llenarFilasPausaTipo1Predefinida(data.datosUsuPau1, data.tipoPausas, tipo);
                }
                if (tipo == 0) {
                    llenarFilasPausaTipo0Predefinida(data.datosUsuPau0, data.tipoPausas, tipo);
                }



            },
            error: function() {
                alertify.error('Ha ocurrido un error de red');
            }
        });


    }
    
    function llenarFilasPausaTipo1Predefinida(filasUsuPau, tipoPausas, Tipo) {

        $("#pausaConHorarioFijoPredefinida").html("");
        var select = '<select class="form-control selectPausaFija"  name="selectTipo1Predefinida[]" style="width:180px;"><option value="0">seleccionar</option>';
        $.each(tipoPausas, function(i, item) {
            select += '<option value="' + item.id + '">' + item.tipo + '</option>';
        });
        select += '</select>';

        $.each(filasUsuPau, function(i, item) {
            var fila = '<tr class="selected pausaFija" id="filaPredefinida' + item.PAUSASMALLA_ConsInte__b + '">' +
                '<td class="hidden"><input type="text" name="idUsuPauTipo1Predefinida[]"  value="' + item.PAUSASMALLA_ConsInte__b + '"></input></td>' +
                '<td >' + select + '<input type="hidden" value="' + item.PAUSASMALLA_ConsInte__b + '"></input></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorIniLun_b + '" name="predefinidaPHorIniLun[]" id="predefinidaPHorIniLun' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorFinLun_b + '" name="predefinidaPHorFinLun[]" id="predefinidaPHorFinLun' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorIniMar_b + '" name="predefinidaPHorIniMar[]" id="predefinidaPHorIniMar' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorFinMar_b + '" name="predefinidaPHorFinMar[]" id="predefinidaPHorFinMar' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorIniMie_b + '" name="predefinidaPHorIniMie[]" id="predefinidaPHorIniMie' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorFinMie_b + '" name="predefinidaPHorFinMie[]" id="predefinidaPHorFinMie' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorIniJue_b + '" name="predefinidaPHorIniJue[]" id="predefinidaPHorIniJue' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorFinJue_b + '" name="predefinidaPHorFinJue[]" id="predefinidaPHorFinJue' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorIniVie_b + '" name="predefinidaPHorIniVie[]" id="predefinidaPHorIniVie' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorFinVie_b + '" name="predefinidaPHorFinVie[]" id="predefinidaPHorFinVie' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorIniSab_b + '" name="predefinidaPHorIniSab[]" id="predefinidaPHorIniSab' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorFinSab_b + '" name="predefinidaPHorFinSab[]" id="predefinidaPHorFinSab' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorIniDom_b + '" name="predefinidaPHorIniDom[]" id="predefinidaPHorIniDom' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorFinDom_b + '" name="predefinidaPHorFinDom[]" id="predefinidaPHorFinDom' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorIniFes_b + '" name="predefinidaPHorIniFes[]" id="predefinidaPHorIniFes' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.PAUSASMALLA_HorFinFes_b + '" name="predefinidaPHorFinFes[]" id="predefinidaPHorFinFes' + item.PAUSASMALLA_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarFilaPausaPredefinida(' + item.PAUSASMALLA_ConsInte__b + ');"><i class="fa fa-trash"></i></button></td>'

                +
                '</tr>';

            $("#pausaConHorarioFijoPredefinida").append(fila);
            $("#filaPredefinida" + item.PAUSASMALLA_ConsInte__b + " select").val(item.PAUSASMALLA_PausasId_b).change();
            aplicarTimePicker(item.PAUSASMALLA_ConsInte__b, Tipo);
        });
    }

    function llenarFilasPausaTipo0Predefinida(filasUsuPau, tipoPausas, Tipo) {
        $("#pausaSinHorarioFijoPredefinida").html("");
        var select = '<select class="form-control selectPausaSinHorario" style="width:180px;" name="selectTipo0Predefinida[]"><option value="0">seleccionar</option>';
        $.each(tipoPausas, function(i, item) {
            select += '<option value="' + item.id + '">' + item.tipo + '</option>';
        });
        select += '</select>';

        $.each(filasUsuPau, function(i, item) {

            var fila = '<tr class="selected pausaNoFija" id="filaPredefinida' + item.PAUSASMALLA_ConsInte__b + '">'

                +
                '<td class="hidden"><input type="text" name="idUsuPauTipo0Predefinida[]" value="' + item.PAUSASMALLA_ConsInte__b + '"></input></td>' +
                '<td >' + select + '<input type="hidden" value="' + item.PAUSASMALLA_ConsInte__b + '"></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.PAUSASMALLA_CanMaxLun_b + '" name="predefinidaPCLun[]" id="predefinidaPCLun' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.PAUSASMALLA_DurMaxLun_b + '" name="predefinidaPDMLun[]" id="predefinidaPDMLun' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.PAUSASMALLA_CanMaxMar_b + '" name="predefinidaPCMar[]" id="predefinidaPCMar' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.PAUSASMALLA_DurMaxMar_b + '" name="predefinidaPDMMar[]" id="predefinidaPDMMar' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.PAUSASMALLA_CanMaxMie_b + '" name="predefinidaPCMie[]" id="predefinidaPCMie' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.PAUSASMALLA_DurMaxMie_b + '" name="predefinidaPDMMie[]" id="predefinidaPDMMie' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.PAUSASMALLA_CanMaxJue_b + '" name="predefinidaPCJue[]" id="predefinidaPCJue' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.PAUSASMALLA_DurMaxJue_b + '" name="predefinidaPDMJue[]" id="predefinidaPDMJue' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.PAUSASMALLA_CanMaxVie_b + '" name="predefinidaPCVie[]" id="predefinidaPCVie' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.PAUSASMALLA_DurMaxVie_b + '" name="predefinidaPDMVie[]" id="predefinidaPDMVie' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.PAUSASMALLA_CanMaxSab_b + '" name="predefinidaPCSab[]" id="predefinidaPCSab' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.PAUSASMALLA_DurMaxSab_b + '" name="predefinidaPDMSab[]" id="predefinidaPDMSab' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.PAUSASMALLA_CanMaxDom_b + '" name="predefinidaPCDom[]" id="predefinidaPCDom' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.PAUSASMALLA_DurMaxDom_b + '" name="predefinidaPDMDom[]" id="predefinidaPDMDom' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.PAUSASMALLA_CanMaxFes_b + '" name="predefinidaPCFes[]" id="predefinidaPCFes' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.PAUSASMALLA_DurMaxFes_b + '" name="predefinidaPDMFes[]" id="predefinidaPDMFes' + item.PAUSASMALLA_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarFilaPausaPredefinida(' + item.PAUSASMALLA_ConsInte__b + ');"><i class="fa fa-trash"></i></button></td>'

                +
                '</tr>';


            $("#pausaSinHorarioFijoPredefinida").append(fila);
            $("#filaPredefinida" + item.PAUSASMALLA_ConsInte__b + " select").val(item.PAUSASMALLA_PausasId_b).change();
            aplicarTimePicker(item.PAUSASMALLA_ConsInte__b, Tipo);
        });
    }
    
    function eliminarMalla(){
        var id = $("#malla_turno").val();
        var texto=$('#malla_turno option:selected').html();
        alertify.confirm("Esta seguro de eliminar la malla de turnos: "+texto+" ?", function(e) {
            //Si la persona acepta
            if (e) {
                //se envian los datos, diciendo que la oper es "del"
                $.ajax({
                    url: '<?=$url_crud;?>',
                    type: 'POST',
                    data: {
                        id: id,
                        deleteMalla:'si'
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    },
                    success: function(data) {
                        if(data.estado=='ok'){
                            alertify.success(data.mensaje);
                        }
                    }
                });
            }
        });
    }
    
    /*FIN DE LAS FUNCIONES PARA LA MALLA PREDEFINIDA*/

    function llenar_lista_navegacion(x) {

        var tr = '';
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                CallDatosJson: 'SI',
                Busqueda: x
            },
            dataType: 'json',
            success: function(data) {
                //Cargar la lista con los datos obtenidos en la consulta
                $.each(data, function(i, item) {
                    var estado = '';
                    if (data[i].estado == -1) {
                        estado = 'INACTIVO';
                    }
                    tr += "<tr class='CargarDatos' id='" + data[i].id + "'>";
                    tr += "<td>";
                    tr += "<p style='font-size:14px;'><b>" + data[i].camp1 + " <strong style='float:right; color:red'>" + estado + "</strong></b></p>";
                    tr += "<p style='font-size:12px; margin-top:-10px;'>" + data[i].camp2 + "</p>";
                    tr += "</td>";
                    tr += "</tr>";
                });
                $("#tablaScroll").html(tr);
                //aplicar funcionalidad a la Lista de navegacion
                busqueda_lista_navegacion();

                //SI el Id existe, entonces le damos click,  para traer sis datos y le damos la clase activa
                if ($("#" + idTotal).length > 0) {
                    $("#" + idTotal).click();
                    $("#" + idTotal).addClass('active');
                } else {
                    //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                    $(".CargarDatos :first").click();
                }

            }
        });
    }

    //poner en el formulario de la derecha los datos del registro seleccionado a la izquierda, funcionalidad de la lista de navegacion
    function busqueda_lista_navegacion() {

        $(".CargarDatos").click(function() {

            $(".seleccionarMalla").css('display','inherit');

            //remover todas las clases activas de la lista de navegacion
            $(".CargarDatos").each(function() {
                $(this).removeClass('active');
            });

            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass('active');


            var id = $(this).attr('id');
            //buscar los datos
            $.ajax({
                url: '<?=$url_crud;?>',
                type: 'POST',
                data: {
                    CallDatos: 'SI',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    //recorrer datos y enviarlos al formulario+

                    $.each(data.datosUsuario, function(i, item) {

                        $("#Cargo").val(item.USUARI_Cargo_____b);

                        $("#Cargo").val(item.USUARI_Cargo_____b).change();

                        $("#IdentificacionUsuario").val(item.USUARI_Identific_b);

                        $("#NombreUsuario").val(item.USUARI_Nombre____b);

                        $("#CodigoUsuario").val(item.USUARI_Codigo____b);

                        $("#Correo").val(item.USUARI_Correo___b);

                        $("#h3mio").html(item.principal);
                        
                        let img = item.USUARI_Foto______b;
                        const myArr = img.split("Kakashi");
                        if(myArr.length>1){
                            $("#avatar3").attr("src", "<?=base_url?>"+item.USUARI_Foto______b);
                        }else{
                            $("#avatar3").attr("src", "<?=base_url_image?>"+item.USUARI_Foto______b);
                        }
                        
                        $("#passwordActual").val(item.USUARI_Clave_____b);

                        $("#hidOculto").val(0);

                        $("#cmbPerfiles").val(item.USUARI_ConsInte__USUPER_b).trigger("change");

                        $("#regenerar").attr('disabled', false);

                        $("#estadoUsuario").val(item.USUARI_Bloqueado_b).change();

                        $("#tipoContrato").val(item.USUARI_TipoContr_b).change();

                        $("#fechaIniContr").val(item.USUARI_FechIniContr_b);

                        $("#fechaFinContr").val(item.USUARI_FechFinContr_b);



                        if (item.USUARI_Cargo_____b == "agente" || item.USUARI_Cargo_____b == 'supervision') {
                            $("#malla_turno").val(item.USUARI_IdMalla_b).trigger("change");
                            
                            $("#HorIniLun").val(resetearHorasCero(item.USUARI_HorIniLun_b));

                            $("#HorFinLun").val(resetearHorasCero(item.USUARI_HorFinLun_b));

                            $("#HorIniMar").val(resetearHorasCero(item.USUARI_HorIniMar_b));

                            $("#HorFinMar").val(resetearHorasCero(item.USUARI_HorFinMar_b));

                            $("#HorIniMie").val(resetearHorasCero(item.USUARI_HorIniMie_b));

                            $("#HorFinMie").val(resetearHorasCero(item.USUARI_HorFinMie_b));

                            $("#HorIniJue").val(resetearHorasCero(item.USUARI_HorIniJue_b));

                            $("#HorFinJue").val(resetearHorasCero(item.USUARI_HorFinJue_b));

                            $("#HorIniVie").val(resetearHorasCero(item.USUARI_HorIniVie_b));

                            $("#HorFinVie").val(resetearHorasCero(item.USUARI_HorFinVie_b));

                            $("#HorIniSab").val(resetearHorasCero(item.USUARI_HorIniSab_b));

                            $("#HorFinSab").val(resetearHorasCero(item.USUARI_HorFinSab_b));

                            $("#HorIniDom").val(resetearHorasCero(item.USUARI_HorIniDom_b));

                            $("#HorFinDom").val(resetearHorasCero(item.USUARI_HorFinDom_b));

                            $("#HorIniFes").val(resetearHorasCero(item.USUARI_HorIniFes_b));

                            $("#HorFinFes").val(resetearHorasCero(item.USUARI_HorFinFes_b));

                            llenarPausasPorDefecto(data.datosUsuPau1, 1);
                            llenarPausasPorDefecto(data.datosUsuPau2, 2);
                            llenarPausasPorDefecto(data.datosUsuPau3, 3);
                            cargarPausas(1);
                            cargarPausas(0);

                        }




                    });

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatos :input').each(function() {
                        $(this).attr('disabled', true);
                    });

                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr('disabled', false);
                    $("#edit").attr('disabled', false);
                    $("#delete").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                }
            });

            $.ajax({
                url: '<?=$url_crud;?>?dameCampanas=true',
                type: 'POST',
                data: {
                    id_usuario: id,
                    idioma: '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                },
                success: function(data) {
                    //recorrer datos y enviarlos al formulario
                    $("#campanasRegistradas").html(data);
                }
            });

            $("#hidId").val(id);
            idTotal = $("#hidId").val();

            // La funcion de encarga de traer la lista de permisos
            // de backoffice y calidad para cada usuario
            
            cargarListaTarea('saliente');
            cargarListaTarea('entrante');
            $("#panelTareaSaliente").show();
            $("#panelTareaEntrante").show();
            cargarListaPermisosBOC();
        });
    }

    function seleccionar_registro() {
        //Seleccinar loos registros de la Lista de navegacion, 
        if ($("#" + idTotal).length > 0) {
            $("#" + idTotal).click();
            $("#" + idTotal).addClass('active');
            idTotal = 0;
        } else {
            $(".CargarDatos :first").click();

        }

    }

    function llenarFilasPausaTipo1(filasUsuPau, tipoPausas, Tipo) {

        $("#pausaConHorarioFijo").html("");
        var select = '<select class="form-control selectPausaFija"  name="selectTipo1[]" style="width:180px;"><option value="0">seleccionar</option>';
        $.each(tipoPausas, function(i, item) {
            select += '<option value="' + item.id + '">' + item.tipo + '</option>';
        });
        select += '</select>';

        $.each(filasUsuPau, function(i, item) {
            var fila = '<tr class="selected pausaFija" id="fila' + item.USUPAU_ConsInte__b + '">' +
                '<td class="hidden"><input type="text" name="idUsuPauTipo1[]"  value="' + item.USUPAU_ConsInte__b + '"></input></td>' +
                '<td >' + select + '<input type="hidden" value="' + item.USUPAU_ConsInte__b + '"></input></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorIniLun_b + '" name="PHorIniLun[]" id="PHorIniLun' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorFinLun_b + '" name="PHorFinLun[]" id="PHorFinLun' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorIniMar_b + '" name="PHorIniMar[]" id="PHorIniMar' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorFinMar_b + '" name="PHorFinMar[]" id="PHorFinMar' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorIniMie_b + '" name="PHorIniMie[]" id="PHorIniMie' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorFinMie_b + '" name="PHorFinMie[]" id="PHorFinMie' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorIniJue_b + '" name="PHorIniJue[]" id="PHorIniJue' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorFinJue_b + '" name="PHorFinJue[]" id="PHorFinJue' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorIniVie_b + '" name="PHorIniVie[]" id="PHorIniVie' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorFinVie_b + '" name="PHorFinVie[]" id="PHorFinVie' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorIniSab_b + '" name="PHorIniSab[]" id="PHorIniSab' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorFinSab_b + '" name="PHorFinSab[]" id="PHorFinSab' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorIniDom_b + '" name="PHorIniDom[]" id="PHorIniDom' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorFinDom_b + '" name="PHorFinDom[]" id="PHorFinDom' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorIniFes_b + '" name="PHorIniFes[]" id="PHorIniFes' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><input type="text" class="form-control input-sm Hora" value="' + item.USUPAU_HorFinFes_b + '" name="PHorFinFes[]" id="PHorFinFes' + item.USUPAU_ConsInte__b + '" placeholder="HH:MM:SS" ></td>' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarFilaPausa(' + item.USUPAU_ConsInte__b + ');"><i class="fa fa-trash"></i></button></td>'

                +
                '</tr>';

            $("#pausaConHorarioFijo").append(fila);
            $("#fila" + item.USUPAU_ConsInte__b + " select").val(item.USUPAU_PausasId_b).change();
            aplicarTimePicker(item.USUPAU_ConsInte__b, Tipo);
        });

        $('#FormularioDatos :input').each(function() {
            $(this).attr('disabled', true);
        });
    }

    function llenarFilasPausaTipo0(filasUsuPau, tipoPausas, Tipo) {
        $("#pausaSinHorarioFijo").html("");
        var select = '<select class="form-control selectPausaSinHorario" style="width:180px;" name="selectTipo0[]"><option value="0">seleccionar</option>';
        $.each(tipoPausas, function(i, item) {
            select += '<option value="' + item.id + '">' + item.tipo + '</option>';
        });
        select += '</select>';

        $.each(filasUsuPau, function(i, item) {

            var fila = '<tr class="selected pausaNoFija" id="fila' + item.USUPAU_ConsInte__b + '">'

                +
                '<td class="hidden"><input type="text" name="idUsuPauTipo0[]" value="' + item.USUPAU_ConsInte__b + '"></input></td>' +
                '<td >' + select + '<input type="hidden" value="' + item.USUPAU_ConsInte__b + '"></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.USUPAU_CanMaxLun_b + '" name="PCLun[]" id="PCLun' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.USUPAU_DurMaxLun_b + '" name="PDMLun[]" id="PDMLun' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.USUPAU_CanMaxMar_b + '" name="PCMar[]" id="PCMar' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.USUPAU_DurMaxMar_b + '" name="PDMMar[]" id="PDMMar' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.USUPAU_CanMaxMie_b + '" name="PCMie[]" id="PCMie' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.USUPAU_DurMaxMie_b + '" name="PDMMie[]" id="PDMMie' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.USUPAU_CanMaxJue_b + '" name="PCJue[]" id="PCJue' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.USUPAU_DurMaxJue_b + '" name="PDMJue[]" id="PDMJue' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.USUPAU_CanMaxVie_b + '" name="PCVie[]" id="PCVie' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.USUPAU_DurMaxVie_b + '" name="PDMVie[]" id="PDMVie' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.USUPAU_CanMaxSab_b + '" name="PCSab[]" id="PCSab' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.USUPAU_DurMaxSab_b + '" name="PDMSab[]" id="PDMSab' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.USUPAU_CanMaxDom_b + '" name="PCDom[]" id="PCDom' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.USUPAU_DurMaxDom_b + '" name="PDMDom[]" id="PDMDom' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="number" class="form-control input-sm " value="' + item.USUPAU_CanMaxFes_b + '" name="PCFes[]" id="PCFes' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><input type="text" class="form-control input-sm " value="' + item.USUPAU_DurMaxFes_b + '" name="PDMFes[]" id="PDMFes' + item.USUPAU_ConsInte__b + '" placeholder="0" ></td>' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarFilaPausa(' + item.USUPAU_ConsInte__b + ');"><i class="fa fa-trash"></i></button></td>'

                +
                '</tr>';


            $("#pausaSinHorarioFijo").append(fila);
            $("#fila" + item.USUPAU_ConsInte__b + " select").val(item.USUPAU_PausasId_b).change();
            aplicarTimePicker(item.USUPAU_ConsInte__b, Tipo);
        });

        $('#FormularioDatos :input').each(function() {
            $(this).attr('disabled', true);
        });
    }


    //agrega un fila para una pausa con horario fijo
    function agregarFilaPausa(tipo) {
        var pausa1 = '<?php echo $arrayTipoPausaDefecto1[0] ?>';
        var pausa2 = '<?php echo $arrayTipoPausaDefecto2[0] ?>';
        var pausa3 = '<?php echo $arrayTipoPausaDefecto3[0] ?>';
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                agregarFilaPausa: 'SI',
                tipo: tipo,
                'pausa1': pausa1,
                'pausa2': pausa2,
                'pausa3': pausa3
            },
            dataType: 'json',
            success: function(data) {
                if (data.status == 'lleno') {
                    if (tipo == 1) {

                        var select = '<select class="form-control selectPausaFija" style="width:180px;"  name="selectTipo1[]"><option value="0">seleccionar</option>';
                        $.each(data.tipoPausa, function(i, item) {
                            select += '<option value="' + item.id + '">' + item.tipo + '</option>';
                        });
                        select += '</select>';

                        var fila = '<tr class="selected pausaFija" id="fila' + data.ultimoId + '">'

                            +
                            '<td class="hidden"><input type="text" name="idUsuPauTipo1[]"  value="' + data.ultimoId + '"></input></td>' +
                            '<td >' + select + '<input type="hidden" value="' + data.ultimoId + '"></input></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorIniLun[]" id="PHorIniLun' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorFinLun[]" id="PHorFinLun' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorIniMar[]" id="PHorIniMar' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorFinMar[]" id="PHorFinMar' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorIniMie[]" id="PHorIniMie' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorFinMie[]" id="PHorFinMie' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorIniJue[]" id="PHorIniJue' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorFinJue[]" id="PHorFinJue' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorIniVie[]" id="PHorIniVie' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorFinVie[]" id="PHorFinVie' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorIniSab[]" id="PHorIniSab' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorFinSab[]" id="PHorFinSab' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorIniDom[]" id="PHorIniDom' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorFinDom[]" id="PHorFinDom' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorIniFes[]" id="PHorIniFes' + data.ultimoId + '"  ></td>' +
                            '<td><input type="text" class="form-control input-sm Hora" value="0" name="PHorFinFes[]" id="PHorFinFes' + data.ultimoId + '"  ></td>' +
                            '<td><button type="button" class="btn btn-danger" onclick="eliminarFilaPausa(' + data.ultimoId + ');"><i class="fa fa-trash"></i></button></td>'

                            +
                            '</tr>';

                        //agregamos fila ala tabla

                        $("#pausaConHorarioFijo").append(fila);

                        //le colocamos las opciones de timepicker

                        aplicarTimePicker(data.ultimoId, tipo);





                    }
                    if (tipo == 0) {
                        var select = '<select class="form-control selectPausaSinHorario" style="width:180px;"  name="selectTipo0[]"><option value="0">seleccionar</option>';

                        $.each(data.tipoPausa, function(i, item) {
                            select += '<option value="' + item.id + '">' + item.tipo + '</option>';
                        });

                        select += '</select>'

                        var fila = '<tr class="selected pausaNoFija" id="fila' + data.ultimoId + '">'

                            +
                            '<td class="hidden"><input type="text" name="idUsuPauTipo0[]" value="' + data.ultimoId + '"></input></td>' +
                            '<td >' + select + '<input type="hidden" value="' + data.ultimoId + '"></input></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="PCLun[]" id="PCLun' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="PDMLun[]" id="PDMLun' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="PCMar[]" id="PCMar' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="PDMMar[]" id="PDMMar' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="PCMie[]" id="PCMie' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="PDMMie[]" id="PDMMie' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="PCJue[]" id="PCJue' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="PDMJue[]" id="PDMJue' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="PCVie[]" id="PCVie' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="PDMVie[]" id="PDMVie' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="PCSab[]" id="PCSab' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="PDMSab[]" id="PDMSab' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="PCDom[]" id="PCDom' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="PDMDom[]" id="PDMDom' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="number" class="form-control input-sm " value="0" name="PCFes[]" id="PCFes' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><input type="text" class="form-control input-sm " value="0" name="PDMFes[]" id="PDMFes' + data.ultimoId + '" placeholder="0" ></td>' +
                            '<td><button type="button" class="btn btn-danger" onclick="eliminarFilaPausa(' + data.ultimoId + ');"><i class="fa fa-trash"></i></button></td>'

                            +
                            '</tr>';

                        //agregamos fila ala tabla

                        $("#pausaSinHorarioFijo").append(fila);

                        //aplicamos plugin timePicker
                        aplicarTimePicker(data.ultimoId, tipo);


                    }
                } else {
                    alertify.error('No hay pausas disponibles');
                }

            }
        });

    }

    function eliminarFilaPausa(index) {

        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                eliminarFilaPausa: 'SI',
                index: index
            },
            success: function(data) {

                if (data == 'true') {
                    $("#fila" + index).remove();
                } else {
                    alert('ocurrio un error inesperado,intentato de nuevo');
                }
            }
        });
    }

    function eliminarFilaPausaSistema(index) {
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                eliminarFilaPausaSistema: 'SI',
                index: index
            },
            // dataType: 'json',
            // async: true,

            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                if (data == 'false') {
                    $("#filaTipoDescanso" + index).remove();
                }
                if (data == 'true') {
                    $('#modalPreguntaPausas').modal()
                    guardarPausas(index)
                    obtenerPausas(index)
                }
            },
            complete: function() {
                $.unblockUI()
            },
            error: function() {
                alertify.error('Ocurrio un problema en la red');
            }
        });
    }

    function aplicarTimePicker(index, Tipo) {

        if (Tipo == '1') {
            $("#PHorIniLun" + index).timepicker(options);
            $("#PHorFinLun" + index).timepicker(options);
            $("#PHorIniMar" + index).timepicker(options);
            $("#PHorFinMar" + index).timepicker(options);
            $("#PHorIniMie" + index).timepicker(options);
            $("#PHorFinMie" + index).timepicker(options);
            $("#PHorIniJue" + index).timepicker(options);
            $("#PHorFinJue" + index).timepicker(options);
            $("#PHorIniVie" + index).timepicker(options);
            $("#PHorFinVie" + index).timepicker(options);
            $("#PHorIniSab" + index).timepicker(options);
            $("#PHorFinSab" + index).timepicker(options);
            $("#PHorIniDom" + index).timepicker(options);
            $("#PHorFinDom" + index).timepicker(options);
            $("#PHorIniFes" + index).timepicker(options);
            $("#PHorFinFes" + index).timepicker(options);
        }

        if (Tipo == '0') {
            $("#PDMLun" + index).timepicker(options2);
            $("#PDMMar" + index).timepicker(options2);
            $("#PDMMie" + index).timepicker(options2);
            $("#PDMJue" + index).timepicker(options2);
            $("#PDMVie" + index).timepicker(options2);
            $("#PDMSab" + index).timepicker(options2);
            $("#PDMDom" + index).timepicker(options2);
            $("#PDMFes" + index).timepicker(options2);
        }
    }


    const obtenerPausas = (intIndex) => {
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                callDatosTipoDescanso: 'SI'
            },
            dataType: 'json',
            async: true,
            
            beforeSend: function () {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                console.log("data pausas", data)
                pintarPausasSelect(data.tipoPausa, intIndex)
                
            },
            complete: function () {
                $.unblockUI()
            },
            error: function() {
                alertify.error('Ocurrio un problema en la red');
            }
        });
    }

    const pintarPausasSelect = (pausas, intId) => {
        const selectPausas = document.getElementById('selectPausas')
        
        limpiar(selectPausas)
         
        pausas.forEach(pausa => {
            const option = document.createElement('option')

            if (pausa.tipo_pausa === '0' && pausa.id != intId) {
                const value = pausa.id
                const text = pausa.tipo
                option.setAttribute('id', `${pausa.id}`)
                option.value = value
                option.text = text
                selectPausas.appendChild(option)                
            }

       });
    }
    
    const limpiar = (select) => {
        for (let i = select.options.length; i >= 0; i--) {
            console.log('i :>> ', i);
            select.remove(i);
        }
    };

    const guardarPausas = (index) => {
        const selectPausas = document.getElementById('selectPausas')
        const btnGuardarEliminacionPausas = document.getElementById('btnGuardarEliminacionPausas')
        
        btnGuardarEliminacionPausas.onclick = () => {
            
            $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                eliminarPausa: 'SI',
                idPausaReplace: selectPausas.value,
                idPausaEliminar: index
            },
            dataType: 'json',
            async: true,
            
            beforeSend: function () {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                if(data.status === true) {
                    alertify.success(`${data.message}`)
                    $(`#filaTipoDescanso${index}`).remove();
                    $('#modalPreguntaPausas').modal('hide')

                }else {
                    alertify.error(`${data.message}`)
                }
            },
            complete: function () {
                $.unblockUI()
            },
            error: function() {
                alertify.error('Ocurrio un problema en la red');
            }
        });
        }
    }
  
    function llenarPausaSistema() {
        $("#pausaSistema").html("");
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                callDatosTipoDescanso: 'SI'
            },
            dataType: 'json',
            async: true,

            beforeSend: function () {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                // console.log('data[llenarPausaSistema] :>> ', data);
                var select = '<select class="form-control selectClasificacion"  name="selectClasificacion[]"><option value="none">seleccionar</option><option value="0">Laboral</option><option value="1">Descanso</option></select>';
                var select2 = '<select class="form-control selectTipoProgramacion"  name="selectTipoProgramacion[]"><option value="0">Sin horario exacto</option></select>';
                $.each(data.tipoPausa, function(i, item) {

                    if(item.es_pausa_por_defecto || item.tipo_pausa == 1){

                        let btnDelete = '';
                        // Las pausas por defecto no pueden tener el boton de eliminar
                        if(!item.es_pausa_por_defecto){
                            btnDelete = `<td><button type="button" class="btn btn-danger" onclick="eliminarFilaPausaSistema(${item.id});"><i class="fa fa-trash"></i></button></td>`;
                        }

                        var fila = `
                            <tr class="selected" id="filaTipoDescanso${item.id}">
                                <td class="hidden"><input type="hidden" name="idTipoDescanso[]" id="idTipoDescanso${item.id}" value="${item.id}" disabled></input></td>
                                <td><input name="pausa[]" id="pausa${item.id}" type="text" class="form-control nombrePausa" value="${item.tipo}" disabled></td>
                                <td>
                                    <select class="form-control selectClasificacion" name="selectClasificacion[]" disabled>
                                        <option value="none">seleccionar</option>
                                        <option value="0">Laboral</option>
                                        <option value="1">Descanso</option>
                                    </select>
                                </td>
                                <td> 
                                    <select class="form-control selectTipoProgramacion" name="selectTipoProgramacion[]" disabled>
                                        <option value="1">Con horario exacto</option>
                                    </select>
                                    <input type="hidden" value="${item.id}"></input>
                                </td>
                                <td hidden><input name="horaInicioDefecto[]" id="horaInicioDefecto${item.id}" type="text" class="form-control" value="${item.hora_inicial_por_defecto}" disabled></td>
                                <td hidden><input name="horaFinalDefecto[]" id="horaFinalDefecto${item.id}" type="text" class="form-control" value="${item.hora_final_por_defecto}" disabled></td>
                                ${btnDelete}
                            </tr>
                        `;
                    }else{
                        
                        var fila = '<tr class="selected" id="filaTipoDescanso' + item.id + '">' +
                            '<td class="hidden"><input type="hidden" name="idTipoDescanso[]" id="idTipoDescanso' + item.id + '" value="' + item.id + '"></input></td>' +
                            '<td><input name="pausa[]" id="pausa' + item.id + '" type="text"  class="form-control nombrePausa" value="' + item.tipo + '"></td>' +
                            '<td>' + select + '</td>' +
                            '<td>' + select2 + '<input type="hidden" value="' + item.id + '"></input></td>' +
                            '<td style="display:none;" hidden><input name="horaInicioDefecto[]" id="horaInicioDefecto' + item.id + '" type="text"  class="form-control " value="' + item.hora_inicial_por_defecto + '"></td>' +
                            '<td style="display:none;" hidden><input name="horaFinalDefecto[]" id="horaFinalDefecto' + item.id + '" type="text"  class="form-control" value="' + item.hora_final_por_defecto + '"></td>' +
                            '<td style="text-align:center;"><button type="button" class="btn btn-danger" onclick="eliminarFilaPausaSistema(' + item.id + ');"><i class="fa fa-trash"></i></button></td>' +
                            '<td hidden><input name="duracionMax[]" id="duracionMax' + item.id + '" type="number"  class="form-control" value="' + item.duracion_maxima + '"></td>' +
                            '<td hidden><input name="cantidadMax[]" id="cantidadMax' + item.id + '" type="number"  class="form-control" value="' + item.cantidad_maxima_evento_dia + '"></td>' +
                            '</tr>';
                    }

                    $("#pausaSistema").append(fila);
                    $("#filaTipoDescanso" + item.id + " .selectClasificacion").val(item.descanso).change();
                    $("#filaTipoDescanso" + item.id + " .selectTipoProgramacion").val(item.tipo_pausa).change();
                    $("#horaInicioDefecto" + item.id).timepicker(options);
                    $("#horaFinalDefecto" + item.id).timepicker(options);

                    if (item.tipo_pausa == '1') {
                        $("#duracionMax" + item.id).attr('readonly', 'readonly');
                        $("#cantidadMax" + item.id).attr('readonly', 'readonly');
                        $("#duracionMax" + item.id).val('');
                        $("#cantidadMax" + item.id).val('');
                    }
                    if (item.tipo_pausa == '0') {
                        $("#horaInicioDefecto" + item.id).attr('readonly', 'readonly');
                        $("#horaFinalDefecto" + item.id).attr('readonly', 'readonly');
                        $("#horaInicioDefecto" + item.id).val('');
                        $("#horaFinalDefecto" + item.id).val('');
                    }
                });


            },
            complete: function() {
                $.unblockUI()
            },

            error: function() {
                alertify.error("Se genero un error al procesar la solicitud");
            }
        });
    }

    function actualizaPausas() {

        var validoNombrePausa = 0;
        var validoSelectClasificacion = 0;
        var validoSelectTipoProgramacion = 0;

        $('#formPausas .nombrePausa').each(function() {

            if ($(this).val() == "") {
                valido = 1;
                validoNombrePausa = 1;
            }

        });
        $('#formPausas .selectClasificacion').each(function() {

            if ($(this).val() == "none") {
                validoSelectClasificacion = 1;
            }

        });
        $('#formPausas .selectTipoProgramacion').each(function() {

            if ($(this).val() == "none") {
                validoSelectTipoProgramacion = 1;
            }

        });


        if (validoNombrePausa == 0 && validoSelectClasificacion == 0 && validoSelectTipoProgramacion == 0) {



            //Se crean un array con los datos a enviar, apartir del formulario 
            var formData = new FormData($("#formPausas")[0]);


            $.ajax({
                url: '<?=$url_crud;?>?guardarDatosPausa=1',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    validarPausas(data)
                },
                error: function() {
                    alertify.error(`${data.mensaje} ${data.error}`);
                }
            });

        } else {
            if (validoNombrePausa == 1) {
                alertify.error('El nombre de las pausas no pueden quedar vacia');
            }
            if (validoSelectClasificacion == 1) {
                alertify.error('Verifica que las  clasificaciones hayan sido seleccionadas ');
            }
            if (validoSelectTipoProgramacion == 1) {
                alertify.error('Verifica que los tipo de programación hayan sido seleccionadas ');
            }

        }

    }

    const validarPausas = (data)  => {
        
        data = data.filter(item => item.status === false)
        if (data.length > 0) {
            data.forEach(item => {
                const input = document.getElementById(`pausa${item.idCampo}`)
                if (item.status === false) {
                    alertify.error(`${item.mensaje}. La pausa ${item.campo} no puede estar duplicado`);
                    let tr = input.closest("tr")
                    tr.setAttribute('class', 'has-error')
                }
            })

        } else {
            alertify.success(`Actualización realizada de manera exitosa`)
            llenarPausaSistema();
        }
    }

    function agregarFilaPausaSistema() {

        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                insertarDatosPausa: 'SI'
            },
            dataType: 'json',
            success: function(data) {

                var select = '<select class="form-control selectClasificacion"  name="selectClasificacion[]"><option value="none">seleccionar</option><option value="0">Laboral</option><option value="1">Descanso</option></select>';
                var select2 = '<select class="form-control selectTipoProgramacion"  name="selectTipoProgramacion[]"><option value="0">Sin horario exacto</option></select>';

                var fila = '<tr class="selected" id="filaTipoDescanso' + data.ultimoId + '">' +
                    '<td class="hidden"><input type="hidden" name="idTipoDescanso[]" id="idTipoDescanso' + data.ultimoId + '" value="' + data.ultimoId + '"></input></td>' +
                    '<td><input name="pausa[]" id="pausa' + data.ultimoId + '" type="text"  class="form-control nombrePausa" value=""></td>' +
                    '<td>' + select + '</td>' +
                    '<td>' + select2 + '</td>' +
                    '<td style="display:none;">' + select2 + '<input type="hidden" value="' + data.ultimoId + '"></input></td>' +
                    '<td style="display:none;"><input name="horaInicioDefecto[]" id="horaInicioDefecto' + data.ultimoId + '"  class="form-control " value=""></td>' +
                    '<td style="display:none;"><input  name="horaFinalDefecto[]" id="horaFinalDefecto' + data.ultimoId + '" class="form-control" value=""></td>' +
                    '<td style="text-align:center;"><button type="button" class="btn btn-danger"  onclick="eliminarFilaPausaSistema(' + data.ultimoId + ');"><i class="fa fa-trash"></i></button></td>' +
                    '<td hidden><input name="duracionMax[]" id="duracionMax' + data.ultimoId + '" type="number"  class="form-control" value=""></td>' +
                    '<td hidden><input name="cantidadMax[]" id="cantidadMax' + data.ultimoId + '" type="number"  class="form-control" value=""></td>' +
                    '</tr>';

                $("#pausaSistema").append(fila);
                $("#horaInicioDefecto" + data.ultimoId).timepicker(options);
                $("#horaFinalDefecto" + data.ultimoId).timepicker(options);

            }
        });
    }

    function abrirModalPausas() {
        $("#modalPausas").modal();
    }

    function llenarPausasPorDefecto(filasUsuPau, index) {

        $.each(filasUsuPau, function(i, item) {


            //console.log(item.USUPAU_HorIniLun_b);
            $("#breakHorIniLun" + index).val(resetearHorasCero(item.USUPAU_HorIniLun_b));
            $("#breakHorFinLun" + index).val(resetearHorasCero(item.USUPAU_HorFinLun_b));
            $("#breakHorIniMar" + index).val(resetearHorasCero(item.USUPAU_HorIniMar_b));
            $("#breakHorFinMar" + index).val(resetearHorasCero(item.USUPAU_HorFinMar_b));
            $("#breakHorIniMie" + index).val(resetearHorasCero(item.USUPAU_HorIniMie_b));
            $("#breakHorFinMie" + index).val(resetearHorasCero(item.USUPAU_HorFinMie_b));
            $("#breakHorIniJue" + index).val(resetearHorasCero(item.USUPAU_HorIniJue_b));
            $("#breakHorFinJue" + index).val(resetearHorasCero(item.USUPAU_HorFinJue_b));
            $("#breakHorIniVie" + index).val(resetearHorasCero(item.USUPAU_HorIniVie_b));
            $("#breakHorFinVie" + index).val(resetearHorasCero(item.USUPAU_HorFinVie_b));
            $("#breakHorIniSab" + index).val(resetearHorasCero(item.USUPAU_HorIniSab_b));
            $("#breakHorFinSab" + index).val(resetearHorasCero(item.USUPAU_HorFinSab_b));
            $("#breakHorIniDom" + index).val(resetearHorasCero(item.USUPAU_HorIniDom_b));
            $("#breakHorFinDom" + index).val(resetearHorasCero(item.USUPAU_HorFinDom_b));
            $("#breakHorIniFes" + index).val(resetearHorasCero(item.USUPAU_HorIniFes_b));
            $("#breakHorFinFes" + index).val(resetearHorasCero(item.USUPAU_HorFinFes_b));



        });
    }

    function resetearHorasCero(hora) {
        var valor = hora;
        if (valor == '00:00:00') {
            valor = '';
        }
        return valor;
    }

    /**YCY 2019-09-26
     * funcion que carga los diferentes tipos de pausa
     * @param1 obtiene el tipo de pausa 
     */
    function cargarPausas(tipo) {
        var pausa1 = '<?php echo $arrayTipoPausaDefecto1[0] ?>';
        var pausa2 = '<?php echo $arrayTipoPausaDefecto2[0] ?>';
        var pausa3 = '<?php echo $arrayTipoPausaDefecto3[0] ?>';
        var idUsuario = $("#hidId").val();

        $.ajax({
            url: '<?=$url_crud;?>?cargarPausas=si',
            type: 'POST',
            data: {
                'pausa1': pausa1,
                'pausa2': pausa2,
                'pausa3': pausa3,
                'idUsuario': idUsuario,
                'tipo': tipo
            },
            dataType: 'json',
            success: function(data) {
                if (tipo == 1) {
                    llenarFilasPausaTipo1(data.datosUsuPau1, data.tipoPausas, tipo);
                }
                if (tipo == 0) {
                    llenarFilasPausaTipo0(data.datosUsuPau0, data.tipoPausas, tipo);
                }



            },
            error: function() {
                alertify.error('Ha ocurrido un error de red');
            }
        });


    }

    /** Breiner San 2019-12-10
     * La funcion se encarga de mostrar la lista de permisos de backoffice y calidad
     * dependiendo del usuario seleccionado    
     */
    function cargarListaPermisosBOC() {
        var idHuesped = <?php echo $_SESSION['HUESPED'] ?>;
        var idUsuario = $("#hidId").val();

        $.ajax({
            url: '<?=$url_crud;?>?cargarPermisosBOC=si',
            type: 'POST',
            data: {
                'idUsuario': idUsuario,
                'idHuesped': idHuesped
            },
            dataType: 'json',
            success: function(data) {

                var fila = '';
                $.each(data.arrPEOBUS, function(key, value) {

                    var idFormulario = value.PEOBUS_ConsInte__GUION__b;
                    var opciones = '';

                    $.each(data.arrFormulario, function(key, value) {
                        opciones += '<option value="' + value.id + '" ' + ((value.id == idFormulario) ? "selected" : "") + '>' + value.nombre + '</option>';
                    });

                    fila += '<tr>' +
                        '<input type="hidden" value="' + value.PEOBUS_ConsInte__b + '" name="permisoId[]">' +
                        '<td>' +
                        '<select disabled style="width:355px;" name="permisoFormulario[]" class="form-control permisoForularioRequerido">' +
                        opciones +
                        '</select>' +
                        '</td>' +
                        '<td>' +
                        '<input disabled type="checkbox" value="' + value.PEOBUS_ConsInte__b + '" name="permisoVer[]" ' + ((value.PEOBUS_VeRegPro__b == -1) ? "checked" : "") + ' >' +
                        '</td>' +
                        '<td>' +
                        '<input disabled type="checkbox" value="' + value.PEOBUS_ConsInte__b + '" name="permisoEditar[]" ' + ((value.PEOBUS_Escritur__b == -1) ? "checked" : "") + '>' +
                        '</td>' +
                        '<td>' +
                        '<input disabled type="checkbox" value="' + value.PEOBUS_ConsInte__b + '" name="permisoAgregar[]" ' + ((value.PEOBUS_Adiciona__b == -1) ? "checked" : "") + '>' +
                        '</td>' +
                        '<td>' +
                        '<input disabled type="checkbox" value="' + value.PEOBUS_ConsInte__b + '" name="permisoEliminar[]" ' + ((value.PEOBUS_Borrar____b == -1) ? "checked" : "") + '>' +
                        '</td>' +
                        '<td>' +
                        '<button disabled type="button" class="btn btn-danger btn-sm eliminarPermiso"><i class="fa fa-trash"></i></button>' +
                        '</td>' +
                        '</tr>';
                });

                $("#table-permisosBOC tbody").html(fila);
                $("#table-permisosBOC tbody select").select2();
            },
            error: function() {
                alertify.error('Ha ocurrido un error al traer los datos');
            }
        });
    }

    var contadorNuevaFila = 0;

    function agregarFilaPermisoBOC() {
        contadorNuevaFila += -1;
        var filaHtml = '<tr>' +
            '<input type="hidden" value="' + contadorNuevaFila + '" name="permisoId[]">' +
            '<td><select style="width:355px;" name="permisoFormulario[]" class="form-control permisoForularioRequerido">' +
            '<?php echo $opcionesFormularioGUION ?>' +
            '</select></td>' +
            '<td><input type="checkbox" value="' + contadorNuevaFila + '" name="permisoVer[]"></td>' +
            '<td><input type="checkbox" value="' + contadorNuevaFila + '" name="permisoEditar[]"></td>' +
            '<td><input type="checkbox" value="' + contadorNuevaFila + '" name="permisoAgregar[]"></td>' +
            '<td><input type="checkbox" value="' + contadorNuevaFila + '" name="permisoEliminar[]"></td>' +
            '<td>' +
            '<button type="button" class="btn btn-danger btn-sm eliminarPermiso"><i class="fa fa-trash"></i></button>' +
            '</td>' +
            '</tr>';
        $("#table-permisosBOC tbody").append(filaHtml);
        $("#table-permisosBOC tbody select").select2();

    }

    $("#table-permisosBOC tbody").on('click', '.eliminarPermiso', function() {
        $(this).closest('tr').remove();
    });

    function before_save() {}

    function after_save() {}

    function after_save_error() {}

    function before_edit() {}

    function after_edit() {}

    function before_add() {}

    function after_add() {}

    function before_delete() {}

    function after_delete() {}

    function after_delete_error() {}

</script>

<!-- Scripts para manejar los eventos de las tareas de campañas -->
<script>

    function cargarListaTarea(sentido){

        let huespedId = <?php echo $_SESSION['HUESPED'] ?>;
        let usuarioId = $("#hidId").val();

        let listaNoAsignadas = '';
        let listaAsignadas = '';
        
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php?traerTareas=true',
            type: 'POST',
            dataType: 'JSON',
            data: {huespedId: huespedId, usuarioId: usuarioId, sentido: sentido},
            beforeSend : function(){
                $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
            },
            success: function(res){

                // Lleno los campos de las campanas no asignadas
                if(res.campanasNoAsignadas && res.campanasNoAsignadas.length > 0){
                    res.campanasNoAsignadas.forEach(element => {

                        listaNoAsignadas += `
                            <li data-id="${element.campanId}">
                                <table class="table table-hover">
                                    <tr>
                                        <td width="40px">
                                            <input type="checkbox" class="flat-red mi-check">
                                        </td>
                                        <td class="nombre">
                                            ${element.estrategiaNombre} - ${element.campanNombre}
                                        </td>
                                    </tr>
                                </table>
                            </li>
                        `;
                    });
                }

                // Lleno los campos de las campanas asignadas
                if(res.campanasAsignadas && res.campanasAsignadas.length > 0){
                    res.campanasAsignadas.forEach((element, index) => {

                        let nombrePrioridad = (sentido == 'saliente') ? 'Orden' : 'Prioridad';
                        let prioridad = `${index + 1}`

                        let nombre = `${element.estrategiaNombre} - ${element.campanNombre} - ${nombrePrioridad}: ${prioridad}`;

                        if(element.prioridad > 0 && element.prioridad !== null){
                            nombre = `${element.estrategiaNombre} - ${element.campanNombre} - ${nombrePrioridad}: ${element.prioridad}`;
                            prioridad = element.prioridad
                        }

                        listaAsignadas += `
                            <li data-id="${element.campanId}">
                                <table class="table table-hover">
                                    <tr>
                                        <td width="40px">
                                            <input type="checkbox" class="flat-red mi-check">
                                        </td>
                                        <td class="nombre">
                                            ${nombre}
                                        </td>
                                    </tr>
                                </table>
                            </li>
                        `;
                        // console.log('element :>> ', element);
                        
                        sentido === 'saliente' ? aplicarPrioridadCampanasSalientes({sentido: sentido, prioridad: prioridad, arrCampanasSeleccionadas: [parseInt(element.campanId, 0)], usuarioId: usuarioId}) : null
                    });
                }

                if(sentido == 'saliente'){
                    $("#disponibleTareaSalientes").html(listaNoAsignadas);
                    $("#seleccionadoTareaSalientes").html(listaAsignadas);
                }

                if(sentido == 'entrante'){
                    $("#disponibleTareaEntrante").html(listaNoAsignadas);
                    $("#seleccionadoTareaEntrante").html(listaAsignadas);
                }
                

                $('input[type="checkbox"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-blue'
                });

            },
            error: function(res){
                console.log(res);
            },
            complete : function(){
                $.unblockUI();
            }
        });

    }

    function abrirModalPrioridadCampana(sentido){

        let frase = '';

        $("#prioridadCampana").val('1')

        if(sentido == 'entrante'){
            frase = 'Si por ejemplo un agente es prioridad 3 en una campaña entrante, significa que solo le van a llegar llamadas de esa campaña si todos los agentes que están en prioridad 1 y 2 para esa campaña, están ocupados.';
            $("#sentidoPrioridadCampana").val('entrante');
        }

        $("#textoPrioridadCampana").html(frase);
        $('#modalPrioridadCampana').modal();
    }

    function aplicarPrioridadesCampana(){

        let sentido = $("#sentidoPrioridadCampana").val();
        let prioridad = $("#prioridadCampana").val();
        let usuarioId = $("#hidId").val();

        let valido = true;

        let arrCampanasSeleccionadas = [];

        // necesito traer las campanas seleccionadas
        if(sentido == 'saliente'){
            var obj = $("#seleccionadoTareaSalientes .seleccionado");
        }

        if(sentido == 'entrante'){
            var obj = $("#seleccionadoTareaEntrante .seleccionado");
        }

        obj.each(function (key, value){
            arrCampanasSeleccionadas[key] = $(value).data("id");
        });

        if(arrCampanasSeleccionadas.length <= 0){
            valido = false;
            alertify.warning("Para cambiar el orden debe seleccionar las campañas que desea modificar");
        }

        console.log('arrCampanasSeleccionadas :>> ', {sentido: sentido, prioridad: prioridad, arrCampanasSeleccionadas: arrCampanasSeleccionadas, usuarioId: usuarioId});

        if(valido){
            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php?guardarPrioridades=true',
                type: 'POST',
                dataType: 'JSON',
                data: {sentido: sentido, prioridad: prioridad, arrCampanasSeleccionadas: arrCampanasSeleccionadas, usuarioId: usuarioId},
                beforeSend : function(){
                    $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                },
                success: function(res){
    
                    if(res.estado){

                        sentido === 'entrante' ? cargarListaTarea('entrante') : null

                        alertify.success("Se ha actualizado correctamente las prioridades de las campañas seleccionadas");
                        $('#modalPrioridadCampana').modal('hide');
                    }else{
                        alertify.error(res.msgError);
                    }
                },
                error: function(res){
                    console.log(res);
                    alertify("Se presento un error al guardar la informacion " + res);
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        }
    }


    const aplicarPrioridadCampanasSalientes = (data) => {
        
        // console.log('data :>> ', data);

            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php?guardarPrioridades=true',
                type: 'POST',
                data: data,
                dataType: 'JSON',
                async: true,

                beforeSend : function(){
                    $.blockUI({
                        baseZ : 2000, 
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' 
                    });
                },
                success: function(res){
                    
                    res.estado === true ? null : alertify.error(res.msgError);

                },
                complete : function(){
                    $.unblockUI();
                },
                error: function(res){
                    console.log(res);
                    alertify.error(`Se presento un error al guardar la informacion ${res}`);
                }
            });
        }


    $(function(){

        $(".cerrar-modal-prioridad-campana").on('click', function(){
            $('#modalPrioridadCampana').modal('hide');
        });

        // -------- Eventos drag and drop campañas salientes --------

        const calcularOrden = (strCadena, index) => {
            strCadena = strCadena.split(":")[0];
            strCadena = `${strCadena}: ${index}`;
            console.log(strCadena);
            
            return strCadena
        }

        // En esta funcion se encontrara el buscador que filtrara por el nombre 
        $('#buscadorDisponibleTareaSaliente, #buscadorSeleccionadoTareaSaliente').keyup(function(){
            var tipoBuscador = $(this).attr('id');
            var nombres = '';

            if(tipoBuscador == 'buscadorDisponibleTareaSaliente'){
                nombres = $('ul#disponibleTareaSalientes .nombre');
            }else if(tipoBuscador == 'buscadorSeleccionadoTareaSaliente'){
                nombres = $('ul#seleccionadoTareaSalientes .nombre');
            }

            var buscando = $(this).val();
            var item='';

            for (let i = 0; i < nombres.length; i++) {
                item = $(nombres[i]).html().toLowerCase();
                
                for (let x = 0; x < item.length; x++) {
                    if(buscando.length == 0 || item.indexOf(buscando) > -1 ){
                        $(nombres[i]).closest('li').show();
                    }else{
                        $(nombres[i]).closest('li').hide();
                        
                    }
                }
                
            }
        });

        /** Estas funciones se encargan del funcionamiento del drag & drop */
        $("#disponibleTareaSalientes").sortable({
            connectWith:"#seleccionadoTareaSalientes",
            start: function(event, ui) {
                const index = ui.item.index();
                const campanaId = ui.item.data('id');
                const usuarioId = $("#hidId").val();
                ui.item.data('items', ui.item.data('sortableItem').items)
                ui.item.data('usuarioId', usuarioId)
                ui.item.data('sentido', 'saliente')
                
                const itemOrder = $(this).sortable('toArray', {attribute: 'data-id'});

                console.log('event->start :>> ', ui.item.data('sortableItem'));
                console.log('event->start :>> ', ui.item.data());
                console.log('itemOrder->start :>> ', itemOrder);
            },
        });
        // $("#seleccionadoTareaSalientes").sortable({connectWith:"#disponibleTareaSalientes"}),
        
        $("#seleccionadoTareaSalientes").sortable({
            connectWith:"#disponibleTareaSalientes",
            
            start: function(event, ui) {
                const index = ui.item.index();
                const campanaId = ui.item.data('id');
                const usuarioId = $("#hidId").val();
                ui.item.data('items', ui.item.data('sortableItem').items)
                ui.item.data('usuarioId', usuarioId)
                ui.item.data('sentido', 'saliente')
                
                // const item = ui.item.data('id');
                

                
                // console.log('item->start :>> ', item);
                // console.log('index->start :>> ', index);

                console.log('event->start :>> ', ui.item.data('sortableItem'));
                // console.log('event->start :>> ', ui.item.data());
                // console.log('ui->start :>> ', ui);
            },
            // Agrego los valores al objecto data, para aplicar la prioridad
            update: function(event, ui) {
                const index = ui.item.index()
                const campanaId = ui.item.data('id')
                const items = ui.item.data('items')

                const itemOrder = $(this).sortable('toArray', {attribute: 'data-id'});


                items.forEach((item, index) => {
                    const campanas_id = parseInt(itemOrder[index] === undefined ? 0 : itemOrder[index], 0)
                    const itemsliSeleccionado = document.querySelector(`#seleccionadoTareaSalientes li:nth-child(${index + 1}) td.nombre`) 
                    const itemsliDisponible = document.querySelector(`#disponibleTareaSalientes li:nth-child(${ui.item.index() + 1}) td.nombre`)
                    
                    // console.log('update->item :>> ', item.item.context.dataset);
                    // console.log('update->index :>> ', index);
                    
                    const itemli = itemsliSeleccionado === null ? itemsliDisponible : itemsliSeleccionado

                    // console.log('itemli :>> ', itemli);

                    // console.table('index :>> ', index, 'itemli :>> ', itemli)
                    console.log('update->itemOrder :>> ', itemOrder[index]);
                    
                    itemli.innerText = calcularOrden(itemli.innerText, `${index + 1}`)
                    

                    if (campanas_id > 0) {
                        const data = {
                            sentido: ui.item.data('sentido'),
                            prioridad: `${index + 1}`,
                            arrCampanasSeleccionadas: [campanas_id],
                            usuarioId: ui.item.data('usuarioId')
                        }
                        console.log('update->data :>> ', data);
                        
                        aplicarPrioridadCampanasSalientes(data)
                    }
                
                });

                console.log('update->ui.item.data() :>> ', ui.item.data());
                // console.log('update->ui.item.data() :>> ', ui.item);
                // console.log('update->itemOrder :>> ', itemOrder);

                console.log('update->ui.item.data(items) :>> ', ui.item.data('items'));
                // console.log('update->index :>> ', index);
                console.log('\n\n --------------TERMINA update --------------  \n\n', );
                // console.log('event->start :>> ', event);
                // console.log('ui->start :>> ', ui);
            }
        });

        // Capturo el li cuando es puesto en la lista de disponible            
        $( "#disponibleTareaSalientes" ).on( "sortreceive", function( event, ui ) {
            let arrDisponible = [];
            arrDisponible[0] = ui.item.data("id");
            
            console.log('ui.item.data("id"); :>> ', ui.item.data("id")); 
            

            moverCampana(arrDisponible, "izquierda", "saliente");
        } );

        // Capturo el li cuando es puesto en la lista de  seleccionados         
        $( "#seleccionadoTareaSalientes" ).on( "sortreceive", function( event, ui ) {
            let arrSeleccionado = [];
            arrSeleccionado[0] = ui.item.data("id");

            moverCampana(arrSeleccionado, "derecha", "saliente");
        } );

        // Solo se selecciona el check cuando se clickea el li
        $("#disponibleTareaSalientes, #seleccionadoTareaSalientes").on('click', 'li', function(){
            $(this).find(".mi-check").iCheck('toggle');

            if($(this).find(".mi-check").is(':checked') ){
                $(this).addClass('seleccionado');
            }else{
                $(this).removeClass('seleccionado');
            }    
        });

        $("#disponibleTareaSalientes, #seleccionadoTareaSalientes").on('ifToggled', 'input', function(event){
            if($(this).is(':checked') ){
                $(this).closest('li').addClass('seleccionado');
            }else{
                $(this).closest('li').removeClass('seleccionado');
            }
        });

        // Envia los elementos seleccionados a la lista de la derecha
        $('#btnDerechaTareaSaliente').click(function(){
            var obj = $("#disponibleTareaSalientes .seleccionado");
            $('#seleccionadoTareaSalientes').append(obj);

            let arrSeleccionado = [];
            obj.each(function (key, value){
                arrSeleccionado[key] = $(value).data("id");
            });

            obj.removeClass('seleccionado');
            obj.find(".mi-check").iCheck('toggle');

            if(arrSeleccionado.length > 0){
                moverCampana(arrSeleccionado, "derecha", "saliente");
            }
        });

        // Envia los elementos seleccionados a la lista de la izquerda
        $('#btnIzquierdaTareaSaliente').click(function(){
            var obj = $("#seleccionadoTareaSalientes .seleccionado");
            $('#disponibleTareaSalientes').append(obj);

            let arrDisponible = [];
            obj.each(function (key, value){
                arrDisponible[key] = $(value).data("id");
            });

            obj.removeClass('seleccionado');
            obj.find(".mi-check").iCheck('toggle');

            if(arrDisponible.length > 0){
                moverCampana(arrDisponible, "izquierda", "saliente");
            }
        });

        // Envia todos los elementos a la derecha
        $('#btnTodoDerechaTareaSaliente').click(function(){
            var obj = $("#disponibleTareaSalientes li");
            $('#seleccionadoTareaSalientes').append(obj);

            let arrSeleccionado = [];
            obj.each(function (key, value){
                arrSeleccionado[key] = $(value).data("id");
            });
            
            moverCampana(arrSeleccionado, "derecha", "saliente");
        });

        // Envia todos los elementos a la izquerda
        $('#btnTodoIzquierdaTareaSaliente').click(function(){
            var obj = $("#seleccionadoTareaSalientes li");
            $('#disponibleTareaSalientes').append(obj);

            let arrDisponible = [];
            obj.each(function (key, value){
                arrDisponible[key] = $(value).data("id");
            });
            
            moverCampana(arrDisponible, "izquierda", "saliente");
        });

        // -------- Eventos drag and drop campañas entrantes --------

        // En esta funcion se encontrara el buscador que filtrara por el nombre 
        $('#buscadorDisponibleTareaEntrante, #buscadorSeleccionadoTareaEntrante').keyup(function(){
            var tipoBuscador = $(this).attr('id');
            var nombres = '';

            if(tipoBuscador == 'buscadorDisponibleTareaEntrante'){
                nombres = $('ul#disponibleTareaEntrante .nombre');
            }else if(tipoBuscador == 'buscadorSeleccionadoTareaEntrante'){
                nombres = $('ul#seleccionadoTareaEntrante .nombre');
            }

            var buscando = $(this).val();
            var item='';

            for (let i = 0; i < nombres.length; i++) {
                item = $(nombres[i]).html().toLowerCase();
                
                for (let x = 0; x < item.length; x++) {
                    if(buscando.length == 0 || item.indexOf(buscando) > -1 ){
                        $(nombres[i]).closest('li').show();
                    }else{
                        $(nombres[i]).closest('li').hide();
                        
                    }
                }
                
            }
        });

        /** Estas funciones se encargan del funcionamiento del drag & drop */
        $("#disponibleTareaEntrante").sortable({connectWith:"#seleccionadoTareaEntrante"});
        $("#seleccionadoTareaEntrante").sortable({connectWith:"#disponibleTareaEntrante"});

        // Capturo el li cuando es puesto en la lista de disponible            
        $( "#disponibleTareaEntrante" ).on( "sortreceive", function( event, ui ) {
            let arrDisponible = [];
            arrDisponible[0] = ui.item.data("id");

            moverCampana(arrDisponible, "izquierda", "entrante");
        } );

        // Capturo el li cuando es puesto en la lista de  seleccionados         
        $( "#seleccionadoTareaEntrante" ).on( "sortreceive", function( event, ui ) {
            let arrSeleccionado = [];
            arrSeleccionado[0] = ui.item.data("id");

            moverCampana(arrSeleccionado, "derecha", "entrante");
        } );

        // Solo se selecciona el check cuando se clickea el li
        $("#disponibleTareaEntrante, #seleccionadoTareaEntrante").on('click', 'li', function(){
            $(this).find(".mi-check").iCheck('toggle');

            if($(this).find(".mi-check").is(':checked') ){
                $(this).addClass('seleccionado');
            }else{
                $(this).removeClass('seleccionado');
            }    
        });

        $("#disponibleTareaEntrante, #seleccionadoTareaEntrante").on('ifToggled', 'input', function(event){
            if($(this).is(':checked') ){
                $(this).closest('li').addClass('seleccionado');
            }else{
                $(this).closest('li').removeClass('seleccionado');
            }
        });

        // Envia los elementos seleccionados a la lista de la derecha
        $('#btnDerechaTareaEntrante').click(function(){
            var obj = $("#disponibleTareaEntrante .seleccionado");
            $('#seleccionadoTareaEntrante').append(obj);

            let arrSeleccionado = [];
            obj.each(function (key, value){
                arrSeleccionado[key] = $(value).data("id");
            });

            obj.removeClass('seleccionado');
            obj.find(".mi-check").iCheck('toggle');

            if(arrSeleccionado.length > 0){
                moverCampana(arrSeleccionado, "derecha", "entrante");
            }
        });

        // Envia los elementos seleccionados a la lista de la izquerda
        $('#btnIzquierdaTareaEntrante').click(function(){
            var obj = $("#seleccionadoTareaEntrante .seleccionado");
            $('#disponibleTareaEntrante').append(obj);

            let arrDisponible = [];
            obj.each(function (key, value){
                arrDisponible[key] = $(value).data("id");
            });

            obj.removeClass('seleccionado');
            obj.find(".mi-check").iCheck('toggle');

            if(arrDisponible.length > 0){
                moverCampana(arrDisponible, "izquierda", "entrante");
            }
        });

        // Envia todos los elementos a la derecha
        $('#btnTodoDerechaTareaEntrante').click(function(){
            var obj = $("#disponibleTareaEntrante li");
            $('#seleccionadoTareaEntrante').append(obj);

            let arrSeleccionado = [];
            obj.each(function (key, value){
                arrSeleccionado[key] = $(value).data("id");
            });
            
            moverCampana(arrSeleccionado, "derecha", "entrante");
        });

        // Envia todos los elementos a la izquerda
        $('#btnTodoIzquierdaTareaEntrante').click(function(){
            var obj = $("#seleccionadoTareaEntrante li");
            $('#disponibleTareaEntrante').append(obj);

            let arrDisponible = [];
            obj.each(function (key, value){
                arrDisponible[key] = $(value).data("id");
            });
            
            moverCampana(arrDisponible, "izquierda", "entrante");
        });

    });

    // ejecuto el ajax para insertar los usuarios a la lista de la izquierda o derecha
    function moverCampana(arrCampanas, accion, sentido){
            
        let prioridad = $("#prioridadCampana").val();
        let usuarioId = $("#hidId").val();

        if(accion == 'derecha'){
            ruta = "agregarCampanasUsuario=true";
        }else if(accion = 'izquerda'){
            ruta = "quitarCampanasUsuario=true";
        }

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php?'+ruta,  
            type: 'POST',
            dataType : 'json',
            data: {arrCampanas : arrCampanas, sentido: sentido, prioridad: prioridad, usuarioId: usuarioId},
            success: function(response){
                if(response.estado){

                    if(sentido == 'saliente'){
                        cargarListaTarea('saliente');
                    }else{
                        cargarListaTarea('entrante');
                    }

                    if(accion == 'derecha'){
                        alertify.success("Se han agregado las campañas correctamente");
                    }else{
                        alertify.success("Se han desasociado las campañas correctamente");
                    }
                }else{
                    alertify.error(response.msgError);
                }
            },
            error: function(response){
                console.log(response);
            },
            beforeSend : function(){
                $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }

</script>

<!-- Script para manejar los eventos del drag & drog -->
<script>
$(document).ready(function(){
    // ejecuto el ajax para insertar los usuarios a la lista de la izquierda o derecha
    function moverUsuarios(arrUsuarios, accion) {

        let ruta = '';
        if (accion == 'derecha') {
            ruta = "?agregarAgentes=true";
        } else if (accion = 'izquerda') {
            ruta = "?quitarAgentes=true";
        }

        $.ajax({
            url: '<?=$url_crud;?>' + ruta,
            type: 'POST',
            dataType: 'json',
            data: {
                arrAgentes: arrUsuarios,
                idMalla: $("#idMalla").val()
            },
            success: function(response) {
                alertify.success("Mensaje: " + response.estado);
            },
            error: function(response) {
                console.log(response);
            },
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

    // En esta funcion se encontrara el buscador que filtrara por el nombre 
    $('#buscadorDisponible, #buscadorSeleccionado').keyup(function() {
        var tipoBuscador = $(this).attr('id');
        var nombres = '';

        if (tipoBuscador == 'buscadorDisponible') {
            nombres = $('ul#disponible .nombre');
        } else if (tipoBuscador == 'buscadorSeleccionado') {
            nombres = $('ul#seleccionado .nombre');
        }

        var buscando = $(this).val();
        var item = '';

        for (let i = 0; i < nombres.length; i++) {
            item = $(nombres[i]).html().toLowerCase();

            for (let x = 0; x < item.length; x++) {
                if (buscando.length == 0 || item.indexOf(buscando) > -1) {
                    $(nombres[i]).closest('li').show();
                } else {
                    $(nombres[i]).closest('li').hide();

                }
            }

        }
    });
    /** Estas funciones se encargan del funcionamiento del drag & drop */
    $("#disponible").sortable({
        connectWith: "#seleccionado"
    });
    $("#seleccionado").sortable({
        connectWith: "#disponible"
    });

    // Capturo el li cuando es puesto en la lista de usuarios disponible            
    $("#disponible").on("sortreceive", function(event, ui) {
        let arrDisponible = [];
        arrDisponible[0] = ui.item.data("id");

        moverUsuarios(arrDisponible, "izquierda");
    });

    // Capturo el li cuando es puesto en la lista de usuarios seleccionados         
    $("#seleccionado").on("sortreceive", function(event, ui) {
        let arrSeleccionado = [];
        arrSeleccionado[0] = ui.item.data("id");

        moverUsuarios(arrSeleccionado, "derecha");
    });

    // Solo se selecciona el check cuando se clickea el li
    $("#disponible, #seleccionado").on('click', 'li', function() {
        $(this).find(".mi-check").iCheck('toggle');

        if ($(this).find(".mi-check").is(':checked')) {
            $(this).addClass('seleccionado');
        } else {
            $(this).removeClass('seleccionado');
        }

    });

    $("#disponible, #seleccionado").on('ifToggled', 'input', function(event) {
        if ($(this).is(':checked')) {
            $(this).closest('li').addClass('seleccionado');
        } else {
            $(this).closest('li').removeClass('seleccionado');
        }
    });

    // Envia los elementos seleccionados a la lista de la derecha
    $('#derecha').click(function() {
        var obj = $("#disponible .seleccionado");
        $('#seleccionado').append(obj);

        let arrSeleccionado = [];
        obj.each(function(key, value) {
            arrSeleccionado[key] = $(value).data("id");
        });

        obj.removeClass('seleccionado');
        obj.find(".mi-check").iCheck('toggle');

        if (arrSeleccionado.length > 0) {
            moverUsuarios(arrSeleccionado, "derecha");
        }

    });

    // Envia los elementos seleccionados a la lista de la izquerda
    $('#izquierda').click(function() {
        var obj = $("#seleccionado .seleccionado");
        $('#disponible').append(obj);

        let arrDisponible = [];
        obj.each(function(key, value) {
            arrDisponible[key] = $(value).data("id");
        });

        obj.removeClass('seleccionado');
        obj.find(".mi-check").iCheck('toggle');

        if (arrDisponible.length > 0) {
            moverUsuarios(arrDisponible, "izquierda");
        }
    });

    // Envia todos los elementos a la derecha
    $('#todoDerecha').click(function() {
        var obj = $("#disponible li");
        $('#seleccionado').append(obj);

        let arrSeleccionado = [];
        obj.each(function(key, value) {
            arrSeleccionado[key] = $(value).data("id");
        });

        moverUsuarios(arrSeleccionado, "derecha");
    });

    // Envia todos los elementos a la izquerda
    $('#todoIzquierda').click(function() {
        var obj = $("#seleccionado li");
        $('#disponible').append(obj);

        let arrDisponible = [];
        obj.each(function(key, value) {
            arrDisponible[key] = $(value).data("id");
        });

        moverUsuarios(arrDisponible, "izquierda");
    });    
});

</script>
