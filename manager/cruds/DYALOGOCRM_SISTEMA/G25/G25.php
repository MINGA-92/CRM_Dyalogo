
<style type="text/css">
    .embed-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
    }
    .embed-container iframe {
        position: absolute;
        top:0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width:95%; margin-top: 10px;">
        <div class="modal-content">
            <div class="modal-header" style="padding-right: 5px; padding-bottom: 3px; padding-top: 3px;">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body embed-container">
                <iframe id="frameContenedor" src=""  marginheight="0" marginwidth="0" noresize  frameborder="0">
                  
                </iframe>
            </div>
        </div>
    </div>
</div>

<?php
   //SECCION : Definicion urls
   $url_crud = "formularios/G25/G25_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN


    $Zsql = "SELECT G25_ConsInte__b as id, G25_C249 as camp1 , b.LISOPC_Nombre____b as camp2 FROM ".$BaseDatos.".G25  LEFT JOIN ".$BaseDatos_systema.".LISOPC as b ON b.LISOPC_ConsInte__b = G25_C250 ORDER BY G25_ConsInte__b DESC LIMIT 0, 50";
    

   $result = $mysqli->query($Zsql);

?>

<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li></li>
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
                        <br/>
                        <!-- FIN BUSQUEDA EN LA LISTA DE NAVEGACION-->
                            
                        <!-- LISTA DE NAVEGACION -->
                        <div class="table-responsive no-padding" id="txtPruebas" style="height:553px; overflow-x:hidden; overflow-y:scroll;">
                            <table class="table table-hover" id="tablaScroll">
                                
                                <?php
                 
                                    while($obj = $result->fetch_object()){
                                        echo "<tr class='CargarDatos' id='".$obj->id."'>
                                                <td>
                                                    <p style='font-size:14px;'><b>".strtoupper(($obj->camp1))."</b></p>
                                                    <p style='font-size:12px; margin-top:-10px;'>".($obj->camp2)."</p>
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
                            <button class="btn btn-default"  id="delete" >
                                <i class="fa fa-trash"></i> 
                            </button>
                            <button class="btn btn-default" id="edit" >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-default" id="Save" disabled>
                                <i class="fa fa-save"></i>
                            </button>
                            <button class="btn btn-default" id="cancel" disabled>
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
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
                        <!-- FIN BOTONES -->
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <br/>
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                                <div class="row">
                                    <div class="col-md-12">

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            DATOS BÁSICOS
        </h4>
    </div>
    <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G25_C249" id="LblG25_C249">NOMBRE</label>
                        <input type="text" disabled class="form-control input-sm" id="G25_C249" value=""  name="G25_C249"  placeholder="NOMBRE">
                    </div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div>


            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G25_C250" id="LblG25_C250">TIPO</label>
                        <select disabled class="form-control input-sm str_Select2"  style="width: 100%;" name="G25_C250" id="G25_C250">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 46";

                                $obj = $mysqli->query($str_Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C251" id="LblG25_C251">HUESPED</label>
                        <input type="text" disabled class="form-control input-sm Numerico" value=""  name="G25_C251" id="G25_C251" placeholder="HUESPED">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>


            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C252" id="LblG25_C252">BASE DE DATOS</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C252" id="G25_C252">
                            <option>NOMBRE</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C253" id="LblG25_C253">SCRIPT</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C253" id="G25_C253">
                            <option>NOMBRE</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C254" id="LblG25_C254">TIPIFICACION TIME OUT</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C254" id="G25_C254">
                            <option>NOMBRE</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G12_C96)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C255" id="LblG25_C255">TIPIFICACION ERRADA</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C255" id="G25_C255">
                            <option>NOMBRE</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G12_C96)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


        </div>


    </div>
</div>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            CONFIGURACION BUSQUEDA
        </h4>
    </div>
    <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C257" id="LblG25_C257">Dato capturado en el IVR 1</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C257" id="G25_C257">
                            <option>TEXTO</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C258" id="LblG25_C258">Dato capturado en el IVR 2</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C258" id="G25_C258">
                            <option>TEXTO</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C259" id="LblG25_C259">Dato capturado en el IVR 3</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C259" id="G25_C259">
                            <option>TEXTO</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C260" id="LblG25_C260">Dato capturado en el IVR 4</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C260" id="G25_C260">
                            <option>TEXTO</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C261" id="LblG25_C261">Dato capturado en el IVR 5</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C261" id="G25_C261">
                            <option>TEXTO</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


        </div>


    </div>
</div>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            METAS, REPORTES y ALARMAS
        </h4>
    </div>
    <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C262" id="LblG25_C262">Meta Nivel de Servicio</label>
                        <input type="text" disabled class="form-control input-sm Numerico" value=""  name="G25_C262" id="G25_C262" placeholder="Meta Nivel de Servicio">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C263" id="LblG25_C263">Tiempo Nivel de Servicio</label>
                        <input type="text" disabled class="form-control input-sm Numerico" value=""  name="G25_C263" id="G25_C263" placeholder="Tiempo Nivel de Servicio">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C264" id="LblG25_C264">Tiempo mínimo para considerarse abandono</label>
                        <input type="text" disabled class="form-control input-sm Numerico" value=""  name="G25_C264" id="G25_C264" placeholder="Tiempo mínimo para considerarse abandono">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C265" id="LblG25_C265">TMO mínimo</label>
                        <input type="text" disabled class="form-control input-sm Numerico" value=""  name="G25_C265" id="G25_C265" placeholder="TMO mínimo">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C266" id="LblG25_C266">TMO máximo</label>
                        <input type="text" disabled class="form-control input-sm Numerico" value=""  name="G25_C266" id="G25_C266" placeholder="TMO máximo">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>


        </div>


    </div>
</div>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            REPORTES
        </h4>
    </div>
    <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                    <div class="form-group">
                        <label for="G25_C267" id="LblG25_C267">Enviar reportes automáticamente</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"  disabled value="1" name="G25_C267" id="G25_C267" data-error="Before you wreck yourself"  > 
                            </label>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO SI/NO -->
  
            </div>


            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIMEPICKER -->
                    <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C268" id="LblG25_C268">A que hora se envía</label>
                        <div class="input-group">
                            <input type="text" disabled class="form-control input-sm Hora"  name="G25_C268" id="G25_C268" placeholder="HH:MM:SS" >
                            <div class="input-group-addon" id="TMP_G25_C268">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <!-- /.input group -->
                    </div>
                    <!-- /.form group -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIPO MEMO -->
                    <div class="form-group">
                        <label for="G25_C269" id="LblG25_C269">Quienes lo reciben</label>
                        <textarea disabled class="form-control input-sm" name="G25_C269" id="G25_C269"  value="" placeholder="Quienes lo reciben"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO MEMO -->
  
            </div>


        </div>


    </div>
</div>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            Mientras el cliente espera
        </h4>
    </div>
    <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G26_ConsInte__b as id , G26_C270 FROM ".$BaseDatos_systema.".G26";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C272" id="LblG25_C272">Música de espera</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C272" id="G25_C272">
                            <option>NOMBRE</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G26_C270)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                    <div class="form-group">
                        <label for="G25_C273" id="LblG25_C273">Reproducir anuncios al cliente</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"  disabled value="1" name="G25_C273" id="G25_C273" data-error="Before you wreck yourself"  > 
                            </label>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO SI/NO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C274" id="LblG25_C274">Cada cuanto se reproducirán los anuncios</label>
                        <input type="text" disabled class="form-control input-sm Numerico" value=""  name="G25_C274" id="G25_C274" placeholder="Cada cuanto se reproducirán los anuncios">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>


            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G26_ConsInte__b as id , G26_C270 FROM ".$BaseDatos_systema.".G26";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C275" id="LblG25_C275">Anuncios</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C275" id="G25_C275">
                            <option>NOMBRE</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G26_C270)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                    <div class="form-group">
                        <label for="G25_C276" id="LblG25_C276">Decir la posición en la cola de espera</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"  disabled value="1" name="G25_C276" id="G25_C276" data-error="Before you wreck yourself"  > 
                            </label>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO SI/NO -->
  
            </div>


            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G25_C277" id="LblG25_C277">Opciones ofrecidas al cliente</label>
                        <select disabled class="form-control input-sm str_Select2"  style="width: 100%;" name="G25_C277" id="G25_C277">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 51";

                                $obj = $mysqli->query($str_Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G26_ConsInte__b as id , G26_C270 FROM ".$BaseDatos_systema.".G26";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C278" id="LblG25_C278">Audio Devolucion</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C278" id="G25_C278">
                            <option>NOMBRE</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G26_C270)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G27_ConsInte__b as id , G27_C280 FROM ".$BaseDatos_systema.".G27";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C279" id="LblG25_C279">Por donde salen las llamadas Devolucion</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C279" id="G25_C279">
                            <option>nombre</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G27_C280)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G28_ConsInte__b as id , G28_C283 FROM ".$BaseDatos_systema.".G28";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C285" id="LblG25_C285">IVR de acciones disponibles mientras se espera</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C285" id="G25_C285">
                            <option>nombre</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G28_C283)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G25_C286" id="LblG25_C286">Acción desborde</label>
                        <select disabled class="form-control input-sm str_Select2"  style="width: 100%;" name="G25_C286" id="G25_C286">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 52";

                                $obj = $mysqli->query($str_Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G29_ConsInte__b as id , G29_C287 FROM ".$BaseDatos_systema.".G29";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C289" id="LblG25_C289">Cola de ACD desborde</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C289" id="G25_C289">
                            <option>nombre</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G29_C287)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G28_ConsInte__b as id , G28_C283 FROM ".$BaseDatos_systema.".G28";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C290" id="LblG25_C290">IVR desborde</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C290" id="G25_C290">
                            <option>nombre</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G28_C283)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C291" id="LblG25_C291">Tiempo desborde</label>
                        <input type="text" disabled class="form-control input-sm Numerico" value=""  name="G25_C291" id="G25_C291" placeholder="Tiempo desborde">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>


        </div>


    </div>
