<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_detallado_t___ ;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content" id="secPrincipalContenedor">
        <?php 
            //Obtenemos el id del proyecto para ser enviado por parametro
            try{
                $strConsultaIdProyecto_t = "SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped=$_SESSION[HUESPED]";
                $intIdProyectoUsuarios_t = $mysqli->query($strConsultaIdProyecto_t)->fetch_row()[0];
            } catch (Exception $ex) {
                error_log("Algo paso al intentar generar el proyecto $ex", 0);
                echo "Algo paso al intentar generar el proyecto $ex";
            }
        ?>
        

        <object id="objIframe" type='text/html' data="http://www.wikipedia.org/" style='width:100%; height:90vh' />
        <input type="hidden" id="txtIdUsuario_t" value="23">
        <input type="hidden" id="txtSQLCondicionProyecto_t" value="0=0">

        <script type="text/javascript">
            var strIdUsuario_t="<?php echo $_SESSION['USUARICBX'];?>";
            var strSQLCondicionProyecto_t="idProyecto=<?php echo $intIdProyectoUsuarios_t;?>";
            var strURLDefinitiva_t;
            var intAltoMenosAlgo_t = parseInt(window.innerHeight)-150;
            
            strURLDefinitiva_t = "https://interno.dyalogo.cloud:8887/dy_cbx_detallado_llamadas/reporte/historico_llamadas.jsfdy?id_usuario_logueado="+strIdUsuario_t
            +"&sqlcondproy="+strSQLCondicionProyecto_t;
            
            console.log('URL DEFINITIVA: '+strURLDefinitiva_t);
            

            document.getElementById('objIframe').data = strURLDefinitiva_t;
            /*
            document.getElementById('secPrincipalContenedor').style.height=intAltoMenosAlgo_t+'px';            
            window.onresize = function(event) {
                intAltoMenosAlgo_t = parseInt(window.innerHeight)-150;
                document.getElementById('secPrincipalContenedor').style.height=intAltoMenosAlgo_t+'px';

            };*/
            
        </script>
    </section>
</div>
