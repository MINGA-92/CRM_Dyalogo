                </section><!--Seccion de contenido-->
            </div>

<?php
	$http = "http://".$_SERVER["HTTP_HOST"];
	if (isset($_SERVER['HTTPS'])) {
	    $http = "https://".$_SERVER["HTTP_HOST"];
	}
?>
    <!-- ./COntent wrapper -->
    <div class="modal modal-wide fade" id="concursoEntrar"  role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Formularios Dyalogo</h4>
                </div>
                <div class="modal-body">
                    <div class="control-group">
                        <select class="form-control select2" style="width: 100%;" id="SelCampanhas">
                            <option>Seleccione</option>
                            <?php
                                
                                $Lsql = '';
                                $acceso = getAccesoUser($token);
                                $identificacion = getIdentificacionUser($token);

                                $Lsql = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ JOIN ".$BaseDatos_systema.".PEOBUS ON PEOBUS_ConsInte__GUION__b = GUION__ConsInte__b WHERE PEOBUS_ConsInte__USUARI_b = ".$identificacion;
                                if($identificacion != 0){
                                    $result = $mysqli->query($Lsql);

                                    while($key = $result->fetch_object()){
                                        echo '<option value="'.$key->GUION__ConsInte__b.'">'.($key->GUION__Nombre____b).'</option>';
                                    } 

                                }
                                
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn btn-primary" id="BtnCambiarForm"><i class="fa fa-check"></i> Aceptar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- MODAL DE OPCIONES DE PEFIL -->
    <div class="modal fade-in" id="cambiarPerfil" data-backdrop="static"  data-keyboard="false" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="insertPerfil" enctype="multipart/form-data" method="post"  >
                    <div class="modal-header">
                        <h4 class="modal-title" style="text-align: center;"><?php echo $_SESSION['NOMBRES'];?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title text-center" id="nombrePasswordCh"> </h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="form-group">
                                        <label for="inputName">Nueva Contraseña: </label>
                                            <a class="btn btn-outline-info" id="VerPassNueva"><i class="fa fa-eye-slash"></i></a>
                                            <input type="password" class="form-control" id="txrPasswordCh" name="txrPasswordCh" placeholder="***********">
                                        </div>
                                        <div class="form-group">
                                        <label for="inputName">Confirmar Contraseña: </label>
                                            <a class="btn btn-outline-info" id="VerPassConfirmar"><i class="fa fa-eye-slash"></i></a>
                                            <input type="password" class="form-control" id="txrPasswordChR" name="txrPasswordChR" placeholder="***********" disabled>
                                        </div>

                                        <div class="form-group" id="divActual">
                                            <label for="inputName" style="margin-top: 2%;">Contraseña Actual: </label>
                                            <a class="btn btn-outline-info" id="VerPassActual"><i class="fa fa-eye-slash"></i></a>
                                            <input type="password" class="form-control" id="ActualPassword" name="ActualPassword" placeholder="***********" disabled>
                                        </div>
                                    </div>
                                    
                                    <!-- Div Alertext -->
                                    <div id="Alertext" class="form-group">
                                        <p class="text-danger">
                                            <br><label id="Alerta" style="font-size: 92%; text-align: center;" hidden> 🤔 </label></br>
                                        </p>
                                    </div>
                                    <input type="hidden" name="IdUsuario" id="IdUsuario" value="<?php echo $_SESSION['IDENTIFICACION'];?>">
                                    <input type="hidden" name="IdHuesped" id="IdHuesped" value="<?php echo $_SESSION['HUESPED_CRM'];?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Profile Image -->
                                <div class="box box-default">
                                    <div class="box-body box-profile">
                                        <img id="avatar_Imagen" class="profile-user-img img-responsive img-circle" src="<?php echo $_SESSION['IMAGEN'];?>" alt="User profile picture">
                                        <h3 class="profile-username text-center"><?php echo $_SESSION['NOMBRES'];?></h3>
                                        <p class="text-muted text-center"><?php echo $_SESSION['CARGO'];?></p>

                                        <ul class="list-group list-group-unbordered">
                                            <!--<li class="list-group-item">
                                                <b>Followers</b> <a class="pull-right">1,322</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Following</b> <a class="pull-right">543</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Friends</b> <a class="pull-right">13,287</a>
                                            </li>-->
                                        </ul>

                                        <input type="file" name="inpFotoPerfil" id="inpFotoPerfil2" class="form-control" accept="image/jpg, image/jpeg" >
                                        <input type="hidden" name="hidUsuari" id="hidUsuari" value="<?php echo $_SESSION['IDENTIFICACION'];?>">
                                        <input type="hidden" name="ruta" value="<?php if(isset($_GET['page'])){ echo $_GET['page'];}else{ echo "dashboard"; }?>">
                                    </div>
                                <!-- /.box-body -->
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                    <button type="button" name="BtnCambiarPass" id="BtnCambiarPass" class="btn btn-primary" disabled="true">Guardar Cambios</button>
                        <button type="button" id="btnCambiarFoto" class="btn btn-primary" hidden="true">Guardar Todo</button>
                        <button class="btn btn-danger" id="btnCancelar_2" data-dismiss="modal"  type="button">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- jQuery UI 1.11.4 -->
    <script src="assets/jqueryUI/jquery-ui.min.js"></script>
    <!-- jQuery BlockUI 2.70.0 -->
    <script src="assets/plugins/BlockUi/jquery.blockUI.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.6 -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <!-- Sparkline -->
    <script src="assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- Slimscroll -->
    <script src="assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- daterangepicker -->
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <!-- FastClick -->
    <script src="assets/plugins/fastclick/fastclick.js"></script>

    <link rel="stylesheet" href="assets/plugins/timepicker/bootstrap-timepicker.min.css">
    <script src="assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>

    <script src="assets/Guriddo_jqGrid_/js/i18n/grid.locale-es.js" type="text/javascript"></script>
    <script src="assets/Guriddo_jqGrid_/js/jquery.jqGrid.min.js" type="text/javascript"></script>

    <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>      
    
    
    <!-- AdminLTE App -->
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/numeric.js"></script>
    <script src="assets/js/alertify.js"></script>

    <script type="text/javascript">
        $(function(){
            $("#btnGenerar").click(function(){
                $("#enviarForms").attr("action", "generar_estable.php");
                $("#enviarForms").submit();
            });

            $("#btnGenerarBusqueda").click(function(){
                $("#enviarForms").attr("action", "generarBusqueda.php");
                $("#enviarForms").submit();
            });

            /* $.fn.datepicker.dates['es'] = {
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

            //alertify.defaults.glossary.title = 'أليرتفاي جي اس';
            

                //Datemask dd/mm/yyyy
            


            $(".FechaEntreValida").datepicker({
                language: "es",
                autoclose: true,
                todayHighlight: true
            });


            $(".Numerico").numeric();
            $(".NumericoValidador").numeric();
            $(".Decimal").numeric({ decimal : ".",  negative : false, scale: 4 }); */

            $('#SelCampanhas').select2();

            $("#BtnCambiarForm").click(function(){
                window.location.href = "<?php echo $http;?>/crm_php/index.php?formulario="+$("#SelCampanhas").val();
            }); 

            <?php 

                if(isset($_GET['result'])){ 
                    if($_GET['result'] == '1'){ ?>
                        swal({
                            title: "Exito!",
                            html : true,
                            text: 'El formulario a sido generado con exito!',
                            type: "success",
                            confirmButtonText: "Ok"
                        });
            <?php 
                    }

                    if(isset($_GET['form']) && !isset($_GET['busqueda']) && !isset($_GET['forma'])){ ?>

                        var generados = 'Se ha generado la carpeta G<?php echo $_GET['form'];?> con la siguiente estructura :' +
                                        '<ul class="list-unstyled">' +
                                            '<li><i class="fa fa-folder-open-o"></i>&nbsp;G<?php echo $_GET['form'];?><li>' +
                                                '<ul>' +
                                                    '<li><i class="fa fa-file-code-o"></i>&nbsp;G<?php echo $_GET['form'];?>.php</li>'+
                                                    '<li><i class="fa fa-file-code-o"></i>&nbsp;G<?php echo $_GET['form'];?>_CRUD.php</li>'+
                                                    '<li><i class="fa fa-file-code-o"></i>&nbsp;G<?php echo $_GET['form'];?>_eventos.js</li>'+
                                                    '<li><i class="fa fa-file-code-o"></i>&nbsp;G<?php echo $_GET['form'];?>_extender_funcionalidad.php</li>'+
                                                '</ul>'+
                                            '</li>'+
                                        '</ul>';
                        $("#muestraGenerados").html(generados);
            <?php
                    }elseif(isset($_GET['forma'])){
                        //si es una busqueda
            ?>
                        var generados = 'Se ha generado el formulario web , Puede verlo en la siguiente url : <a href="<?php echo $http;?>/crm_php/web_forms.php?web=<?php echo $_GET['form'];?>" target="_blank"><?php echo $http;?>/crm_php/web_forms.php?web=<?php echo $_GET['form'];?></a>';
                        $("#muestraGenerados").html(generados);
            <?php
                    }else{
                        //si es una busqueda
            ?>
                var generados = 'Se ha generado la carpeta G<?php echo $_GET['form'];?> con la siguiente estructura :' +
                                        '<ul class="list-unstyled">' +
                                            '<li><i class="fa fa-folder-open-o"></i>&nbsp;G<?php echo $_GET['form'];?><li>' +
                                                '<ul>' +
                                                    '<li><i class="fa fa-file-code-o"></i>&nbsp;G<?php echo $_GET['form'];?>.php</li>'+
                                                    '<li><i class="fa fa-file-code-o"></i>&nbsp;G<?php echo $_GET['form'];?>_CRUD.php</li>'+
                                                    '<li><i class="fa fa-file-code-o"></i>&nbsp;G<?php echo $_GET['form'];?>_eventos.js</li>'+
                                                    '<li><i class="fa fa-file-code-o"></i>&nbsp;G<?php echo $_GET['form'];?>_extender_funcionalidad.php</li>'+
                                                '</ul>'+
                                            '</li>'+
                                        '</ul>';
                $("#muestraGenerados").html(generados);
            <?php           
                    }
                }
            ?>
        });
    </script>
        
    </body>
</html>