</div>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            Al colgar la llamada
        </h4>
    </div>
    <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G30_ConsInte__b as id , G30_C293 FROM ".$BaseDatos_systema.".G30";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C292" id="LblG25_C292">Encuesta</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C292" id="G25_C292">
                            <option>nombre</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G30_C293)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                    <div class="form-group">
                        <label for="G25_C295" id="LblG25_C295">Devolver la llamada Abandonada</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"  disabled value="1" name="G25_C295" id="G25_C295" data-error="Before you wreck yourself"  > 
                            </label>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO SI/NO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G27_ConsInte__b as id , G27_C280 FROM ".$BaseDatos_systema.".G27";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C296" id="LblG25_C296">Por donde salen las llamadas</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C296" id="G25_C296">
                            <option>nombre</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G27_C280)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


        </div>


    </div>
</div>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            AVANZADO
        </h4>
    </div>
    <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G25_C297" id="LblG25_C297">Peso</label>
                        <input type="text" disabled class="form-control input-sm Numerico" value=""  name="G25_C297" id="G25_C297" placeholder="Peso">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>


            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                    <div class="form-group">
                        <label for="G25_C298" id="LblG25_C298">Contestar llamada automáticamente</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"  disabled value="1" name="G25_C298" id="G25_C298" data-error="Before you wreck yourself"  > 
                            </label>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO SI/NO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <?php 
                    $str_Lsql = "SELECT  G26_ConsInte__b as id , G26_C270 FROM ".$BaseDatos_systema.".G26";
                    ?>
                    <!-- CAMPO DE TIPO GUION -->
                    <div class="form-group">
                        <label for="G25_C299" id="LblG25_C299">Audio anuncio agente</label>
                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G25_C299" id="G25_C299">
                            <option>NOMBRE</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                */
                                $combo = $mysqli->query($str_Lsql);
                                while($obj = $combo->fetch_object()){
                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G26_C270)."</option>";

                                }    
                                
                            ?>
                        </select>
                        

                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>


            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G25_C300" id="LblG25_C300">Estrategia de distribución</label>
                        <select disabled class="form-control input-sm str_Select2"  style="width: 100%;" name="G25_C300" id="G25_C300">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 53";

                                $obj = $mysqli->query($str_Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div>

  
        </div>


    </div>
</div>


<!-- SI ES MAESTRO - DETALLE CREO LAS TABS --> 

<hr/>
<div class="nav-tabs-custom">

    <ul class="nav nav-tabs">

        <li class="active">
            <a href="#tab_0" data-toggle="tab" id="tabs_click_0">ASOCIACION DATOS BD Y SCRIPT</a>
        </li>

        <li class="">
            <a href="#tab_1" data-toggle="tab" id="tabs_click_1">DEFINICION DE METAS</a>
        </li>

    </ul>


    <div class="tab-content">

        <div class="tab-pane active" id="tab_0"> 
            <table class="table table-hover table-bordered" id="tablaDatosDetalless0" width="100%">
            </table>
            <div id="pagerDetalles0">
            </div> 
            <button title="Crear ASOCIACION DATOS BD Y SCRIPT" class="btn btn-primary btn-sm llamadores" padre="'<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' " id="btnLlamar_0"><i class="fa fa-plus"></i></button>
        </div>

        <div class="tab-pane " id="tab_1"> 
            <table class="table table-hover table-bordered" id="tablaDatosDetalless1" width="100%">
            </table>
            <div id="pagerDetalles1">
            </div> 
            <button title="Crear DEFINICION DE METAS" class="btn btn-primary btn-sm llamadores" padre="'<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' " id="btnLlamar_1"><i class="fa fa-plus"></i></button>
        </div>

    </div>

</div>
<div class="modal fade-in" id="busquedaAvanzada_" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="botonCerradorModal">&times;</button>
                <h4 class="modal-title">Busqueda Avanzada</h4>
            </div>
            <div class="modal-body">

                <div class="row" id="buscador">
                    <div class="col-md-12">
                        <div class="row">
                            <form id="formBusquedaAquiLLave">
  
                        </form>
                    </div>
                </div>
                <div class="col-md-1">&nbsp;</div>
            </div>
            <div class="row" id="botones">
                <div class="col-xs-6">
                    <button class="btn btn-block btn-danger" id="btnCancelar" data-dismiss="modal"  type="button">Cancelar</button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-block btn-success" id="btnBuscar" type="button"><i class="fa fa-search"></i> Busqueda</button>
                </div>
            </div>
            <br/>
            <div class="row" id="resulados">
                <div class="col-md-12" id="resultadosBusqueda">
                </div>
            </div>

            </div>
        </div>
    </div>
</div>
<!-- SECCION : PAGINAS INCLUIDAS -->
<input type="hidden" name="id" id="hidId" value='0'>
                                <input type="hidden" name="oper" id="oper" value='add'>
                                <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
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


<script type="text/javascript">
    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
      $(function(){

        $.fn.datepicker.dates['es'] = {
            days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
            daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            today: "Today",
            clear: "Clear",
            format: "yyyy-mm-dd",
            titleFormat: "yyyy-mm-dd", 
            weekStart: 0
        };

        $("#usuarios").addClass('active');
        busqueda_lista_navegacion();
        $(".CargarDatos :first").click();
      
        $("#btnLlamadorAvanzado").click(function(){
            $('#busquedaAvanzada_ :input').each(function(){
                $(this).attr('disabled', false);
            });
        });

        

        $("#tablaScroll").on('scroll', function() {
            //alert('Si llegue');
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                
                $.post("<?=$url_crud;?>", { inicio : inicio, fin : fin , callDatosNuevamente : 'si' }, function(data){
                    if(data != ""){
                        $("#TablaIzquierda").append(data);
                        inicio += fin;
                        busqueda_lista_navegacion();
                    }
                });
            }
        });

        //SECCION FUNCIONALIDAD BOTONES

        //Funcionalidad del boton + , add
        $("#add").click(function(){

             //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);    

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);   
            $("#Save").attr('disabled', false);
            
           

            //Inializacion campos vacios por defecto
            $('#FormularioDatos :input').each(function(){
                if($(this).is(':checkbox')){
                    if($(this).is(':checked')){
                        $(this).attr('checked', false);
                    }
                    $(this).attr('disabled', false); 
                }else{
                    $(this).val('');
                    $(this).attr('disabled', false); 
                }
                               
            });

            $(".str_Select2").each(function(){
                $(this).val(0).change();
            });

            $("#hidId").val(0);
            $("#h3mio").html('');
            //Le informa al crud que la operaciòn a ejecutar es insertar registro
            document.getElementById('oper').value = "add";
            before_add();
           
        });

        jQuery.fn.reset = function () {
            $(this).each (function() { this.reset(); });
        }

        //funcionalidad del boton editar
        $("#edit").click(function(){

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
            $('#FormularioDatos :input').each(function(){
                $(this).attr('disabled', false);
            });

            before_edit();
          
        });

        //funcionalidad del boton seleccionar_registro
        $("#cancel").click(function(){
            //Se le envia como paraetro cero a la funcion seleccionar_registro
            seleccionar_registro(0);
            //Se inicializa el campo oper, nuevamente
            $("#oper").val(0);

            <?php if(isset($_GET['view'])){ ?>
                window.location.href  = "cancelado.php";
            <?php }  ?>
        });

        //funcionalidad del boton eliminar
        $("#delete").click(function(){
            //Se solicita confirmacion de la operacion, para asegurarse de que no sea por error
            alertify.confirm("¿Está seguro de eliminar el registro seleccionado?", function (e) {
                //Si la persona acepta
                if (e) {
                    var id = $("#hidId").val();
                    //se envian los datos, diciendo que la oper es "del"
                    $.ajax({
                        url      : '<?=$url_crud;?>?insertarDatosGrilla=si',
                        type     : 'POST',
                        data     : { id : id , oper : 'del'},
                        success  : function(data){
                            if(data == '1'){   
                                //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo                             
                                llenar_lista_navegacion('');
                            }else{
                                //Algo paso, hay un error
                                alert(data);
                            }
                        } 
                    });
                    
                } else {
                    
                }
            }); 
        });


        //datos Hoja de busqueda
        $("#BtnBusqueda_lista_navegacion").click(function(){
            //alert($("#table_search_lista_navegacion").val());
            llenar_lista_navegacion($("#table_search_lista_navegacion").val());
        });
        
        //Cajaj de texto de bus queda
        $("#table_search_lista_navegacion").keypress(function(e){
            if(e.keyCode == 13)
            {
                llenar_lista_navegacion($(this).val());
            }
        });

        //preguntar cuando esta vacia la tabla para dejar solo los botones correctos habilitados
        var g = $("#tablaScroll").html();
        if(g === ''){
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true); 
        }
    });


