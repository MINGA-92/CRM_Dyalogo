<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $_SESSION['PROYECTO'];?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li><?php echo $str_usuarios;?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <form class="form-horizontal" id="insertPerfil" enctype="multipart/form-data" method="post" action="pages/Usuarios/guardarUsuarios.php">
            <div class="row">
                <div class="col-md-9">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"><?php echo $str_datos__usuario;?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $str_nombre_usuario;?></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="txtNombre" readonly="true" name="txtNombre" placeholder="<?php echo $str_nombre_usuario;?>" value="<?php echo $_SESSION['NOMBRES'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label"><?php echo $str_passwo_usuario;?></label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="<?php echo $str_passwo_usuario;?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label"><?php echo $str_passRe_usuario;?></label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="txtRepeatPassword" name="txtRepeatPassword" placeholder="<?php echo $str_passRe_usuario;?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary"><?php echo $str_guardar;?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="box box-default">
                        <div class="box-body box-profile">
                            <img id="avatar3" class="profile-user-img img-responsive img-circle" src="<?php echo $_SESSION['IMAGEN'];?>" alt="User profile picture">

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

                            <input type="file" name="inpFotoPerfil" id="inpFotoPerfil" class="form-control">
                            <input type="hidden" name="hidOculto" id="hidOculto" value="0">
                            <input type="hidden" name="hidUsuari" id="hidUsuari" value="<?php echo $_SESSION['IDENTIFICACION'];?>">
                            <input type="hidden" name="ruta" value="<?php if(isset($_GET['page'])){ echo $_GET['page'];}else{ echo "dashboard"; }?>">
                        </div>
                    <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </form>
    </section>
</div>


<script type="text/javascript">
    $(function(){
        $('#inpFotoPerfil').on('change', function(e){
            addImage4(e); 
            $("#hidOculto").val(1);
        });   
    });

    function addImage4(e){
        var file = e.target.files[0],
        imageType = /image.*/;

        if (!file.type.match(imageType))
            return;

        var reader = new FileReader();
        reader.onload = fileOnload4;
        reader.readAsDataURL(file);
    }

    function fileOnload4(e) {
        var result= e.target.result;
        $('#avatar3').attr("src",result);
    }
</script>