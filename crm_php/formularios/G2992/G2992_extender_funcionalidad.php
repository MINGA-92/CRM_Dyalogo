<?php

    include(__DIR__."/../../conexion.php");

    //Este archivo es para agregar funcionalidades al G, y que al momento de generar de nuevo no se pierdan

    //Cosas como nuevas consultas, nuevos Inserts, Envio de correos, etc, en fin extender mas los formularios en PHP
                
?>

<script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>

<!-- Modal de calidad para nuestro nuevo boton de calificar calidad-->
<div class="modal fade-in" id="enviarCalificacion2" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="CerrarCalificacion2">&times;</button>
                <h4 class="modal-title">Enviar Calificacion</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-12">
                        <p >Para enviar la calificacion a otros correos, ingresarlos <strong>SEPARANDOLOS</strong>  por una coma ( , ).</p>
                        <input type="text" class="form-control" id="cajaCorreos2" name="cajaCorreos2" placeholder="Ejemplo1@ejem.com,Ejemplo2@ejem.com">
 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <img hidden id="loading" src="/crm_php/assets/plugins/loading.gif" width="30" height="30">&nbsp;&nbsp;&nbsp;
                        <button id="sendEmails2" readonly class="btn btn-primary" >Enviar Calificacion</button>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  // Declaracion de variables
  var idLoco;

  // Botón para realizar consumo de información del suscriptor
  $("#9162").append("<button style='align: right' id='botonBusqueda'data-toggle='modal' class='btn btn-info'  onClick='botonNuevo();' >Validar Codigo Supscriptor</button>");

  // Funcion que invoca el consumo del api
  function botonNuevo(){

    getCliente( $("#G2992_C59915").val());

  }

  // Accion que actualiza el id de la gestión en un campo nuevo, cada que se cambia la tipificación
  $('#G2992_C59899').change(function(){

    idLoco = $("#IdGestion").val();
    if($('#G2992_C59899').val() > 0){
      $.ajax({
        method: "POST",
        url: "formularios/G2992/G2992_CRUD_DOS.php?actualizarID=si",
        data: {
          idLoco: idLoco
        },
        dataType: "json",
        success: function(data){
          console("ya");
        },
        fail: function(e){
          console.error('algo esta mal ', e.toString());
        }
      });
    }
  });

  /**
  * llamada ajax para la api, para obtener la información del cliente
  */
  function getCliente(cliente){
    $.ajax({
        method: "POST",
        url: "formularios/G2999/G2999_extender_funcionalidad.php?getCliente=si",
        data: {
          cliente:cliente,
          empresa:'322',
        },
        dataType: "json",
        success: function(data){
          
          if (data["result"]) {
            console.log(data["data"].identification);
            console.log(data["result"]);
            $("#G2992_C59917").val(data["data"].identification);
            $("#G2992_C59916").val(data["data"].name + ' ' + data["data"].lastName);
            $("#G2992_C59918").val(data["data"].email);
            $("#G2992_C59919").val(data["data"].phones);
            $("#G2992_C59921").val(data["data"].codeUser);
            $("#G2992_C59922").val(data["data"].relationshipProperty);
            $("#G2992_C59923").val(data["data"].status);
            $("#G2992_C59924").val(data["data"].arrears);
            $("#G2992_C65254").val(data["data"].reference);
          }else{
            console.log(data["result"]);
            console.log(data["message"]);
            var erroNoEncontrado = "NO EXISTE REGISTRO";
            $("#G2992_C59916").val(erroNoEncontrado);
            $("#G2992_C59917").val('');
            $("#G2992_C59918").val('');
            $("#G2992_C59919").val('');
            $("#G2992_C59921").val('');
            $("#G2992_C59922").val('');
            $("#G2992_C59923").val('');
            $("#G2992_C59924").val('');
            $("#G2992_C65254").val('');
          }
        },
        fail: function(e){
          console.error('algo esta mal ', e.toString());
        }
    });
  }

  $(document).ready(function () {
      //alert('dev2_test');
      <?php if(isset($_GET['G2992_C59915'])) : ?>
          var cliente='<?=$_GET['G2992_C59915']
          
          ?>';
      <?php else : ?>
          var cliente=$("#G2992_C59915").val();
      <?php endif; ?>
      getCliente(cliente);

      $("#G2992_C59921").change(function(){
        cliente=$(this).val();
        getCliente(cliente);
      });

  });

  /** **************************************************
   * DESARROLLOS PARA CALIDAD DESDE EL MODULO CALIDAD
   * ***************************************************
   */
  // Función que permite llenar los campos numéricos con los valores de los campos tipo lista del formulario de calidad
  function traducirCalificacion(idOption){

    var valCalificacion =0;

    switch (idOption) {
      case '45070':
      
        valCalificacion = 1;
        return valCalificacion;
        break;
      case '45082':
        valCalificacion = 10;
        return valCalificacion;
        break;
      case '45071':
        valCalificacion = 2;
        return valCalificacion;
        break;
      case '45072':
        valCalificacion = 3;
        return valCalificacion;
        break;
      case '45073':
        valCalificacion = 4;
        return valCalificacion;
        break;
      case '45074':
        valCalificacion = 5;
        return valCalificacion;
        break;
      case '45075':
        valCalificacion = 6;
        return valCalificacion;
        break;
      case '45076':
        valCalificacion = 7;
        return valCalificacion;
        break;
      case '45077':
        valCalificacion = 8;
        return valCalificacion;
        break;
      case '45078':
        valCalificacion = 9;
        return valCalificacion;
        break;
      default:
        valCalificacion = 0;
        return valCalificacion;
        break;
    }

    return valCalificacion;

  }

  /**
   * Funcion que permite obtener el link de grabacion de la llamada para despues enviarla por el correo de calificacion
   */
  function seteando(idLocos){
    //Se setea idGestion
    $("#G2992_C66544").val($("#IdGestion").val());
    var isefue= idLocos;
    console.log(isefue); 
    $.ajax({
        url: "formularios/G2992/G2992_CRUD_DOS.php?traerGestionYLink=si",
        type: "POST",
        data: {
          idLoco: isefue
        },
        success: function(data){
            console.log("Llego el link");
            $("#G2992_C66545").val(data); 
        },
        error : function(){
            alertify.error("No se llego el link");   
        },
        complete : function(){
            $("#loading").attr("hidden",true);
            $("#CerrarCalificacion2").click();
        }

    });

  }
  
  $(document).ready(function () {

    <?php if(!isset($_GET["intrusionTR"])) : ?>

      // Campo pregunta1
      $("#G2992_C59955").on('change',function(){
        var calificacio=$("#G2992_C59955").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65875").val(calificaTraducido);
        $("#G2992_C65875").focus();
        $("#G2992_C65875").blur();

      });

      // Campo pregunta2
      $("#G2992_C59988").on('change',function(){
        var calificacio=$("#G2992_C59988").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65876").val(calificaTraducido);
        $("#G2992_C65876").focus();
        $("#G2992_C65876").blur();
      });

      // Campo pregunta3
      $("#G2992_C59990").on('change',function(){
        var calificacio=$("#G2992_C59990").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65877").val(calificaTraducido);
        $("#G2992_C65877").focus();
        $("#G2992_C65877").blur();
      });

      // Campo pregunta4
      $("#G2992_C59992").on('change',function(){
        var calificacio=$("#G2992_C59992").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65878").val(calificaTraducido);
        $("#G2992_C65878").focus();
        $("#G2992_C65878").blur();
      });

      // Campo pregunta5
      $("#G2992_C59994").on('change',function(){
        var calificacio=$("#G2992_C59994").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65879").val(calificaTraducido);
        $("#G2992_C65879").focus();
        $("#G2992_C65879").blur();
      });

      // Campo pregunta6
      $("#G2992_C59996").on('change',function(){
        var calificacio=$("#G2992_C59996").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65880").val(calificaTraducido);
        $("#G2992_C65880").focus();
        $("#G2992_C65880").blur();
      });

      // Campo pregunta7
      $("#G2992_C59998").on('change',function(){
        var calificacio=$("#G2992_C59998").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65881").val(calificaTraducido);
        $("#G2992_C65881").focus();
        $("#G2992_C65881").blur();
      });

      // Campo pregunta8
      $("#G2992_C60000").on('change',function(){
        var calificacio=$("#G2992_C60000").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65882").val(calificaTraducido);
        $("#G2992_C65882").focus();
        $("#G2992_C65882").blur();
      });

      // Campo pregunta9
      $("#G2992_C60002").on('change',function(){
        var calificacio=$("#G2992_C60002").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65883").val(calificaTraducido);
        $("#G2992_C65883").focus();
        $("#G2992_C65883").blur();
      });

      // Campo pregunta10
      $("#G2992_C60004").on('change',function(){
        var calificacio=$("#G2992_C60004").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65884").val(calificaTraducido);
        $("#G2992_C65884").focus();
        $("#G2992_C65884").blur();
      });

      // Campo pregunta11
      $("#G2992_C60006").on('change',function(){
        
        var calificacio=$("#G2992_C60006").val();
        var calificaTraducido = traducirCalificacion(calificacio);
        $("#G2992_C65885").val(calificaTraducido);
        $("#G2992_C65885").focus();
        $("#G2992_C65885").blur();
        if(calificacio>0){
          idLoco = $("#IdGestion").val();
          seteando(idLoco);
          
        }
        
      });

    <?php endif; ?>

  });

  //Se oculta la seccion 2 de calidad
  document.getElementById('10240').style.display = 'none';

  //Se oculta la seccion BOT
  document.getElementById('11199').style.display = 'none';

  //Se oculta la seccion INTERCAMBIO DE DOCUMENTACION CUSIANAGAS
  document.getElementById('11216').style.display = 'none';

  //Se oculta los botones standar de finalizar calificacion
  var buttonCalidadaToHide = document.getElementsByClassName("FinalizarCalificacion"); //buttonCalidadaToHide es un array
  for(var i = 0; i < buttonCalidadaToHide.length; i++){

    buttonCalidadaToHide[i].style.display = "none"; 
  }

  // Se crea nuestro propio boton de calificacion de calidad
  $("#s_9171").append("<a style='float: right;' class='btn btn-success pull-right FinalizarCalificacion2' role='button' >Finalizar Calificacion&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-paper-plane-o'></i></a>");

  //Se abre la modal para colocar el correo a donde se enviará la calificación
  $(".FinalizarCalificacion2").click(function(){
      $("#calidad").val("1");
      $("#enviarCalificacion2").modal("show");
  });

  // Se realiza el envío del email con los datos de la calificación de calidad
  $("#sendEmails2").click(function(){
    
    $("#Save").click();
    $("#loading").attr("hidden",false);
    var emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
    var cajaCorreos = $("#cajaCorreos2").val();

    if (cajaCorreos == null || cajaCorreos == "") {
        cajaCorreos = "";
        console.log("caja correos vacias!")
    }else{
        cajaCorreos = cajaCorreos.replace(/ /g, "");
        cajaCorreos = cajaCorreos.replace(/,,,,,/g, ",");
        cajaCorreos = cajaCorreos.replace(/,,,,/g, ",");
        cajaCorreos = cajaCorreos.replace(/,,,/g, ",");
        cajaCorreos = cajaCorreos.replace(/,,/g, ",");

        if (cajaCorreos[0] == ",") {
            cajaCorreos = cajaCorreos.substring(1);
        }

        if (cajaCorreos[cajaCorreos.length-1] == ",") {
            cajaCorreos = cajaCorreos.substring(0,cajaCorreos.length-1);
        }

        var porciones = cajaCorreos.split(",");
        console.log("porciones: " + porciones);

        for (var i = 0; i < porciones.length; i++) {
            if (!emailRegex.test(porciones[i])) {
                porciones.splice(i, 1);
            }
        }

        cajaCorreos = porciones.join(",");
        console.log(cajaCorreos);
    }

    var formData = new FormData($("#FormularioDatos")[0]);
    formData.append("IdGestion",$("#IdGestion").val());
    formData.append("IdGuion","2992");
    <?php 
      if (isset($_SESSION["IDENTIFICACION"])) {?>
        formData.append("IdCal",<?=$_SESSION["IDENTIFICACION"];?>);

      <?php    
      }elseif (isset($_GET['idUSUARI'])) {?>
        formData.append("IdCal",<?=$_GET['idUSUARI'];?>);
      <?php
      }else{?>
        formData.append("IdCal",<?=$_GET['usuario'];?>);
      <?php
      }
    ?>
    formData.append("Correos",cajaCorreos);

    //Se envían los valores de los campos que se enviaran por correo
    formData.append("G2992_C59955",$("#G2992_C59955").val());
    formData.append("G2992_C59988",$("#G2992_C59988").val());
    formData.append("G2992_C59990",$("#G2992_C59990").val());
    formData.append("G2992_C59992",$("#G2992_C59992").val());
    formData.append("G2992_C59994",$("#G2992_C59994").val());
    formData.append("G2992_C59996",$("#G2992_C59996").val());
    formData.append("G2992_C59998",$("#G2992_C59998").val());
    formData.append("G2992_C60000",$("#G2992_C60000").val());
    formData.append("G2992_C60002",$("#G2992_C60002").val());
    formData.append("G2992_C60004",$("#G2992_C60004").val());
    formData.append("G2992_C60006",$("#G2992_C60006").val());
    formData.append("G2992_C66545",$("#G2992_C66545").val());
    formData.append("G2992_C61149",$("#G2992_C61149").val());
    formData.append("G2992_C61151",$("#G2992_C61151").val());
    formData.append("G2992_C61153",$("#G2992_C61153").val());
    formData.append("G2992_C61154",$("#G2992_C61154").val());
    formData.append("origenCalidad","calidad");

    $.ajax({
        url: "formularios/G2992/G2992_CRUD_DOS.php?EnviarCalificacion=si",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data){
          alertify.success("¡Calificación Enviada!");
          window.location.reload();
        },
        error : function(){
            alertify.error("No se pudo enviar la calificacion.");   
        },
        complete : function(){
            $("#loading").attr("hidden",true);
            $("#CerrarCalificacion2").click();
        }

    }); 
    
  });