</script>

<script type="text/javascript" src="formularios/G25/G25_eventos.js"></script> 
<script type="text/javascript">
    $(function(){

        <?php if(isset($_GET['registroId'])){ ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos : 'SI', id : <?php echo $_GET['registroId']; ?> },
            dataType : 'json',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {
                    

                        $("#G25_C249").val(item.G25_C249);

                        $("#G25_C250").val(item.G25_C250);
 
                        $("#G25_C250").val(item.G25_C250).trigger("change"); 

                        $("#G25_C251").val(item.G25_C251);

                        $("#G25_C252").val(item.G25_C252);
 
                        $("#G25_C252").val(item.G25_C252).trigger("change"); 

                        $("#G25_C253").val(item.G25_C253);
 
                        $("#G25_C253").val(item.G25_C253).trigger("change"); 

                        $("#G25_C254").val(item.G25_C254);
 
                        $("#G25_C254").val(item.G25_C254).trigger("change"); 

                        $("#G25_C255").val(item.G25_C255);
 
                        $("#G25_C255").val(item.G25_C255).trigger("change"); 

                        $("#G25_C257").val(item.G25_C257);
 
                        $("#G25_C257").val(item.G25_C257).trigger("change"); 

                        $("#G25_C258").val(item.G25_C258);
 
                        $("#G25_C258").val(item.G25_C258).trigger("change"); 

                        $("#G25_C259").val(item.G25_C259);
 
                        $("#G25_C259").val(item.G25_C259).trigger("change"); 

                        $("#G25_C260").val(item.G25_C260);
 
                        $("#G25_C260").val(item.G25_C260).trigger("change"); 

                        $("#G25_C261").val(item.G25_C261);
 
                        $("#G25_C261").val(item.G25_C261).trigger("change"); 

                        $("#G25_C262").val(item.G25_C262);

                        $("#G25_C263").val(item.G25_C263);

                        $("#G25_C264").val(item.G25_C264);

                        $("#G25_C265").val(item.G25_C265);

                        $("#G25_C266").val(item.G25_C266);
    
                        if(item.G25_C267 == '1'){
                           if(!$("#G25_C267").is(':checked')){
                               $("#G25_C267").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C267").is(':checked')){
                               $("#G25_C267").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C268").val(item.G25_C268);

                        $("#G25_C269").val(item.G25_C269);

                        $("#G25_C272").val(item.G25_C272);
 
                        $("#G25_C272").val(item.G25_C272).trigger("change"); 
    
                        if(item.G25_C273 == '1'){
                           if(!$("#G25_C273").is(':checked')){
                               $("#G25_C273").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C273").is(':checked')){
                               $("#G25_C273").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C274").val(item.G25_C274);

                        $("#G25_C275").val(item.G25_C275);
 
                        $("#G25_C275").val(item.G25_C275).trigger("change"); 
    
                        if(item.G25_C276 == '1'){
                           if(!$("#G25_C276").is(':checked')){
                               $("#G25_C276").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C276").is(':checked')){
                               $("#G25_C276").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C277").val(item.G25_C277);
 
                        $("#G25_C277").val(item.G25_C277).trigger("change"); 

                        $("#G25_C278").val(item.G25_C278);
 
                        $("#G25_C278").val(item.G25_C278).trigger("change"); 

                        $("#G25_C279").val(item.G25_C279);
 
                        $("#G25_C279").val(item.G25_C279).trigger("change"); 

                        $("#G25_C285").val(item.G25_C285);
 
                        $("#G25_C285").val(item.G25_C285).trigger("change"); 

                        $("#G25_C286").val(item.G25_C286);
 
                        $("#G25_C286").val(item.G25_C286).trigger("change"); 

                        $("#G25_C289").val(item.G25_C289);
 
                        $("#G25_C289").val(item.G25_C289).trigger("change"); 

                        $("#G25_C290").val(item.G25_C290);
 
                        $("#G25_C290").val(item.G25_C290).trigger("change"); 

                        $("#G25_C291").val(item.G25_C291);

                        $("#G25_C292").val(item.G25_C292);
 
                        $("#G25_C292").val(item.G25_C292).trigger("change"); 
    
                        if(item.G25_C295 == '1'){
                           if(!$("#G25_C295").is(':checked')){
                               $("#G25_C295").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C295").is(':checked')){
                               $("#G25_C295").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C296").val(item.G25_C296);
 
                        $("#G25_C296").val(item.G25_C296).trigger("change"); 

                        $("#G25_C297").val(item.G25_C297);
    
                        if(item.G25_C298 == '1'){
                           if(!$("#G25_C298").is(':checked')){
                               $("#G25_C298").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C298").is(':checked')){
                               $("#G25_C298").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C299").val(item.G25_C299);
 
                        $("#G25_C299").val(item.G25_C299).trigger("change"); 

                        $("#G25_C300").val(item.G25_C300);
 
                        $("#G25_C300").val(item.G25_C300).trigger("change"); 
                    $("#h3mio").html(item.principal);
                    idTotal = <?php echo $_GET['registroId'];?>;

                    if ( $("#"+idTotal).length > 0) {
                        $("#"+idTotal).click();   
                        $("#"+idTotal).addClass('active'); 
                    }else{
                        //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                        $(".CargarDatos :first").click();
                    }
                });
                //Deshabilitar los campos

                //Habilitar todos los campos para edicion
                $('#FormularioDatos :input').each(function(){
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

        $("#hidId").val(<?php echo $_GET['registroId'];?>);

        $("#TxtFechaReintento").attr('disabled', true);
        $("#TxtHoraReintento").attr('disabled', true); 

        $("#btnLlamar_0").attr('padre', <?php echo $_GET['registroId'];?>);$("#btnLlamar_1").attr('padre', <?php echo $_GET['registroId'];?>);

        vamosRecargaLasGrillasPorfavor(<?php echo $_GET['registroId'];?>)
                        
        <?php } ?>

        //str_Select2 estos son los guiones
        


    $("#G25_C250").select2();

        $("#G25_C252").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C252").change(function(){
            var valores = $("#G25_C252 option:selected").text();
            var campos = $("#G25_C252 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C253").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C253").change(function(){
            var valores = $("#G25_C253 option:selected").text();
            var campos = $("#G25_C253 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C254").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C254").change(function(){
            var valores = $("#G25_C254 option:selected").text();
            var campos = $("#G25_C254 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C255").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C255").change(function(){
            var valores = $("#G25_C255 option:selected").text();
            var campos = $("#G25_C255 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C257").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C257").change(function(){
            var valores = $("#G25_C257 option:selected").text();
            var campos = $("#G25_C257 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C258").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C258").change(function(){
            var valores = $("#G25_C258 option:selected").text();
            var campos = $("#G25_C258 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C259").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C259").change(function(){
            var valores = $("#G25_C259 option:selected").text();
            var campos = $("#G25_C259 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C260").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C260").change(function(){
            var valores = $("#G25_C260 option:selected").text();
            var campos = $("#G25_C260 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C261").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C261").change(function(){
            var valores = $("#G25_C261 option:selected").text();
            var campos = $("#G25_C261 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C272").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C272").change(function(){
            var valores = $("#G25_C272 option:selected").text();
            var campos = $("#G25_C272 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C275").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C275").change(function(){
            var valores = $("#G25_C275 option:selected").text();
            var campos = $("#G25_C275 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

    $("#G25_C277").select2();

        $("#G25_C278").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C278").change(function(){
            var valores = $("#G25_C278 option:selected").text();
            var campos = $("#G25_C278 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C279").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C279").change(function(){
            var valores = $("#G25_C279 option:selected").text();
            var campos = $("#G25_C279 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C285").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C285").change(function(){
            var valores = $("#G25_C285 option:selected").text();
            var campos = $("#G25_C285 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

    $("#G25_C286").select2();

        $("#G25_C289").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C289").change(function(){
            var valores = $("#G25_C289 option:selected").text();
            var campos = $("#G25_C289 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C290").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C290").change(function(){
            var valores = $("#G25_C290 option:selected").text();
            var campos = $("#G25_C290 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C292").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C292").change(function(){
            var valores = $("#G25_C292 option:selected").text();
            var campos = $("#G25_C292 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C296").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C296").change(function(){
            var valores = $("#G25_C296 option:selected").text();
            var campos = $("#G25_C296 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G25_C299").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G25_C299").change(function(){
            var valores = $("#G25_C299 option:selected").text();
            var campos = $("#G25_C299 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

    $("#G25_C300").select2();

        //datepickers
        

        //Timepickers
        


        //Timepicker
        var options = {  //hh:mm 24 hour format only, defaults to current time
            timeFormat: 'HH:mm:ss',
            interval: 5,
            minTime: '10',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        }; 
        $("#G25_C268").timepicker(options);

        //Validaciones numeros Enteros
        

        $("#G25_C251").numeric();
        
        $("#G25_C262").numeric();
        
        $("#G25_C263").numeric();
        
        $("#G25_C264").numeric();
        
        $("#G25_C265").numeric();
        
        $("#G25_C266").numeric();
        
        $("#G25_C274").numeric();
        
        $("#G25_C291").numeric();
        
        $("#G25_C297").numeric();
        

        //Validaciones numeros Decimales
       

        
        
    $("#btnLlamadorAvanzado").click(function(){
        
        $("#resultadosBusqueda").html('');
    });

    $("#btnBuscar").click(function(){
        
        $.ajax({
            url         : 'formularios/G25/G25_Funciones_Busqueda_Manual.php?action=GET_DATOS',
            type        : 'post',
            dataType    : 'json',
            data        : {  },
            success     : function(datosq){
                //alert(datosq[0].cantidad_registros);
                if(datosq[0].cantidad_registros > 1){
                    var valores = null;
                    var tabla_a_mostrar = '<div class="box box-default">'+
                    '<div class="box-header">'+
                        '<h3 class="box-title">RESULTADOS DE LA BUSQUEDA</h3>'+
                    '</div>'+
                    '<div class="box-body">'+
                        '<table class="table table-hover table-bordered" style="width:100%;">';
                    tabla_a_mostrar += '<thead>';
                    tabla_a_mostrar += '<tr>';
                    tabla_a_mostrar += '  ';
                    tabla_a_mostrar += '</tr>';
                    tabla_a_mostrar += '</thead><tbody>';
                    tabla_a_mostrar += '<tbody>';
                    $.each(datosq[0].registros, function(i, item) {
                        tabla_a_mostrar += '<tr ConsInte="'+ item.G25_ConsInte__b +'" class="EditRegistro">';
                        tabla_a_mostrar += '';
                        tabla_a_mostrar += '</tr>';
                    });
                    tabla_a_mostrar += '</tbody>';
                    tabla_a_mostrar += '</table></div></div>';
                    
                    $("#resultadosBusqueda").html(tabla_a_mostrar);
                    
                    $(".EditRegistro").dblclick(function(){
                        var id = $(this).attr("ConsInte");
                        swal({
                            html : true,
                            title: "Información - Dyalogo CRM",
                            text: 'Esta seguro de editar este registro?',
                            type: "warning",
                            confirmButtonText: "Editar registro",
                            cancelButtonText : "No Editar registro",
                            showCancelButton : true,
                            closeOnConfirm : true
                        },
                            function(isconfirm){
                                if(isconfirm){
                                    seleccionar_registro_avanzada(id);
                                    
                                    $("#busquedaAvanzada_").modal('hide');
                                    $("#resultadosBusqueda").html('');
                                }else{

                                    swal("Cancelado", "No se editara este registro", "error");
                                }
                                
                            });
                        });
                }else if(datosq[0].cantidad_registros == 1){
                    var id = datosq[0].registros[0].G25_ConsInte__b;

                    seleccionar_registro_avanzada(id);
                    
                    $("#resultadosBusqueda").html('');
                    $("#busquedaAvanzada_").modal('hide');
                }else{
                    //console.log("LLego aqui");
                    swal({
                        html : true,
                        title: "Información - Dyalogo CRM",
                        text: 'No se encontraron datos, desea adicionar un registro?',
                        type: "warning",
                        confirmButtonText: "Adicionar registro",
                        cancelButtonText : "No adicionar registro",
                        showCancelButton : true,
                        closeOnConfirm : true
                    },
                    function(isconfirm){

                        if(isconfirm){
                            $("#add").click();
                            
                            
                            $("#resultadosBusqueda").html('');
                            $("#busquedaAvanzada_").modal('hide');
                           
                        }
                        else
                        {
                            
                            $("#resultadosBusqueda").html('');
                           
                            swal("Cancelado", "No se crearan nuevos registros", "error");
                        }
                        
                    });
                }
            }
        });
    });


        //Buton gurdar
        

        $("#Save").click(function(){
            var bol_respuesta = before_save();
            if(bol_respuesta){
                var form = $("#FormularioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#FormularioDatos")[0]);
                $.ajax({
                   url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data){
                            <?php if(!isset($_GET['campan'])){ ?>
                                //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                if($("#oper").val() == 'add'){
                                    idTotal = data;
                                }else{
                                    idTotal= $("#hidId").val();
                                }
                                $(".modalOculto").hide();

                                <?php if(isset($_GET['yourfather'])){ ?>
                                        window.location.href  = "resultadoExitoso.php";
                                <?php }  ?>
                                //Limpiar formulario
                                form[0].reset();
                                after_save();
                                <?php if(isset($_GET['registroId'])){ ?>
                                $.ajax({
                                    url      : '<?=$url_crud;?>',
                                    type     : 'POST',
                                    data     : { CallDatos : 'SI', id : <?php echo $_GET['registroId']; ?> },
                                    dataType : 'json',
                                    success  : function(data){
                                        //recorrer datos y enviarlos al formulario
                                        $.each(data, function(i, item) {
                                            
 
                                            $("#G25_C249").val(item.G25_C249);
 
                                            $("#G25_C250").val(item.G25_C250);
 
                                            $("#G25_C251").val(item.G25_C251);
 
                                            $("#G25_C252").val(item.G25_C252);

                                            $("#G25_C252").val(item.G25_C252).trigger("change"); 
 
                                            $("#G25_C253").val(item.G25_C253);

                                            $("#G25_C253").val(item.G25_C253).trigger("change"); 
 
                                            $("#G25_C254").val(item.G25_C254);

                                            $("#G25_C254").val(item.G25_C254).trigger("change"); 
 
                                            $("#G25_C255").val(item.G25_C255);

                                            $("#G25_C255").val(item.G25_C255).trigger("change"); 
 
                                            $("#G25_C257").val(item.G25_C257);

                                            $("#G25_C257").val(item.G25_C257).trigger("change"); 
 
                                            $("#G25_C258").val(item.G25_C258);

                                            $("#G25_C258").val(item.G25_C258).trigger("change"); 
 
                                            $("#G25_C259").val(item.G25_C259);

                                            $("#G25_C259").val(item.G25_C259).trigger("change"); 
 
                                            $("#G25_C260").val(item.G25_C260);

                                            $("#G25_C260").val(item.G25_C260).trigger("change"); 
 
                                            $("#G25_C261").val(item.G25_C261);

                                            $("#G25_C261").val(item.G25_C261).trigger("change"); 
 
                                            $("#G25_C262").val(item.G25_C262);
 
                                            $("#G25_C263").val(item.G25_C263);
 
                                            $("#G25_C264").val(item.G25_C264);
 
                                            $("#G25_C265").val(item.G25_C265);
 
                                            $("#G25_C266").val(item.G25_C266);
      
                                            if(item.G25_C267 == 1){
                                               $("#G25_C267").attr('checked', true);
                                            } 
 
                                            $("#G25_C268").val(item.G25_C268);
 
                                            $("#G25_C269").val(item.G25_C269);
 
                                            $("#G25_C272").val(item.G25_C272);

                                            $("#G25_C272").val(item.G25_C272).trigger("change"); 
      
                                            if(item.G25_C273 == 1){
                                               $("#G25_C273").attr('checked', true);
                                            } 
 
                                            $("#G25_C274").val(item.G25_C274);
 
                                            $("#G25_C275").val(item.G25_C275);

                                            $("#G25_C275").val(item.G25_C275).trigger("change"); 
      
                                            if(item.G25_C276 == 1){
                                               $("#G25_C276").attr('checked', true);
                                            } 
 
                                            $("#G25_C277").val(item.G25_C277);
 
                                            $("#G25_C278").val(item.G25_C278);

                                            $("#G25_C278").val(item.G25_C278).trigger("change"); 
 
                                            $("#G25_C279").val(item.G25_C279);

                                            $("#G25_C279").val(item.G25_C279).trigger("change"); 
 
                                            $("#G25_C285").val(item.G25_C285);

                                            $("#G25_C285").val(item.G25_C285).trigger("change"); 
 
                                            $("#G25_C286").val(item.G25_C286);
 
                                            $("#G25_C289").val(item.G25_C289);

                                            $("#G25_C289").val(item.G25_C289).trigger("change"); 
 
                                            $("#G25_C290").val(item.G25_C290);

                                            $("#G25_C290").val(item.G25_C290).trigger("change"); 
 
                                            $("#G25_C291").val(item.G25_C291);
 
                                            $("#G25_C292").val(item.G25_C292);

                                            $("#G25_C292").val(item.G25_C292).trigger("change"); 
      
                                            if(item.G25_C295 == 1){
                                               $("#G25_C295").attr('checked', true);
                                            } 
 
                                            $("#G25_C296").val(item.G25_C296);

                                            $("#G25_C296").val(item.G25_C296).trigger("change"); 
 
                                            $("#G25_C297").val(item.G25_C297);
      
                                            if(item.G25_C298 == 1){
                                               $("#G25_C298").attr('checked', true);
                                            } 
 
                                            $("#G25_C299").val(item.G25_C299);

                                            $("#G25_C299").val(item.G25_C299).trigger("change"); 
 
                                            $("#G25_C300").val(item.G25_C300);
                                            $("#h3mio").html(item.principal);
                                        });

                                        //Deshabilitar los campos

                                        //Habilitar todos los campos para edicion
                                        $('#FormularioDatos :input').each(function(){
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
                                })
                                $("#hidId").val(<?php echo $_GET['registroId'];?>);
                                <?php } else { ?>
                                    llenar_lista_navegacion('');
                                <?php } ?>   

                            <?php }else{ ?>
                               
                                
                
                            <?php } ?>        
                        }else{
                            //Algo paso, hay un error
                            alertify.error('Un error ha ocurrido');
                        }                
                    },
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                    }
                });
            }
        });
    });


    //SECCION  : Manipular Lista de Navegacion

    //buscar registro en la Lista de navegacion
    function llenar_lista_navegacion(x){
        var tr = '';
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatosJson : 'SI', Busqueda : x},
            dataType : 'json',
            success  : function(data){
                //Cargar la lista con los datos obtenidos en la consulta
                $.each(data, function(i, item) {
                    tr += "<tr class='CargarDatos' id='"+data[i].id+"'>";
                    tr += "<td>";
                    tr += "<p style='font-size:14px;'><b>"+data[i].camp1+"</b></p>";
                    tr += "<p style='font-size:12px; margin-top:-10px;'>"+data[i].camp2+"</p>";
                    tr += "</td>";
                    tr += "</tr>";
                });
                $("#tablaScroll").html(tr);
                //aplicar funcionalidad a la Lista de navegacion
                busqueda_lista_navegacion();

                //SI el Id existe, entonces le damos click,  para traer sis datos y le damos la clase activa
                if ( $("#"+idTotal).length > 0) {
                    $("#"+idTotal).click();   
                    $("#"+idTotal).addClass('active'); 
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
                $(this).removeClass('active');
            });
            
            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass('active');
              
              
            var id = $(this).attr('id');
            //buscar los datos
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);
                    $.each(data, function(i, item) {
                        

                    $.jgrid.gridUnload('#tablaDatosDetalless0'); 
                    cargarHijos_0(id);

                    $.jgrid.gridUnload('#tablaDatosDetalless1'); 
                    cargarHijos_1(id);

                        $("#G25_C249").val(item.G25_C249);

                        $("#G25_C250").val(item.G25_C250);
 
                        $("#G25_C250").val(item.G25_C250).trigger("change"); 

                        $("#G25_C251").val(item.G25_C251);

                        $("#G25_C252").val(item.G25_C252);
 
                        $("#G25_C252").val(item.G25_C252).trigger("change"); 

                        $("#G25_C253").val(item.G25_C253);
 
                        $("#G25_C253").val(item.G25_C253).trigger("change"); 

                        $("#G25_C254").val(item.G25_C254);
 
                        $("#G25_C254").val(item.G25_C254).trigger("change"); 

                        $("#G25_C255").val(item.G25_C255);
 
                        $("#G25_C255").val(item.G25_C255).trigger("change"); 

                        $("#G25_C257").val(item.G25_C257);
 
                        $("#G25_C257").val(item.G25_C257).trigger("change"); 

                        $("#G25_C258").val(item.G25_C258);
 
                        $("#G25_C258").val(item.G25_C258).trigger("change"); 

                        $("#G25_C259").val(item.G25_C259);
 
                        $("#G25_C259").val(item.G25_C259).trigger("change"); 

                        $("#G25_C260").val(item.G25_C260);
 
                        $("#G25_C260").val(item.G25_C260).trigger("change"); 

                        $("#G25_C261").val(item.G25_C261);
 
                        $("#G25_C261").val(item.G25_C261).trigger("change"); 

                        $("#G25_C262").val(item.G25_C262);

                        $("#G25_C263").val(item.G25_C263);

                        $("#G25_C264").val(item.G25_C264);

                        $("#G25_C265").val(item.G25_C265);

                        $("#G25_C266").val(item.G25_C266);
    
                        if(item.G25_C267 == '1'){
                           if(!$("#G25_C267").is(':checked')){
                               $("#G25_C267").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C267").is(':checked')){
                               $("#G25_C267").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C268").val(item.G25_C268);

                        $("#G25_C268").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G25_C268,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        

                        $("#G25_C269").val(item.G25_C269);

                        $("#G25_C272").val(item.G25_C272);
 
                        $("#G25_C272").val(item.G25_C272).trigger("change"); 
    
                        if(item.G25_C273 == '1'){
                           if(!$("#G25_C273").is(':checked')){
                               $("#G25_C273").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C273").is(':checked')){
                               $("#G25_C273").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C274").val(item.G25_C274);

                        $("#G25_C275").val(item.G25_C275);
 
                        $("#G25_C275").val(item.G25_C275).trigger("change"); 
    
                        if(item.G25_C276 == '1'){
                           if(!$("#G25_C276").is(':checked')){
                               $("#G25_C276").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C276").is(':checked')){
                               $("#G25_C276").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C277").val(item.G25_C277);
 
                        $("#G25_C277").val(item.G25_C277).trigger("change"); 

                        $("#G25_C278").val(item.G25_C278);
 
                        $("#G25_C278").val(item.G25_C278).trigger("change"); 

                        $("#G25_C279").val(item.G25_C279);
 
                        $("#G25_C279").val(item.G25_C279).trigger("change"); 

                        $("#G25_C285").val(item.G25_C285);
 
                        $("#G25_C285").val(item.G25_C285).trigger("change"); 

                        $("#G25_C286").val(item.G25_C286);
 
                        $("#G25_C286").val(item.G25_C286).trigger("change"); 

                        $("#G25_C289").val(item.G25_C289);
 
                        $("#G25_C289").val(item.G25_C289).trigger("change"); 

                        $("#G25_C290").val(item.G25_C290);
 
                        $("#G25_C290").val(item.G25_C290).trigger("change"); 

                        $("#G25_C291").val(item.G25_C291);

                        $("#G25_C292").val(item.G25_C292);
 
                        $("#G25_C292").val(item.G25_C292).trigger("change"); 
    
                        if(item.G25_C295 == '1'){
                           if(!$("#G25_C295").is(':checked')){
                               $("#G25_C295").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C295").is(':checked')){
                               $("#G25_C295").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C296").val(item.G25_C296);
 
                        $("#G25_C296").val(item.G25_C296).trigger("change"); 

                        $("#G25_C297").val(item.G25_C297);
    
                        if(item.G25_C298 == '1'){
                           if(!$("#G25_C298").is(':checked')){
                               $("#G25_C298").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C298").is(':checked')){
                               $("#G25_C298").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C299").val(item.G25_C299);
 
                        $("#G25_C299").val(item.G25_C299).trigger("change"); 

                        $("#G25_C300").val(item.G25_C300);
 
                        $("#G25_C300").val(item.G25_C300).trigger("change"); 
                        $("#h3mio").html(item.principal);
                    });

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatos :input').each(function(){
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

            $("#hidId").val(id);
            idTotal = $("#hidId").val();
        });
    }

    function seleccionar_registro(){
        //Seleccinar loos registros de la Lista de navegacion, 
        if ( $("#"+idTotal).length > 0) {
            $("#"+idTotal).click();   
            $("#"+idTotal).addClass('active'); 
            idTotal = 0;
            $(".modalOculto").hide();
        }else{
            $(".CargarDatos :first").click();
        } 
        

        $.jgrid.gridUnload('#tablaDatosDetalless0'); 

        $.jgrid.gridUnload('#tablaDatosDetalless1'); 
    } 

    

    function cargarHijos_0(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        $("#tablaDatosDetalless0").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_0=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','CAMPAÑA','PREGUNTA POBLACION','PREGUNTA SCRIPT', 'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
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
                }

                ,
                {  
                    name:'G21_C204', 
                    index:'G21_C204', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_Guion_G21_C204=si',
                        dataInit:function(el){
                            $(el).select2({ 
                                templateResult: function(data) {
                                    var r = data.text.split('|');
                                    var row = '<div class="row">';
                                    var totalRows = 12 / r.length;
                                    for(i= 0; i < r.length; i++){
                                        row += '<div class="col-md-'+ Math.round(totalRows) +'">' + r[i] + '</div>';
                                    }
                                    row += '</div>';
                                    var $result = $(row);
                                    return $result;
                                },
                                templateSelection : function(data){
                                    var r = data.text.split('|');
                                    return r[0];
                                }
                            });
                        }
                    }
                }

                ,
                {  
                    name:'G21_C205', 
                    index:'G21_C205', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_Guion_G21_C205=si',
                        dataInit:function(el){
                            $(el).select2({ 
                                templateResult: function(data) {
                                    var r = data.text.split('|');
                                    var row = '<div class="row">';
                                    var totalRows = 12 / r.length;
                                    for(i= 0; i < r.length; i++){
                                        row += '<div class="col-md-'+ Math.round(totalRows) +'">' + r[i] + '</div>';
                                    }
                                    row += '</div>';
                                    var $result = $(row);
                                    return $result;
                                },
                                templateSelection : function(data){
                                    var r = data.text.split('|');
                                    return r[0];
                                }
                            });
                        }
                    }
                }

                ,
                {  
                    name:'G21_C206', 
                    index:'G21_C206', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_Guion_G21_C206=si',
                        dataInit:function(el){
                            $(el).select2({ 
                                templateResult: function(data) {
                                    var r = data.text.split('|');
                                    var row = '<div class="row">';
                                    var totalRows = 12 / r.length;
                                    for(i= 0; i < r.length; i++){
                                        row += '<div class="col-md-'+ Math.round(totalRows) +'">' + r[i] + '</div>';
                                    }
                                    row += '</div>';
                                    var $result = $(row);
                                    return $result;
                                },
                                templateSelection : function(data){
                                    var r = data.text.split('|');
                                    return r[0];
                                }
                            });
                        }
                    }
                }
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerDetalles0",
            rowList: [40,80],
            sortable: true,
            sortname: 'G21_C204',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'ASOCIACION DATOS BD Y SCRIPT',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_0=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                            $("#"+ rowid +"_G21_C204").change(function(){
                                var valores =$("#"+ rowid +"_G21_C204 option:selected").text();
                                var campos =  $("#"+ rowid +"_G21_C204 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                            $("#"+ rowid +"_G21_C205").change(function(){
                                var valores =$("#"+ rowid +"_G21_C205 option:selected").text();
                                var campos =  $("#"+ rowid +"_G21_C205 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                            $("#"+ rowid +"_G21_C206").change(function(){
                                var valores =$("#"+ rowid +"_G21_C206 option:selected").text();
                                var campos =  $("#"+ rowid +"_G21_C206 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                }
                lastSels = rowid;
            }
            ,

            ondblClickRow: function(rowId) {
                $("#frameContenedor").attr('src', 'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_CentroDiesel/new_index.php?formulario=21&view=si&registroId='+ rowId +'&formaDetalle=si&yourfather='+ idTotal +'&pincheCampo=204&formularioPadre=25');
                $("#editarDatos").modal('show');

            }
        }); 

        $(window).bind('resize', function() {
            $("#tablaDatosDetalless0").setGridWidth($(window).width());
        }).trigger('resize');
    }

    function cargarHijos_1(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        $("#tablaDatosDetalless1").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_1=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','NOMBRE','TIPO','ESTRATEGIA','PASO','RANGO','NIVEL','SUBTIPO', 'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
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
                }

                ,
                { 
                    name:'G4_C21', 
                    index: 'G4_C21', 
                    width:160, 
                    resizable:false, 
                    sortable:true , 
                    editable: true 
                }
 
                ,
                {  
                    name:'G4_C22', 
                    index:'G4_C22', 
                    width:80 ,
                    editable: true, 
                    searchoptions: {
                        sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                    }, 
                    editoptions:{
                        size:20,
                        dataInit:function(el){
                            $(el).numeric();
                        }
                    }

                }
 
                ,
                {  
                    name:'G4_C23', 
                    index:'G4_C23', 
                    width:80 ,
                    editable: true, 
                    searchoptions: {
                        sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                    }, 
                    editoptions:{
                        size:20,
                        dataInit:function(el){
                            $(el).numeric();
                        }
                    }

                }
 
                ,
                {  
                    name:'G4_C24', 
                    index:'G4_C24', 
                    width:80 ,
                    editable: true, 
                    searchoptions: {
                        sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                    }, 
                    editoptions:{
                        size:20,
                        dataInit:function(el){
                            $(el).numeric();
                        }
                    }

                }
 
                ,
                {  
                    name:'G4_C25', 
                    index:'G4_C25', 
                    width:80 ,
                    editable: true, 
                    searchoptions: {
                        sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                    }, 
                    editoptions:{
                        size:20,
                        dataInit:function(el){
                            $(el).numeric();
                        }
                    }

                }
 
                ,
                {  
                    name:'G4_C26', 
                    index:'G4_C26', 
                    width:80 ,
                    editable: true, 
                    searchoptions: {
                        sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                    }, 
                    editoptions:{
                        size:20,
                        dataInit:function(el){
                            $(el).numeric();
                        }
                    }

                }
 
                ,
                {  
                    name:'G4_C27', 
                    index:'G4_C27', 
                    width:80 ,
                    editable: true, 
                    searchoptions: {
                        sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                    }, 
                    editoptions:{
                        size:20,
                        dataInit:function(el){
                            $(el).numeric();
                        }
                    }

                }
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerDetalles1",
            rowList: [40,80],
            sortable: true,
            sortname: 'G4_C21',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'DEFINICION DE METAS',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_1=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            ,

            ondblClickRow: function(rowId) {
                $("#frameContenedor").attr('src', 'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_CentroDiesel/new_index.php?formulario=4&view=si&registroId='+ rowId +'&formaDetalle=si&yourfather='+ idTotal +'&pincheCampo=24&formularioPadre=25');
                $("#editarDatos").modal('show');

            }
        }); 

        $(window).bind('resize', function() {
            $("#tablaDatosDetalless1").setGridWidth($(window).width());
        }).trigger('resize');
    }

    function seleccionar_registro_avanzada(id){
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);
                    $.each(data, function(i, item) {
                        

                    $.jgrid.gridUnload('#tablaDatosDetalless0'); 
                    cargarHijos_0(id);

                    $.jgrid.gridUnload('#tablaDatosDetalless1'); 
                    cargarHijos_1(id);

                        $("#G25_C249").val(item.G25_C249);

                        $("#G25_C250").val(item.G25_C250);
 
                        $("#G25_C250").val(item.G25_C250).trigger("change"); 

                        $("#G25_C251").val(item.G25_C251);

                        $("#G25_C252").val(item.G25_C252);
 
                        $("#G25_C252").val(item.G25_C252).trigger("change"); 

                        $("#G25_C253").val(item.G25_C253);
 
                        $("#G25_C253").val(item.G25_C253).trigger("change"); 

                        $("#G25_C254").val(item.G25_C254);
 
                        $("#G25_C254").val(item.G25_C254).trigger("change"); 

                        $("#G25_C255").val(item.G25_C255);
 
                        $("#G25_C255").val(item.G25_C255).trigger("change"); 

                        $("#G25_C257").val(item.G25_C257);
 
                        $("#G25_C257").val(item.G25_C257).trigger("change"); 

                        $("#G25_C258").val(item.G25_C258);
 
                        $("#G25_C258").val(item.G25_C258).trigger("change"); 

                        $("#G25_C259").val(item.G25_C259);
 
                        $("#G25_C259").val(item.G25_C259).trigger("change"); 

                        $("#G25_C260").val(item.G25_C260);
 
                        $("#G25_C260").val(item.G25_C260).trigger("change"); 

                        $("#G25_C261").val(item.G25_C261);
 
                        $("#G25_C261").val(item.G25_C261).trigger("change"); 

                        $("#G25_C262").val(item.G25_C262);

                        $("#G25_C263").val(item.G25_C263);

                        $("#G25_C264").val(item.G25_C264);

                        $("#G25_C265").val(item.G25_C265);

                        $("#G25_C266").val(item.G25_C266);
    
                        if(item.G25_C267 == '1'){
                           if(!$("#G25_C267").is(':checked')){
                               $("#G25_C267").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C267").is(':checked')){
                               $("#G25_C267").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C268").val(item.G25_C268);

                        $("#G25_C269").val(item.G25_C269);

                        $("#G25_C272").val(item.G25_C272);
 
                        $("#G25_C272").val(item.G25_C272).trigger("change"); 
    
                        if(item.G25_C273 == '1'){
                           if(!$("#G25_C273").is(':checked')){
                               $("#G25_C273").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C273").is(':checked')){
                               $("#G25_C273").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C274").val(item.G25_C274);

                        $("#G25_C275").val(item.G25_C275);
 
                        $("#G25_C275").val(item.G25_C275).trigger("change"); 
    
                        if(item.G25_C276 == '1'){
                           if(!$("#G25_C276").is(':checked')){
                               $("#G25_C276").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C276").is(':checked')){
                               $("#G25_C276").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C277").val(item.G25_C277);
 
                        $("#G25_C277").val(item.G25_C277).trigger("change"); 

                        $("#G25_C278").val(item.G25_C278);
 
                        $("#G25_C278").val(item.G25_C278).trigger("change"); 

                        $("#G25_C279").val(item.G25_C279);
 
                        $("#G25_C279").val(item.G25_C279).trigger("change"); 

                        $("#G25_C285").val(item.G25_C285);
 
                        $("#G25_C285").val(item.G25_C285).trigger("change"); 

                        $("#G25_C286").val(item.G25_C286);
 
                        $("#G25_C286").val(item.G25_C286).trigger("change"); 

                        $("#G25_C289").val(item.G25_C289);
 
                        $("#G25_C289").val(item.G25_C289).trigger("change"); 

                        $("#G25_C290").val(item.G25_C290);
 
                        $("#G25_C290").val(item.G25_C290).trigger("change"); 

                        $("#G25_C291").val(item.G25_C291);

                        $("#G25_C292").val(item.G25_C292);
 
                        $("#G25_C292").val(item.G25_C292).trigger("change"); 
    
                        if(item.G25_C295 == '1'){
                           if(!$("#G25_C295").is(':checked')){
                               $("#G25_C295").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C295").is(':checked')){
                               $("#G25_C295").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C296").val(item.G25_C296);
 
                        $("#G25_C296").val(item.G25_C296).trigger("change"); 

                        $("#G25_C297").val(item.G25_C297);
    
                        if(item.G25_C298 == '1'){
                           if(!$("#G25_C298").is(':checked')){
                               $("#G25_C298").prop('checked', true);  
                            }
                        } else {
                            if($("#G25_C298").is(':checked')){
                               $("#G25_C298").prop('checked', false);  
                            }
                            
                        }

                        $("#G25_C299").val(item.G25_C299);
 
                        $("#G25_C299").val(item.G25_C299).trigger("change"); 

                        $("#G25_C300").val(item.G25_C300);
 
                        $("#G25_C300").val(item.G25_C300).trigger("change"); 
                        $("#h3mio").html(item.principal);
                    });

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatos :input').each(function(){
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

            $("#hidId").val(id);
            idTotal = $("#hidId").val();
            $(".CargarDatos").each(function(){
                $(this).removeClass('active');
            });
            $("#"+idTotal).addClass('active');
    }

    function vamosRecargaLasGrillasPorfavor(id){
        

            $.jgrid.gridUnload('#tablaDatosDetalless0'); 
            cargarHijos_0(id);

            $.jgrid.gridUnload('#tablaDatosDetalless1'); 
            cargarHijos_1(id);
    }
</script>

