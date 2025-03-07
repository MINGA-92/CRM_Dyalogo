<?php
    $url_crud = "carga/carga_CRUD.php";
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_title_cargue_datos ;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li> <?php echo $str_cargue_d;?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?php echo $str_configuracion_carg;?>
                </h3>
            </div>
            <div class="box-body">
                <form id="formEnvioDatos" method="post" enctype="multipart/form-data" >
                     <div class="row">
                        
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $str_based_label;?></label>
                                <select class="form-control" id="cmbControl" name="cmbControl">
                                    <option value="0"></option>
                                    <?php
                                        $Lsql = '';
                                        $acceso = $_SESSION['ACCESO'];
                                        $identificacion = $_SESSION['IDENTIFICACION']; 

                                        if($acceso == '-1'){
                                            $Lsql = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM GUION_ WHERE GUION__ConsInte__PROYEC_b =".$_SESSION['PROYECTO']." ORDER BY GUION__Nombre____b ASC";
                                        }else{
                                            $Lsql = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM GUION_ JOIN PEOBUS ON PEOBUS_ConsInte__GUION__b = GUION__ConsInte__b WHERE PEOBUS_ConsInte__USUARI_b = ".$identificacion." ORDER BY GUION__Nombre____b ASC";
                                        }

                                        if($identificacion != 0){
                                            $result = $mysqli->query($Lsql);
                                            while($key = $result->fetch_object()){
                                                echo '<option value= "'.$key->GUION__ConsInte__b.'">'.utf8_encode($key->GUION__Nombre____b).'</option>';
                                            } 
                                        }
                                        
                                    ?>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-md-9">
                            <div class="form-group">
                                <label><?php echo $str_carga_label;?></label>
                                <input type="file" name="arcExcell" disabled="true" id="arcExcell" class="form-control">
                            </div>
                        </div>
                       
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(function(){
        var htmlDeaqi = '';
        $("#cargueDeDatos").addClass('active');

        $("#cmbControl").change(function(){
            $.ajax({
                url         : '<?=$url_crud;?>',
                type        : 'post',
                data        : {  llenarDatosGs : 'si', cmbControl : $(this).val() },
                success     : function(data){
                    $("#cmbColumnaD").html(data);
                    htmlDeaqi = data;
                    $("#cmbColumnaD").attr('disabled', false);
                    $("#arcExcell").attr('disabled', false);
                    $("#btnExcell").attr('disabled', false);
                }
            });
        });

        $('#arcExcell').on('change', function(e){
            var formData = new FormData($("#formEnvioDatos")[0]);
            $.ajax({
                url         : '<?=$url_crud;?>?getcolumns=si',
                type        : 'post',
                data: formData,
                dataType    : 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend : function() {
                   $.blockUI({ message: "Un momento, por favor" });
                },
                //una vez finalizado correctamente
                success: function(data){

                    var total = data.total;
                    $("#totales").val(total);
                    var tr = '';
                    var option = '';
                    for(i = 0; i < total; i++){
                        option += '<option value="'+ i +'">'+ data.opciones[i]['Nombres'] +'</option>';
                    }

                    $("#cmbColumnaP").html(option);
                    $("#cmbColumnaP").attr('disabled', false);

                    for(i = 0; i < total; i++){

                        tr += '<tr>';
                        tr += '<td width="50%"><select class="form-control" id="selExcel'+ i +'" name="selExcel'+ i +'">'+ option +'</select></td>';
                        tr += '<td width="50%"><select class="form-control" id="selDB'+ i +'" name="selDB'+ i +'">'+ htmlDeaqi +'</select></td>';
                        tr += '</tr>';

                    }                   
                    $("#JodeteCarajo").html(tr);

                },
                complete: function(){
                    $.unblockUI();
                },
                //si ha ocurrido un error
                error: function(){
                    alertify.error('Ocurrio un error, intenta mas tarde');
                }
            })
        });   
    });
</script>