</script>

<script >
  /**
   * Esta parte será para calidad desde el tiempo real
   * 
   * 
   * */
  <?php if(isset($_GET["intrusionTR"]) && $_GET["intrusionTR"]=="si") : ?>

    var pregunta1 = 0;
    var pregunta2 = 0;
    var pregunta3 = 0;
    var pregunta4 = 0;
    var pregunta5 = 0;
    var pregunta6 = 0;
    var pregunta7 = 0;
    var pregunta8 = 0;
    var pregunta9 = 0;
    var pregunta10 = 0;
    var pregunta11 = 0;
    //Acciones sobre los campos de calificacion
    // Campo pregunta1
    $("#G2992_C59955").on('change',function(){
      var calificacio=$("#G2992_C59955").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta1 = calificaTraducido;
      calificacion();

    });

    // Campo pregunta2
    $("#G2992_C59988").on('change',function(){
      var calificacio=$("#G2992_C59988").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta2 = calificaTraducido;
      calificacion();
    });

    // Campo pregunta3
    $("#G2992_C59990").on('change',function(){
      var calificacio=$("#G2992_C59990").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta3 = calificaTraducido;
      calificacion();
    });

    // Campo pregunta4
    $("#G2992_C59992").on('change',function(){
      var calificacio=$("#G2992_C59992").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta4 = calificaTraducido;
      calificacion();
    });

    // Campo pregunta5
    $("#G2992_C59994").on('change',function(){
      var calificacio=$("#G2992_C59994").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta5 = calificaTraducido;
      calificacion();
    });

    // Campo pregunta6
    $("#G2992_C59996").on('change',function(){
      var calificacio=$("#G2992_C59996").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta6 = calificaTraducido;
      calificacion();
    });

    // Campo pregunta7
    $("#G2992_C59998").on('change',function(){
      var calificacio=$("#G2992_C59998").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta7 = calificaTraducido;
      calificacion();
    });

    // Campo pregunta8
    $("#G2992_C60000").on('change',function(){
      var calificacio=$("#G2992_C60000").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta8 = calificaTraducido;
      calificacion();
    });

    // Campo pregunta9
    $("#G2992_C60002").on('change',function(){
      var calificacio=$("#G2992_C60002").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta9 = calificaTraducido;
      calificacion();
    });

    // Campo pregunta10
    $("#G2992_C60004").on('change',function(){
      var calificacio=$("#G2992_C60004").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta10 = calificaTraducido;
      calificacion();
    });

    // Campo pregunta11
    $("#G2992_C60006").on('change',function(){
      
      var calificacio=$("#G2992_C60006").val();
      var calificaTraducido = traducirCalificacion(calificacio);
      pregunta11 = calificaTraducido;
      calificacion();
      if(calificacio>0){

        <?php 
        if (isset($_GET["registroId"])) {?>
          console.log("si existe");
          var idLoco22 = <?php echo $_GET["registroId"];?>;
          console.log("el valor es "+idLoco22);
          seteandoTP(idLoco22);
        <?php
        }else{?>
          console.log("no existe");
        <?php
        }
        ?>
      }
      
    });

    //funcion calificacion
    function calificacion(){

      var calificacionFinal = ((pregunta1*5)+(pregunta2*10)+(pregunta3*10)+(pregunta4*10)+(pregunta5*10)+(pregunta6*15)+(pregunta7*5)+(pregunta8*10)+(pregunta9*10)+(pregunta10*5)+(pregunta11*10))/100;

      $("#G2992_C61149").val(calificacionFinal);
      $("#G2992_C61149").blur();
    
    }

    //funcion para obtener el uniqueID
    function seteandoTP(idLocos){
      //Se setea idGestion
      $("#G2992_C66544").val($("#IdGestion").val());
      var isefue= idLocos;
      console.log(isefue); 
      $.ajax({
          url: "formularios/G2992/G2992_CRUD_DOS.php?traerGestionYLinkTR=si",
          type: "POST",
          data: {
            idLoco: isefue
          },
          success: function(data){
              console.log("Llego el link");
              $("#G2992_C66545").val("https://onuris.dyalogo.cloud:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid="+data+"&uid2="+data+"&canal=telefonia"); 
          },
          error : function(){
              alertify.error("No se llego el link");   
          },
          complete : function(){
              $("#loading").attr("hidden",true);
              $("#CerrarCalificacion2").click();
          }

      });

    }

    //Se oculta los botones standar de finalizar calificacion
    var buttonCalidadaToHide = document.getElementsByClassName("FinalizarCalificacion"); //buttonCalidadaToHide es un array
    for(var i = 0; i < buttonCalidadaToHide.length; i++){

      buttonCalidadaToHide[i].style.display = "none"; 
    }

    // Se crea nuestro propio boton de calificacion de calidad
    $("#s_9171").append("<a style='float: right;' class='btn btn-success pull-right FinalizarCalificacion2' role='button' >Finalizar Calificacion&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-paper-plane-o'></i></a>");

    //Se abre la modal para colocar el correo a donde se enviará la calificación
    $(".FinalizarCalificacion2").click(function(){
        $("#calidad").val("1");
        $("#enviarCalificacion2").modal("show");
    });

    // Se realiza el envío del email con los datos de la calificación de calidad
    $("#sendEmails2").click(function(){
      
      $("#Save").click();
      $("#loading").attr("hidden",false);
      var emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
      var cajaCorreos = $("#cajaCorreos2").val();

      if (cajaCorreos == null || cajaCorreos == "") {
          cajaCorreos = "";
          console.log("caja correos vacias!")
      }else{
          cajaCorreos = cajaCorreos.replace(/ /g, "");
          cajaCorreos = cajaCorreos.replace(/,,,,,/g, ",");
          cajaCorreos = cajaCorreos.replace(/,,,,/g, ",");
          cajaCorreos = cajaCorreos.replace(/,,,/g, ",");
          cajaCorreos = cajaCorreos.replace(/,,/g, ",");

          if (cajaCorreos[0] == ",") {
              cajaCorreos = cajaCorreos.substring(1);
          }

          if (cajaCorreos[cajaCorreos.length-1] == ",") {
              cajaCorreos = cajaCorreos.substring(0,cajaCorreos.length-1);
          }

          var porciones = cajaCorreos.split(",");
          console.log("porciones: " + porciones);
          
          for (var i = 0; i < porciones.length; i++) {
              if (!emailRegex.test(porciones[i])) {
                  porciones.splice(i, 1);
              }
          }

          cajaCorreos = porciones.join(",");
          console.log(cajaCorreos);
      }

      var formData = new FormData($("#FormularioDatos")[0]);
      formData.append("IdGestion",<?=$_GET['registroId'];?>);
      formData.append("IdGuion","2992");
      <?php 
        if (isset($_SESSION["IDENTIFICACION"])) {?>
          formData.append("IdCal",<?=$_SESSION["IDENTIFICACION"];?>);

        <?php    
        }elseif (isset($_GET['idUSUARI'])) {?>
          formData.append("IdCal",<?=$_GET['idUSUARI'];?>);
        <?php
        }else{?>
          formData.append("IdCal",<?=$_GET['usuario'];?>);
        <?php
        }
      ?>
      formData.append("Correos",cajaCorreos);

      //Se envían los valores de los campos que se enviaran por correo
      formData.append("G2992_C59955",$("#G2992_C59955").val());
      formData.append("G2992_C59988",$("#G2992_C59988").val());
      formData.append("G2992_C59990",$("#G2992_C59990").val());
      formData.append("G2992_C59992",$("#G2992_C59992").val());
      formData.append("G2992_C59994",$("#G2992_C59994").val());
      formData.append("G2992_C59996",$("#G2992_C59996").val());
      formData.append("G2992_C59998",$("#G2992_C59998").val());
      formData.append("G2992_C60000",$("#G2992_C60000").val());
      formData.append("G2992_C60002",$("#G2992_C60002").val());
      formData.append("G2992_C60004",$("#G2992_C60004").val());
      formData.append("G2992_C60006",$("#G2992_C60006").val());
      formData.append("G2992_C66545",$("#G2992_C66545").val());
      formData.append("G2992_C61149",$("#G2992_C61149").val());
      formData.append("G2992_C61151",$("#G2992_C61151").val());
      formData.append("G2992_C61153","now()");// fecha auditado
      formData.append("G2992_C61154",$("#G2992_C61154").val());// nombre auditor
      formData.append("origenCalidad","intrusion");

      $.ajax({
          url: "formularios/G2992/G2992_CRUD_DOS.php?EnviarCalificacion=si",
          type: "POST",
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          success: function(data){
            alertify.success("¡Calificación Enviada!");
            window.location.reload();
          },
          error : function(){
              alertify.error("No se pudo enviar la calificacion.");   
          },
          complete : function(){
              $("#loading").attr("hidden",true);
              $("#CerrarCalificacion2").click();
          }

      }); 
      
    });

  <?php endif; ?>


</script>