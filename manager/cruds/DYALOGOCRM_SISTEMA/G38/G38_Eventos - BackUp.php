
<?php
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include_once(__DIR__."../../../../../helpers/parameters.php");

   $url_crud_programa_tareas = base_url."cruds/DYALOGOCRM_SISTEMA/G38/G38_CRUD.php";
?>


<script>

    //Declaración de variables:
    var idEstpas = 0;
    var seccion ='';
    var nombreTarea = '';
    var activo = '';
    var tarRepetir = '';
    var tarCadaCuanto = '';
    var tarHor = '';
    var lunesActivo =  '';
    var martesActivo =  '';
    var miercolesActivo =  '';
    var juevesActivo =  '';
    var viernessActivo =  '';
    var sabadoActivo =  '';
    var domingoActivo =  '';
    var tarFinaliza =  '';
    var radioFecha =  '';
    let idNuevoTarHor =0;
    var html = '';
    var intRow_t = 0;
    let idNuevoAccion =0;
    let nuevaAccion='';
    let listaAcciones='';
    var opSelecTipoTarea = '';
    var opSelecParaQRegistros = '';
    var contadorAcc = 0;

    //aumentar contador
    $("#new_filtro_tareas").click(function(){
        contadorAcc++;
    });

    // Mostrar modal para agregar una nueva tarea
    $("#accion_nuevo").click(function(){

        agregarNuevaAccion(intRow_t,);
        $("#modVisorAccionesTareaProgramada").modal('show');


    });

    //Se agrega la funcion de datepicker al campo fecha para terminar la tarea
    $("#radioFecha").datepicker({
        language: "es",
        autoclose: true,
        todayHighlight: true,
        format: "yyyy-mm-dd"
    });

    //Acciones sobre el campo fecha de finalizacion
    $("#tarFinaliza").on("change",function(){
        // si termina EL es FECHA ESPECIFICA se desbloquea el campo fecha
        if($("#tarFinaliza").val() == 2){
            $('#radioFecha').prop('disabled',  false);
            $("#divFechaFinal").attr('hidden', false);
        }else{// de lo contrario se bloquea el campo FECHA
            $('#radioFecha').prop('disabled',  'disabled');
            $("#divFechaFinal").attr('hidden', true);
        }

    });

    // Mostrar modal para agregar una accion a la tarea
    $("#tarea_nuevo").click(function(){
        idEstpas = $("#id_estpas").val();
        //se invoca la funcion que agrega una nueva tarea a nivel de base de datos
        agregarNuevaTareaProgramada(idEstpas);
        $("#modVisorTareaProgramada").modal('show');

    });

    //Recargar Pagina
    $("#tarea_refrescar").click(function(){
        window.location.reload();
    })

    // Mostrar modal para agregar una accion a la tarea
    $(document).on('click', '.editarTarHor', function() {
        intRow_t = $(this).attr("idRow");
        console.log("traer datos con editar: ", intRow_t);

        $("#modVisorTareaProgramada").modal('show');
        getDatosTareaProgramada(intRow_t);
        getListaAcciones(intRow_t);
    });

    //Eliminar Condiciones
    function EliminarCondiciones(IdAccion){
        console.log("IdAccion: ", IdAccion);
        let IdEliminarrCondiciones = ajax({accion:'EliminarCondiciones', IdAccion: IdAccion},'application/x-www-form-urlencoded; charset=UTF-8');
        console.log("Respuesta Eliminar Condiciones: " + IdEliminarrCondiciones);
    }

    //Guardar Acciones
    function GuardarAccion(camposAccion, idNuevoAccion, idEstpas, ParaQueRegistros){
        if((idNuevoAccion == null) || (idNuevoAccion == "")){
            idNuevoAccion = $("#InputIdAccion").val();
        }
        
        EliminarCondiciones(idNuevoAccion);
        /*console.log("camposAccion: ", camposAccion);
        console.log("idNuevoAccion: ", idNuevoAccion);
        console.log("idEstpas: ", idEstpas);
        console.log("ParaQueRegistros: ", ParaQueRegistros);*/
        actualizarNuevaAccion(camposAccion ,idNuevoAccion, idEstpas, ParaQueRegistros);
    }

    //GUARDAR LA ACCION
    $("#accion_guardar_accion_nueva").click(function(){

        var valido = 1;
        var idEstpas = $("#id_estpas").val();
        var ParaQueRegistros = ($("#paraQueRegistros").val());
        var AsignarAgente= $("#asignarAgente").val();
        var TipoTarea= $("#tipoTarea").val();
        var AplicaLimite= $("#aplicaLimite").val();
        var CantidadLimite= $("#numbCantidadLimite").val();
        if((AsignarAgente == null) || (AsignarAgente == "")){
            AsignarAgente = $("#InputIdUsuario").val();
        }
        if((CantidadLimite == null) || (CantidadLimite == "")){
            CantidadLimite= "0";
        }

        if((TipoTarea == null) || (TipoTarea == "0")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "TIPO DE TAREA"',
                confirmButtonColor: '#2892DB'
            })
        }else if((ParaQueRegistros == null) || (ParaQueRegistros == "0")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "PARA QUE REGISTROS"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            if(TipoTarea == 3){
                if((AsignarAgente == null) || (AsignarAgente == "") || (AsignarAgente == "0")){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Debe Agregar Un Valor En El Campo "AL AGENTE"',
                        confirmButtonColor: '#2892DB'
                    })
                }else if((AplicaLimite == null) || (AplicaLimite == "")){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Debe Agregar Un Valor En El Campo "APLICAR UN LIMITE DE CANTIDAD"',
                        confirmButtonColor: '#2892DB'
                    })
                }else{
                    //CAPTURAR INFORMACION DEL MODAL
                    let camposAccion = [
                        {
                            "tipoTarea" : TipoTarea,
                            "alAgente" : AsignarAgente,
                            "paraQueRegistros" : ParaQueRegistros,
                            "aplicaLimite" : AplicaLimite,
                            "cantidadLimite" : CantidadLimite
                        }
                    ];
                    GuardarAccion(camposAccion, idNuevoAccion, idEstpas, ParaQueRegistros);
                }
            }else{
                //CAPTURAR INFORMACION DEL MODAL
                let camposAccion = [
                    {
                        "tipoTarea" : TipoTarea,
                        "alAgente" : AsignarAgente,
                        "paraQueRegistros" : ParaQueRegistros,
                        "aplicaLimite" : AplicaLimite,
                        "cantidadLimite" : CantidadLimite
                    }
                ];
                GuardarAccion(camposAccion, idNuevoAccion, idEstpas, ParaQueRegistros);
            }
        }
        
    });

    //Guardar tarea
    function GuardarTarea(camposTarea, idEstpas, idNuevoTarHor, intRow_t) {
        console.log("camposTarea: ", camposTarea);
        console.log("idEstpas: ", idEstpas);
        console.log("idNuevoTarHor: ", idNuevoTarHor);
        console.log("intRow_t: ", intRow_t);

        if(idNuevoTarHor && idNuevoTarHor >0){
            guardarNuevaTareaProgramada(camposTarea,idEstpas,idNuevoTarHor);
        }else if(intRow_t && intRow_t > 0){
            guardarNuevaTareaProgramada(camposTarea,idEstpas,intRow_t);
        }
        setTimeout(function(){
            window.location.reload();
        }, 2000);
        console.log("Recargando...");
    }

    //GUARDAR LA TAREA NUEVA
    $("#accion_guardar_tarea_nueva").click(function(){
        idEstpas = $("#id_estpas").val();

        //CAPTURAR INFORMACION DEL MODAL
        var NombreTarea= $("#txtNombreTarea").val();
        var TarRepetir= $("#tarRepetir").val();
        if((TarRepetir == null) || (TarRepetir == "0")){
            TarRepetir= "1";
        }
        var TarCadaCuanto= $("#tarCadaCuanto").val();
        var DiaDeCadaMes= $("#DiaDeCadaMes").val();
        var FechaCadaYear= $("#FechaCadaYear").val();
        var TarHor= $("#tarHor").val();
        var TarFinaliza= $("#tarFinaliza").val();
        
        /*console.log("NombreTarea: ", NombreTarea);
        console.log("TarRepetir: ", TarRepetir);
        console.log("TarCadaCuanto: ", TarCadaCuanto);
        console.log("DiaDeCadaMes: ", DiaDeCadaMes);
        console.log("FechaCadaYear: ", FechaCadaYear);
        console.log("TarHor: ", TarHor);
        console.log("TarFinaliza: ", TarFinaliza);*/

        //Checks Dias
        var Lunes= $("#lunesActivo").val();
        var Martes= $("#martesActivo").val();
        var Miercoles= $("#miercolesActivo").val();
        var Jueves= $("#juevesActivo").val();
        var Viernes= $("#viernesActivo").val();
        var Sabado= $("#sabadoActivo").val();
        var Domingo= $("#domingoActivo").val();

        if((NombreTarea == null) || (NombreTarea == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En Nombre De La Tarea',
                confirmButtonColor: '#2892DB'
            })

        }else if((TarHor == null) || (TarHor == "0")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Una "Hora De Ejecución"',
                confirmButtonColor: '#2892DB'
            })
        }else if((TarCadaCuanto == null) || (TarCadaCuanto == "0")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Diligenciar La Sección "REPETIR CADA"',
                confirmButtonColor: '#2892DB'
            })
        }else if((TarFinaliza == null) || (TarFinaliza == "0")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En "TERMINA EL"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            // crea un nuevo objeto `Date`
            var Ahora = new Date();
            var FechaHoy = String(Ahora);
            var ArrayFecha= FechaHoy.split(" ");
            var Hoy = Ahora.toLocaleDateString('en-US');            
            var HoraActual= ArrayFecha[4];
            var HoraProgra= TarHor;
            console.log("Ahora: ", Ahora);
            console.log("Hoy: ", Hoy);
            console.log("ArrayFecha: ", ArrayFecha);
            console.log("HoraActual: ", HoraActual);
            console.log("HoraProgra: ", HoraProgra);

            if(TarCadaCuanto == "1"){
                console.log("Cada Dia.. ");
                if(HoraActual => HoraProgra){
                    ArrayHoy= Hoy.split("/");
                    Year= ArrayHoy[2];
                    Mes= ArrayHoy[0];
                    Dia= ArrayHoy[1];
                    DiaN= parseInt(Dia);
                    Dia= DiaN+1;
                    FechaEjecucion= Year+"-"+Mes+"-"+Dia+" "+HoraProgra;
                }else if(HoraActual < HoraProgra){
                    ArrayHoy= Hoy.split("/");
                    Year= ArrayHoy[2];
                    Mes= ArrayHoy[0];
                    Dia= ArrayHoy[1];
                    FechaEjecucion= Year+"-"+Mes+"-"+Dia+" "+HoraProgra;
                }
                console.log("FechaEjecucion: ", FechaEjecucion);   
                
            }else if(TarCadaCuanto == "2"){
                console.log("Cada Semana.. ");
                if((Lunes == "0") && (Martes == "0") && (Miercoles == "0") && (Jueves == "0") && (Viernes == "0") && (Sabado == "0") && ( Domingo == "0")){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Debe Seleccionar Al Menos Un Día De La Semana',
                        confirmButtonColor: '#2892DB'
                    })
                    exit();
                }else{
                    ArrayHoy= Hoy.split("/");
                    Year= ArrayHoy[2];
                    Mes= ArrayHoy[0];
                    Dia= ArrayHoy[1];
                    DiaN= parseInt(Dia);
                    Dia= DiaN+1;
                    FechaEjecucion= Year+"-"+Mes+"-"+Dia+" "+HoraProgra;
                    console.log("FechaEjecucion: ", FechaEjecucion);
                }
                
            }else if(TarCadaCuanto == "3"){
                console.log("Cada Mes.. ");
                if((DiaDeCadaMes == null) || (DiaDeCadaMes == "")){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Debe Seleccionar Un Día Del Mes',
                        confirmButtonColor: '#2892DB'
                    })
                    exit();
                }else{
                    ArrayHoy= Hoy.split("/");
                    DiaActual= ArrayHoy[1];
                    if(DiaActual >= DiaDeCadaMes){
                        console.log("Dia Actual Mayor o Igual.. ");
                        Year= ArrayHoy[2];
                        Mes= ArrayHoy[0];
                        MesN= parseInt(Mes);
                        Mes= MesN+1;
                        Dia= DiaDeCadaMes;
                        FechaEjecucion= Year+"-"+Mes+"-"+Dia+" "+HoraProgra;
                    }else if(DiaActual < DiaDeCadaMes){
                        console.log("Dia Actual Menor.. ");
                        Year= ArrayHoy[2];
                        Mes= ArrayHoy[0];
                        Dia= DiaDeCadaMes;
                        FechaEjecucion= Year+"-"+Mes+"-"+Dia+" "+HoraProgra;
                    }
                    console.log("FechaEjecucion: ", FechaEjecucion);
                }

            }else if(TarCadaCuanto == "4"){
                console.log("Cada Año.. ");
                if((FechaCadaYear == null) || (FechaCadaYear == "")){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Debe Agregar Una Fecha De Ejecución',
                        confirmButtonColor: '#2892DB'
                    })
                    exit();
                }else{
                    ArrayHoy= Hoy.split("/");
                    MesActual= parseInt(ArrayHoy[0]);
                    ArrayFechaProgra= FechaCadaYear.split("-");
                    YearProgra= ArrayFechaProgra[0];
                    MesProgra= parseInt(ArrayFechaProgra[1]);
                    DiaProgra=  ArrayFechaProgra[2];

                    if(MesActual > MesProgra){
                        console.log("Mes Actual Mayor..");
                        Year= YearProgra;
                        Mes= MesProgra;
                        Dia= DiaProgra;
                        YearN= parseInt(Year);
                        Year= YearN+1;
                        FechaEjecucion= Year+"-"+Mes+"-"+Dia+" "+HoraProgra;
                    }else if(MesActual < MesProgra){
                        console.log("Mes Actual Menor..");
                        Year= YearProgra;
                        Mes= MesProgra;
                        Dia= DiaProgra;
                        FechaEjecucion= Year+"-"+Mes+"-"+Dia+" "+HoraProgra;
                    }else{
                        console.log("Son Iguales..");
                        ArrayHoy= Hoy.split("/");
                        DiaActual= ArrayHoy[1];
                        if(DiaActual >= DiaProgra){
                            console.log("Dia Actual Mayor o Igual.. ");
                            Year= YearProgra;
                            Mes= MesProgra;
                            Dia= DiaProgra;
                            YearN= parseInt(Year);
                            Year= YearN+1;
                            FechaEjecucion= Year+"-"+Mes+"-"+Dia+" "+HoraProgra;
                        }else if(DiaActual < DiaProgra){
                            console.log("Dia Actual Menor.. ");
                            Year= YearProgra;
                            Mes= MesProgra;
                            Dia= DiaProgra;
                            FechaEjecucion= Year+"-"+Mes+"-"+Dia+" "+HoraProgra;
                        }
                        
                    }
                    console.log("FechaEjecucion: ", FechaEjecucion);
                }
            }


            if(TarFinaliza == 2 ){
                var FechaFinal = $("#radioFecha").val();
                //console.log("FechaFinal:", FechaFinal);
                if((FechaFinal == null) || (FechaFinal == "")){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Debe Agregar Una Fecha De Finalización',
                        confirmButtonColor: '#2892DB'
                    })
                }else{
                    ArrayFechaEjecucion= FechaEjecucion.split(" ");
                    FechaEjecucion= ArrayFechaEjecucion[0];
                    //console.log("FechaEjecucion", FechaEjecucion);
                    if(FechaEjecucion > FechaFinal){
                        swal({
                            icon: 'error',
                            title: '🤨 Oops...',
                            text: '¡La Fecha De Ejecución Es Posterior, a La Fecha De Finalización!',
                            confirmButtonColor: '#2892DB'
                        })
                    }else{
                        let camposTarea = [
                            {
                                "nombreTarea" : NombreTarea,
                                "activo" : $("#tarConActivo").val(),
                                "tarRepetir" : TarRepetir,
                                "tarCadaCuanto" : TarCadaCuanto,
                                "tarHor" : TarHor,
                                "lunesActivo" : $("#lunesActivo").val(),
                                "martesActivo" : $("#martesActivo").val(),
                                "miercolesActivo" : $("#miercolesActivo").val(),
                                "juevesActivo" : $("#juevesActivo").val(),
                                "viernesActivo" : $("#viernesActivo").val(),
                                "sabadoActivo" : $("#sabadoActivo").val(),
                                "domingoActivo" : $("#domingoActivo").val(),
                                "tarFinaliza" : TarFinaliza,
                                "radioFecha" : FechaFinal,
                                "FechaEjecucion" : FechaEjecucion
                            }
                        ];
                        GuardarTarea(camposTarea, idEstpas, idNuevoTarHor, intRow_t);
                    }

                }
            }else{
                var FechaFinal = 0;
                let camposTarea = [
                    {
                        "nombreTarea" : NombreTarea,
                        "activo" : $("#tarConActivo").val(),
                        "tarRepetir" : TarRepetir,
                        "tarCadaCuanto" : TarCadaCuanto,
                        "tarHor" : TarHor,
                        "lunesActivo" : $("#lunesActivo").val(),
                        "martesActivo" : $("#martesActivo").val(),
                        "miercolesActivo" : $("#miercolesActivo").val(),
                        "juevesActivo" : $("#juevesActivo").val(),
                        "viernesActivo" : $("#viernesActivo").val(),
                        "sabadoActivo" : $("#sabadoActivo").val(),
                        "domingoActivo" : $("#domingoActivo").val(),
                        "tarFinaliza" : TarFinaliza,
                        "radioFecha" : FechaFinal,
                        "FechaEjecucion" : FechaEjecucion
                    }
                ];
                GuardarTarea(camposTarea, idEstpas, idNuevoTarHor, intRow_t);

            }
        }

    });

    //Funciones modal accion tarea
    $("#tipoTarea").on("change",function(){
        ConsultarAgentes();
        // Acciones sobre el campo Tipo de tarea
        if($("#tipoTarea").val() == 1 || $("#tipoTarea").val() == 2 || $("#tipoTarea").val() == 4){//cuando la opcion es activar o inactivar

            //se bloquea la lista agente
            $('#asignarAgente').prop('disabled', 'disabled');
            //se bloquea la lista aplicar a un limite
            $('#aplicaLimite').prop('disabled', 'disabled');
            //se bloquea el campo de cantidad de registros para los que aplica
            $('#numbCantidadLimite').prop('disabled', 'disabled');
            //se desbloquea la lista para que registros
            $('#paraQueRegistros').prop('disabled', false);

        }else if($("#tipoTarea").val() == 3){// cuando la opcion es asignar

            //se desbloquea la lista agente
            $('#asignarAgente').prop('disabled', false);
            //se desbloquea la lista aplicar a un limite
            $('#aplicaLimite').prop('disabled', false);
        }else {

            //se bloquea la lista agente
            $('#asignarAgente').prop('disabled', 'disabled');
            //se bloquea la lista para que registros
            $('#paraQueRegistros').prop('disabled', 'disabled');
            //se bloquea la lista aplicar a un limite
            $('#aplicaLimite').prop('disabled', 'disabled');
            //se bloquea el campo de cantidad de registros para los que aplica
            $('#numbCantidadLimite').prop('disabled', 'disabled');

        }
    });
    // Acciones sobre el campo APLICAR UN LIMITE DE CANTIDAD
    $("#paraQueRegistros").on("change",function(){
        if($("#paraQueRegistros").val() == 2){
            //se desbloquea el campo de aplicar a un limite
            $('#aplicaLimite').prop('disabled', false);
            //se bloquea el campo de cantidad de registros para los que aplica
            $('#numbCantidadLimite').prop('disabled', 'disabled');
            // se cambia el valor de secciones
            seccion = 'CONDICIONES_TAREAS';
            //Se muestra el boton agregar condiciones
            $("#div_filtros_tareas").show();
            //Si la seccion se oculto antes se valida y se vuelve a mostrar
            if($("#CONDICIONES_TAREAS").hide()){
                $("#CONDICIONES_TAREAS").show();
            }
            $("#new_filtro_tareas").click();

        }else{
             //se bloquea el campo de aplicar a un limite
            $('#aplicaLimite').prop('disabled', 'disabled');
            //se bloquea el campo de cantidad de registros para los que aplica
            $('#numbCantidadLimite').prop('disabled', 'disabled');
            //Se oculta el boton agregar condiciones
            $("#div_filtros_tareas").hide();
            //Se oculta la seccion de condiciones
            $("#CONDICIONES_TAREAS").hide();
        }
    });
    // Acciones sobre el campo APLICAR UN LIMITE DE CANTIDAD
    $("#aplicaLimite").on("change",function(){
        if($("#aplicaLimite").val() == -1){
            //se desbloquea el campo de cantidad de registros para los que aplica
            $('#numbCantidadLimite').prop('disabled', false);
            $('#divCantidadLimite').show();
        }else{
            //se bloquea el campo de cantidad de registros para los que aplica
            $('#numbCantidadLimite').prop('disabled', 'disabled');
            $('#divCantidadLimite').hide();

        }
    });
    //agregar condiciones a las tareas
    $('#div_filtros_tareas').click(function(){
        seccion = 'CONDICIONES_TAREAS';
        agregarFiltrosCondciones();
    });

    //Cambiar el valor a los campos tipo check
    $("#tarConActivo").on("change",function(){
        if(!$("#tarConActivo").is(':checked')){
            document.getElementById("tarConActivo").value = "0";
        }else {
            if($("#tarConActivo").is(':checked')){
                document.getElementById("tarConActivo").value = "-1"; 
            }
        }
    });
    
    $("#lunesActivo").on("change",function(){
        if(!$("#lunesActivo").is(':checked')){
            document.getElementById("lunesActivo").value = "0";
        }else {
            if($("#lunesActivo").is(':checked')){
                document.getElementById("lunesActivo").value = "-1"; 
            }
        }
    });

    $("#martesActivo").on("change",function(){
        if(!$("#martesActivo").is(':checked')){
            document.getElementById("martesActivo").value = "0";
        }else {
            if($("#martesActivo").is(':checked')){
                document.getElementById("martesActivo").value = "-1"; 
            }
        }
    });

    $("#miercolesActivo").on("change",function(){
        if(!$("#miercolesActivo").is(':checked')){
            document.getElementById("miercolesActivo").value = "0";
        }else {
            if($("#miercolesActivo").is(':checked')){
                document.getElementById("miercolesActivo").value = "-1"; 
            }
        }
    });

    $("#juevesActivo").on("change",function(){
        if(!$("#juevesActivo").is(':checked')){
            document.getElementById("juevesActivo").value = "0";
        }else {
            if($("#juevesActivo").is(':checked')){
                document.getElementById("juevesActivo").value = "-1"; 
            }
        }
    });

    $("#viernesActivo").on("change",function(){
        if(!$("#viernesActivo").is(':checked')){
            document.getElementById("viernesActivo").value = "0";
        }else {
            if($("#viernesActivo").is(':checked')){
                document.getElementById("viernesActivo").value = "-1"; 
            }
        }
    });

    $("#sabadoActivo").on("change",function(){
        if(!$("#sabadoActivo").is(':checked')){
            document.getElementById("sabadoActivo").value = "0";
        }else {
            if($("#sabadoActivo").is(':checked')){
                document.getElementById("sabadoActivo").value = "-1"; 
            }
        }
    });

    $("#domingoActivo").on("change",function(){
        if(!$("#domingoActivo").is(':checked')){
            document.getElementById("domingoActivo").value = "0";
        }else {
            if($("#domingoActivo").is(':checked')){
                document.getElementById("domingoActivo").value = "-1"; 
            }
        }
    });

    // FUNCIÓN PARA OCULTAR EL GIF DE CARGA
    function hideBlock(){
        try{
            $.unblockUI();
        }catch(e){}
    }

    // FUNCION QUE MUESTRA UNA ALERTA PARA LOS ERRORES
    function showError(data=null){
        let mensaje='Ocurrio un error y no se pudo procesar la solictud';
        if(data != null){
            mensaje=data;
        }
        alertify.error(mensaje);
    }

    // FUNCIÓN QUE PROCESA CADA PETICIÓN DEL ARCHIVO
    function ajax(data,contentType=false,cache=true,processData=true,retorno="JSON",url='<?=$url_crud_programa_tareas;?>'){
        let response='';
        $.ajax({
            url:url,
            async:false,
            method:'POST',
            dataType:retorno,
            data:data,
            cache:cache,
            processData:processData,
            contentType:contentType,
            success:function(data){
                response=data;
            },
            beforeSend:function(){
                //showBlock();
            },
            complete:function(){
                hideBlock();
            },
            error:function(){
                showError();
            }
        });
        
        return response;
    }

    function actualizarNuevaAccion(camposAccion,idNuevoAccion){
        var formData = new FormData();
        var reg = 0;
        var elementos2 = document.querySelectorAll("#accionDeLaTarea select, #accionDeLaTarea input");

        elementos2.forEach(function(elemento) {
            var nombre = elemento.name;
            var valor = elemento.value;
            formData.append(nombre, valor);
        });

        formData.append('contador' , contadorAcc);
        formData.append('idEstpas_2' , idEstpas);
        formData.append('idAccion',idNuevoAccion);

        if($("#paraQueRegistros").val() == 2){

            reg = 2;
            var elementos = document.querySelectorAll("#CONDICIONES_TAREAS select, #CONDICIONES_TAREAS input");
            
            // Agrega los valores de los elementos al objeto FormData
            elementos.forEach(function(elemento) {
                var nombre = elemento.name;
                var valor = elemento.value;
                formData.append(nombre, valor);
            });

            // Recorriendo los datos de FormData utilizando forEach
            formData.forEach(function(valor, clave, formulario) {
                console.log(`Clave: ${clave}, Valor: ${valor}`);
            });
        }
        
        let campos=ajax({accion:'DatosActualizarAccionTareaPrograma', idAccion: idNuevoAccion, camposAccion: camposAccion },'application/x-www-form-urlencoded; charset=UTF-8');

        formData.append('paraqueregistros_2' , reg);
        $.ajax({
            url  : "<?php echo $url_crud_programa_tareas;?>?agregar_condiciones_accion=true",
            type : "post",
            data : formData,
            cache: false,
            contentType: false,
            processData: false,
            success : function(data){
                
                
            },
            beforeSend : function(){
                /*$.blockUI({ 
                    baseZ: 2000, 
                    message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                });*/
            },
            complete : function(){
                $.unblockUI();
            }
        });

        
        setTimeout(function(){
            window.location.reload();
        },2000);
        console.log("Recargando...");

    }

    function getListaAcciones(idTareaCreada){

        html = '';
        opSelecTipoTarea = '';
        opSelecParaQRegistros = '';

        listaAcciones = ajax({accion:'ListarAccionTareaPrograma', idTarea: intRow_t},'application/x-www-form-urlencoded; charset=UTF-8');

        if(listaAcciones.estado){
            //console.log("lista Acciones:", listaAcciones.lista);
            $.each(listaAcciones.lista, function(i,item){
                
                console.log("tar pro -> tipo: " + item.TARPRO_Tipo_De_Tarea____b);
                switch (item.TARPRO_Tipo_De_Tarea____b) {
                    
                    case "1":
                        opSelecTipoTarea = '<option value="1" selected="true">ACTIVAR REGISTROS</option>';                                        
                        break;
                    case "2":
                        opSelecTipoTarea = '<option value="2" selected="true">INACTIVAR REGISTROS</option>';          
                        break;
                    case "3":
                        opSelecTipoTarea = '<option value="3" selected="true">ASIGNAR REGISTROS</option>';
                        break;
                    case "4":
                        opSelecTipoTarea = '<option value="4" selected="true">DESASIGNAR REGISTROS</option>';
                        break;
                    default:
                        opSelecTipoTarea = '<option value="0" selected="true">SELECCIONE UNA ACCIÓN</option>';   
                }

                console.log("tar pro -> para que registros: " + item.TARPRO_Para_Que_Registros____b);
                switch (item.TARPRO_Para_Que_Registros____b) {
                    case "1":
                        opSelecParaQRegistros = '<option value="1" selected="true">TODOS</option>';                                        
                        break;
                    case "2":
                        opSelecParaQRegistros = '<option value="2" selected="true">CONDICIONES</option>';           
                        break;
                    default:
                    opSelecParaQRegistros = '<option value="0" selected="true">SELECCIONE</option>';   
                }
                /*console.log(opSelecTipoTarea);
                console.log("****** // *******");
                console.log(opSelecParaQRegistros);*/

                var IdAgente= item.TARPRO_ConsInte__USUARI____b;
                var AgenteAsignado= item.NombreAgente;
                //console.log("AgenteAsignado: ", AgenteAsignado);

                html+='<tr class="tarPro_row" id="tarPro_row_' + item.TARPRO_ConsInte__b + '">';
                    html+= '<td class="col-md-2 col-xs-2 responsiveDiv">';
                        html+= '<div class="form-group">';
                            html+= '<select name="listTipoTarea" id="listTipoTarea" class="form-control" disabled>' + opSelecTipoTarea + '</select>';
                        html+= '</div>';
                    html+= '</td>';

                    html+= '<td class="col-md-3 col-xs-3 responsiveDiv">';
                        html+= '<div class="form-group">';
                            html+= '<input type="text" name="AgenteAsignado" id="AgenteAsignado" class="form-control" value="' + AgenteAsignado + '" disabled/>';
                        html+= '</div>';
                    html+= '</td>';

                    html+= '<td class="col-md-2 col-xs-2 responsiveDiv">';
                        html+= '<div class="form-group">';
                            html+= '<select name="listParaQueRegistros" id="listParaQueRegistros" class="form-control" disabled>' + opSelecParaQRegistros + '</select>';
                        html+= '</div>';
                    html+= '</td>';
                    html+= '<td class="col-md-4 responsiveDiv">';
                        html+= '<div class="form-group" style="text-align: center;">';
                            html+= '<button class="btn btn-sm btn-primary editarTarPro" type="button" name="editarTarPro_'+item.TARPRO_ConsInte__b+'" id="editarTarPro_'+item.TARPRO_ConsInte__b+'" idRow="'+item.TARPRO_ConsInte__b+'" onclick="ActualizarAccion('+item.TARPRO_ConsInte__b+')"><i class="fa fa-edit" aria-hidden="true">&nbsp;</i></button>';
                            html+= '<div class="btn" hidden></div>';
                            html+= '<button class="btn btn-danger btn-sm eliminarTarPro" type="button" name="eliminarTarPro_'+item.TARPRO_ConsInte__b+'" id="eliminarTarPro_'+item.TARPRO_ConsInte__b+'" idRow="'+item.TARPRO_ConsInte__b+'" onclick="EliminarAccion('+item.TARPRO_ConsInte__b+')"><i class="fa fa-trash-o"></i></button>';
                            html+= '<input type="hidden" name="tarpro_id_'+item.TARPRO_ConsInte__b+'" id="tarpro_id_'+item.TARPRO_ConsInte__b+'" value="'+item.TARPRO_ConsInte__b+'">';
                        html+='</div>';
                    html+= '</td>';
                html += '</tr>';
            });
        }else{
            alertify.warning(listaAcciones.mensaje);
        }
        // Obtén el elemento de la tabla por su id
        var tableElementAcciones = document.getElementById("divParametrosAccionesTareasProgramadas");

        // Cambia el texto del label
        tableElementAcciones.innerHTML = html;


    }
 

    function agregarNuevaAccion(idTareaCreada){

        ConsultarAgentes();

        nuevaAccion=ajax({accion:'CrearAccionTareaPrograma', idTarea: intRow_t, paso: idEstpas},'application/x-www-form-urlencoded; charset=UTF-8');
        console.log("respuesta de agregar nueva accion: " + nuevaAccion.idNuevaAccion);
        idNuevoAccion = nuevaAccion.idNuevaAccion;
        $("#divCantidadLimite").prop('hidden', true);
        $("#divGenerarReporte").prop('hidden', true);

    }

    function getDatosTareaProgramada(intRow_t){

        let campos=ajax({accion:'DatosTareaPrograma', idTarea: intRow_t},'application/x-www-form-urlencoded; charset=UTF-8');

        if(campos.estado){
            console.log("campos.lista: ", campos.lista);
            $.each(campos.lista, function(i,item){
                var FechaHora= item.TARHOR_FecProxEje_b;
                var HoraEjecucion= FechaHora.substring(10);
                var FechaEjecucion= FechaHora.substring(0, 10);
                var DiaEjecucion= FechaHora.substring(8, 10);
                $("#txtNombreTarea").val(item.TARHOR_Nombre____b).change();
                $("#ProximaEjecucion").val(FechaHora).change();
                if(item.TARHOR_Activo____b == -1){
                    document.getElementById("tarConActivo").value = "-1";
                    document.getElementById("tarConActivo").checked = true;
                }
                $("#tarRepetir").val(item.TARHOR_Cantidad____b).change();
                $("#tarCadaCuanto").val(item.TARHOR_Unidad____b).change();
                $("#tarHor").val(item.TARHOR_Hora_Ejecucion____b).change();
                $("#DiaDeCadaMes").val(DiaEjecucion).change();
                $("#FechaCadaYear").val(FechaEjecucion).change();

                if(item.TARHOR_Lunes____b == -1){
                    document.getElementById("lunesActivo").value = "-1";
                    document.getElementById("lunesActivo").checked = true;
                }
                if(item.TARHOR_Martes____b == -1){
                    document.getElementById("martesActivo").value = "-1";
                    document.getElementById("martesActivo").checked = true;
                }
                if(item.TARHOR_Miercoles____b == -1){
                    document.getElementById("miercolesActivo").value = "-1";
                    document.getElementById("miercolesActivo").checked = true;
                }
                if(item.TARHOR_Jueves____b == -1){
                    document.getElementById("juevesActivo").value = "-1";
                    document.getElementById("juevesActivo").checked = true;
                }
                if(item.TARHOR_Viernes____b == -1){
                    document.getElementById("viernesActivo").value = "-1";
                    document.getElementById("viernesActivo").checked = true;
                }
                if(item.TARHOR_Sabado____b == -1){
                    document.getElementById("sabadoActivo").value = "-1";
                    document.getElementById("sabadoActivo").checked = true;
                }
                if(item.TARHOR_Domingo____b == -1){
                    document.getElementById("domingoActivo").value = "-1";
                    document.getElementById("domingoActivo").checked = true;
                }
                $("#tarFinaliza").val(item.TARHOR_Finaliza____b).change();
                $("#radioFecha").val(item.TARHOR_Fecha_Finalizacion____b).change();
                
            });
        }else{
            alertify.warning(campos.mensaje);
        }

    }

    function guardarNuevaTareaProgramada(arrayDatosNuevaTarea,idEstpas,idNuevoTarHor){
        
        let actualizarDatosTareaProgramada = ajax({accion:'GuardarProgramacion', paso: idEstpas, idNuevoTarHor: idNuevoTarHor, arrayDatosNuevaTarea : arrayDatosNuevaTarea},'application/x-www-form-urlencoded; charset=UTF-8');

        console.log("actualizarDatosTareaProgramada: ", actualizarDatosTareaProgramada);
        alertify.success(actualizarDatosTareaProgramada.mensaje);

    }

    function agregarNuevaTareaProgramada(idEstpas){

        //nombreTarea = $("#txtNombreTarea").val();
        idNuevoTarHor = ajax({accion:'AgregarProgramacion', paso: idEstpas, nombre: 'default'},'application/x-www-form-urlencoded; charset=UTF-8');
        console.log("respuesta de agregar: " + idNuevoTarHor.idNuevaTarea);
        idNuevoTarHor = idNuevoTarHor.idNuevaTarea;
        $("#divProgramacionAcciones").hide();

    }

    function getListaTareas(idEstpas){

        html = '';
        
        let camposDeCadaTarea=ajax({accion:'Listar', paso: idEstpas},'application/x-www-form-urlencoded; charset=UTF-8');

        if(camposDeCadaTarea.estado){
            $.each(camposDeCadaTarea.lista, function(i,item){
                html+='<tr class="tarHor_row" id="tarHor_row_' + item.TARHOR_ConsInte__b + '">';
                    html+= '<td class="col-md-3 col-xs-3 responsiveDiv">';
                        html+= '<div class="form-group">';
                            html+= '<input type="text" name="tar_id_'+item.TARHOR_ConsInte__b +'" class="form-control" value="'+item.TARHOR_Nombre____b +'" disabled>';
                        html+= '</div>';
                    html+= '</td>';
                    html+= '<td class="col-md-3 responsiveDiv">';
                        html+= '<div class="form-group" style="text-align: center;">';
                            html+= '<button class="btn btn-sm btn-primary editarTarHor" type="button" name="editarTarHor_'+item.TARHOR_ConsInte__b+'" id="editarTarHor_'+item.TARHOR_ConsInte__b+'" idRow="'+item.TARHOR_ConsInte__b+'"><i class="fa fa-edit" aria-hidden="true">&nbsp;</i></button>';
                            html+= '<div class="btn" hidden></div>';
                            html+= '<button class="btn btn-danger btn-sm eliminarTarHor" type="button" name="eliminarTarHor_'+item.TARHOR_ConsInte__b+'" id="eliminarTarHor_'+item.TARHOR_ConsInte__b+'" idRow="'+item.TARHOR_ConsInte__b+'" onclick="EliminarTarea('+item.TARHOR_ConsInte__b+')"><i class="fa fa-trash-o"></i></button>';
                            html+= '<input type="hidden" name="tarhor_id_'+item.TARHOR_ConsInte__b+'" id="tarhor_id_'+item.TARHOR_ConsInte__b+'" value="'+item.TARHOR_ConsInte__b+'">';
                        html+='</div>';
                    html+= '</td>';
                html += '</tr>';
            });
        }else{
            console.log(camposDeCadaTarea.mensaje);
            //alertify.warning(camposDeCadaTarea.mensaje);
        }
        // Obtén el elemento de la tabla por su id
        var tableElement = document.getElementById("divParametrosTareasProgramadas");

        // Cambia el texto del label
        tableElement.innerHTML = html;

        // Obtén el elemento del label por su id
        var labelElement = document.getElementById("respuestaNoHayTareas");

        // Cambia el texto del label
        labelElement.innerHTML = camposDeCadaTarea.mensaje;
    }


    //Funcion que se ejecuta al cargar la pagina y trae el listado de tareas de la campaña
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

        //console.log("calor de estpas:" + $("#id_estpas").val());
        idEstpas = $("#id_estpas").val();
        getListaTareas(idEstpas);
            
    });

    //Funcion Para Eliminar Tareas
    function EliminarTarea(IdTarea){
        console.log("IdTarea: ", IdTarea);
        swal({
            title: "¿Está Seguro?",
            text: "¡No Podrás Recuperar Esta Tarea!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar!",
            closeOnConfirm: false
            },
        function(){
            let IdEliminarTarea = ajax({accion:'EliminarTarea', IdTarea: IdTarea},'application/x-www-form-urlencoded; charset=UTF-8');
            console.log("respuesta de eliminar Tareas: " + IdEliminarTarea);
            swal({
                title: '¡Eliminado   🗑!',
                text: 'La Tarea Se Ha Eliminado Exitosamente!',
                icon: 'success',
                showConfirmButton: false,
                confirmButtonColor: '#2892DB',
                timer: 2000
            })
            
            setTimeout(function(){
                window.location.reload();
            }, 2000);
            console.log("Recargando...");
        });
 
    };

    //Funcion Para Eliminar Accion
    function EliminarAccion(IdAccion){
        console.log("IdAccion: ", IdAccion);
        swal({
            title: "¿Está Seguro?",
            text: "¡No Podrás Recuperar Esta Acción!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar!",
            closeOnConfirm: false
            },
        function(){
            let IdEliminarAccion = ajax({accion:'EliminarAccion', IdAccion: IdAccion},'application/x-www-form-urlencoded; charset=UTF-8');
            console.log("respuesta de eliminar Accion: " + IdEliminarAccion);
            swal({
                title: '¡Eliminado   🗑!',
                text: 'La Accion Se Ha Eliminado Exitosamente!',
                icon: 'success',
                showConfirmButton: false,
                confirmButtonColor: '#2892DB',
                timer: 2000
            })
            
            setTimeout(function(){
                window.location.reload();
            }, 2000);
            console.log("Recargando...");
        });

    }

    //Funcion Para Consultar Agentes
    function ConsultarAgentes(){
        var accion= 'ConsultarAgentes';
        var IdEstpas = $("#id_estpas").val();
        
        let Formulario = new FormData();
        Formulario.append('accion', accion);
        Formulario.append('IdEstpas', IdEstpas);
        $.ajax({
            url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G38/Controller/ConsultarListas.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: Formulario,
            success: function(php_response){
                Respuesta = php_response.msg;
                if (Respuesta == "Ok"){
                    var ListaAgentes= '<option disabled selected>Elige Una Opción</option>';
                    ListaAgentes+= php_response.Respuesta;
                    //console.log("ListaAgentes: ", ListaAgentes);
                    //console.log(php_response.msg);
                    var SelectAgentes = document.getElementById("asignarAgente");
                    SelectAgentes.innerHTML = ListaAgentes;
                }else if (Respuesta == "Nada"){
                    console.log(php_response.msg);
                    console.log(php_response.Respuesta);
                }else if (Respuesta == "Error"){
                    console.log(php_response.msg);
                    console.log(php_response.Falla);
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                console.log(php_response.msg);
                console.log(php_response);
            }
        });
        
    };

    //Funcion Para Consultar Condiciones
    function ConsultarCondiciones(Dato){
        var IdAccion= Dato;
        console.log("IdAccion: ", IdAccion);
        let Formulario = new FormData();
        Formulario.append('IdAccion', IdAccion);
        $.ajax({
            url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G38/Controller/ConsultaCondiciones.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: Formulario,
            success: function(php_response) {
                Respuesta = php_response.msg;
                var Resultado= php_response.ListaCondicion;
                var ArrayCondicion= Resultado[0];
                console.log("Resultado: ", Resultado);
                //console.log("ArrayCondicion: ", ArrayCondicion);

                $("#div_filtros_tareas").show();
                $("#CONDICIONES_TAREAS").show();
                var Contador= contadorAcc;
                var ArrayCierres= [];
                var ArrayValorCierres= [];
                Resultado.forEach(Cond => {
                    Contador= Contador+1;
                    //console.log("Vuelta # ", Contador);
                    IdCondicion= Cond[0];
                    Separador= Cond[1];
                    Campo = Cond[2];
                    Condicion_1 = Cond[3];
                    Valor = Cond[4];
                    SeparadorFinal = Cond[5];
                    $("#new_filtro_tareas").click();

                    /*console.log("IdCondicion: ", IdCondicion);
                    console.log("Separador: ", Separador);
                    console.log("Campo: ", Campo);
                    console.log("Condicion_1: ", Condicion_1);
                    console.log("Valor: ", Valor);
                    console.log("SeparadorFinal: ", SeparadorFinal);*/

                    if(Separador == " WHERE ( "){
                        var SelectApertura= document.getElementById("andOr_"+Contador);
                        SelectApertura.value= "(";
                    }else{
                        var SelectApertura= document.getElementById("andOr_"+Contador);
                        SelectApertura.value= Separador;
                    }

                    var SelectPregun= document.getElementById("pregun_"+Contador);
                    SelectPregun.value= Campo;

                    if(Condicion_1 == "="){
                        ValorCondicion= "IGUAL A";
                    }else if(Condicion_1 == " < "){
                        ValorCondicion= "MENOR QUE";
                    }else if(Condicion_1 == ">"){
                        ValorCondicion= "MAYOR QUE";
                    }else if(Condicion_1 == "!="){
                        ValorCondicion= "DIFERENTE DE";
                    }else if(Condicion_1 == "LIKE_2"){
                        ValorCondicion= "SIMILAR A";
                    }else{
                        ValorCondicion= Condicion_1;
                    }
                    
                    SelectCondicion_1= document.getElementById("condicion_"+Contador);
                    var OpcionActual= '<option value="'+Condicion_1+'" disabled selected>'+ValorCondicion+'</option>';
                    SelectCondicion_1.innerHTML = OpcionActual;

                    var SelectValor= document.getElementById("valor_"+Contador);
                    SelectValor.value= Valor;

                    if((SeparadorFinal == " ) ") || (SeparadorFinal == " )  ) ")){
                        var OpcionCierre= '<option value="'+SeparadorFinal+'" disabled selected>'+SeparadorFinal+'</option>';
                        var IdCierre= "cierre"+Contador;
                        ArrayCierres.push(IdCierre);
                        ArrayValorCierres.push(OpcionCierre);  
                    }

                });
                
                ArrayCierres.forEach(IdCampo => {
                    ArrayValorCierres.forEach(Option => {
                        var SelectCierre= document.getElementById(IdCampo);
                        var OpcionCierre= Option;
                        SelectCierre.innerHTML= OpcionCierre;
                    });
                });
                console.log("Contador: ", Contador);

            },
            error: function(php_response) {
                swal({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response.msg);
                console.log(php_response);
            }
        });
    }

    //Cerrar Condiciones
    function CerrarCondiciones(){

        $("#CONDICIONES_TAREAS").hide();
        $("#tipoTarea").val("0");
        $("#asignarAgente").val("Elige Una Opción");
        $("#paraQueRegistros").val("0");
        $("#aplicaLimite").val("0");
        $("#numbCantidadLimite").val("0");

        var ListaCondiciones= document.getElementById("CONDICIONES_TAREAS");
        ListaCondiciones.innerHTML = " ";
        
        $("#modVisorAccionesTareaProgramada").modal('hide');
        //window.location.reload();
    };

    //Cerrar Tareas
    function CerrarTareas(){
        $("#txtNombreTarea").val("");
        $("#ProximaEjecucion").val("");
        $("#tarCadaCuanto").val("0");
        $("#tarHor").val("0");
        $("#tarFinaliza").val("0");
        $("#radioFecha").val("");
        $("#DiaDeCadaMes").val("0");
        $("#FechaCadaYear").val("");
        $('#tarConActivo').prop("checked", false);
        $('#domingoActivo').prop("checked", false);
        $("#lunesActivo").prop("checked", false);
        $("#martesActivo").prop("checked", false);
        $("#miercolesActivo").prop("checked", false);
        $("#juevesActivo").prop("checked", false);
        $("#viernesActivo").prop("checked", false);
        $("#sabadoActivo").prop("checked", false);
        $("#domingoActivo").prop("checked", false);
        $("#modVisorTareaProgramada").modal('hide');
    };

    //Consultar y Mostrar Formulario Actualizar Acciones
    function ActualizarAccion(Dato){

        let Formulario = new FormData();
        var IdEditar= Dato
        var CampoIdAccion= document.getElementById("InputIdAccion");
        CampoIdAccion.value= IdEditar;
        Formulario.append('IdEditar', IdEditar);

        $.ajax({
            url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G38/Controller/ConsultaAccionEditar.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: Formulario,
            success: function(php_response) {
                Respuesta = php_response.msg;
                if (Respuesta == "Ok"){
                    console.log(php_response.msg);
                    var Resultado= php_response.ListaAccion;
                    var ArrayAccion= Resultado[0];
                    console.log("ArrayAccion: ", ArrayAccion);

                    var IdAccion= ArrayAccion[0];
                    var IdTarea= ArrayAccion[1];
                    var TipoTarea= ArrayAccion[2];
                    var ParaQueRegistros= ArrayAccion[3];
                    var IdUsuario= ArrayAccion[4];
                    var Limite= ArrayAccion[5];
                    var CantidadLimite= ArrayAccion[6];
                    var SentenciaSQL= ArrayAccion[7];

                    /*console.log("IdAccion: ", IdAccion);
                    console.log("IdTarea: ", IdTarea);
                    console.log("TipoTarea: ", TipoTarea);
                    console.log("ParaQueRegistros: ", ParaQueRegistros);
                    console.log("IdUsuario: ", IdUsuario);
                    console.log("Limite: ", Limite);
                    console.log("CantidadLimite: ", CantidadLimite);
                    console.log("SentenciaSQL: ", SentenciaSQL);*/
                    
                    SelectTipoTarea= document.getElementById("tipoTarea");
                    SelectTipoTarea.value = TipoTarea;

                    if(TipoTarea == 3) {
                        $("#divGenerarReporte").prop('hidden', false);
                    }else{
                        $("#divGenerarReporte").prop('hidden', true);
                    }
                    
                    if((IdUsuario == null) || (IdUsuario == "")){
                        var IdUsuario= "0";
                        ConsultarAgentes();
                        $("#asignarAgente").prop('disabled', true);
                    }else{
                        $("#asignarAgente").prop('disabled', false);
                    }
                    console.log("IdUsuario: ", IdUsuario);
                    SelectAgente= document.getElementById("asignarAgente");
                    var NombreUsuario= php_response.NombreUsuario;
                    console.log("NombreUsuario: ", NombreUsuario);
                    var OpcionActual= '<option value="'+IdUsuario+'" disabled selected>'+NombreUsuario+'</option>';
                    SelectAgente.innerHTML= OpcionActual;

                    InputIdUsuario= document.getElementById("InputIdUsuario");
                    InputIdUsuario.value = IdUsuario;

                    if((Limite == null) || (Limite == "")){
                        var Limite= "0";
                        $("#aplicaLimite").prop('disabled', true);
                    }
                    console.log("Limite: ", Limite);
                    SelectAgente= document.getElementById("aplicaLimite");
                    SelectAgente.value = Limite;
                    
                    if((CantidadLimite == null) || (CantidadLimite == "")){
                        var CantidadLimite= "0";
                        $("#numbCantidadLimite").prop('disabled', true);
                    }
                    console.log("CantidadLimite: ", CantidadLimite);
                    SelectAgente= document.getElementById("numbCantidadLimite");
                    SelectAgente.value = CantidadLimite;


                    if(ParaQueRegistros == 2){
                        ConsultarCondiciones(IdAccion);
                    }else{
                        $("#CONDICIONES_TAREAS").hide();
                        $("#div_filtros_tareas").hide();
                    }
                    
                    SelectParaQueRegistros= document.getElementById("paraQueRegistros");
                    SelectParaQueRegistros.value = ParaQueRegistros;


                }else if (Respuesta == "Nada"){
                    console.log(php_response.msg);
                    swal({
                        icon: 'info',
                        title: ' 🤷🏽‍♂️  ¡Nada Por Aqui, Nada Por Alla! ', 
                        text: '!No Se Encontraron Resultados Para Esta Acción!',
                        confirmButtonColor: '#2892DB'
                    })
                }else if (Respuesta == "Error"){
                    swal({
                        icon: 'error',
                        title: '¡Error Al Consultar Información!  🤨',
                        text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response.Falla);
                }
            },
            error: function(php_response) {
                swal({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response.msg);
                console.log(php_response);
            }
        });

        $('#modVisorAccionesTareaProgramada').modal();
    };


    //Funcion para configurar dias, semanas o meses
    $("#tarCadaCuanto").change(function(){
        ValorCadaCuanto= $(this).val();
        console.log("ValorCadaCuanto: ", ValorCadaCuanto);
        document.getElementById("lunesActivo").checked = false;
        document.getElementById("martesActivo").checked = false;
        document.getElementById("miercolesActivo").checked = false;
        document.getElementById("juevesActivo").checked = false;
        document.getElementById("viernesActivo").checked = false;
        document.getElementById("sabadoActivo").checked = false;
        document.getElementById("domingoActivo").checked = false;
        if(ValorCadaCuanto == 1){
            $("#divHora").attr('hidden', false);
            $("#divSeRepiteEl").attr('hidden', true);
            $("#divDiaDeCadaMes").attr('hidden', true);
            $("#divFechaCadaYear").attr('hidden', true);
        }else if(ValorCadaCuanto == 2){
            $("#divHora").attr('hidden', false);
            $("#divSeRepiteEl").attr('hidden', false);
            $("#divDiaDeCadaMes").attr('hidden', true);
            $("#divFechaCadaYear").attr('hidden', true);
        }else if(ValorCadaCuanto == 3){
            $("#divHora").attr('hidden', false);
            $("#divSeRepiteEl").attr('hidden', true);
            $("#divDiaDeCadaMes").attr('hidden', false);
            $("#divFechaCadaYear").attr('hidden', true);
        }else if(ValorCadaCuanto == 4){
            $("#divHora").attr('hidden', false);
            $("#divSeRepiteEl").attr('hidden', true);
            $("#divDiaDeCadaMes").attr('hidden', true);
            $("#divFechaCadaYear").attr('hidden', false);
        }else{
            $("#divHora").attr('hidden', true);
            $("#divSeRepiteEl").attr('hidden', true);
            $("#divDiaDeCadaMes").attr('hidden', true);
            $("#divFechaCadaYear").attr('hidden', true);
        }
    })

    //Funcion para Generar Reporte
    $("#BtnGenerarReporte").click(function(){
        let FormReporte = new FormData();
        var IdAccion= document.getElementById("InputIdAccion").value;
        var IdUsuario= document.getElementById("InputIdUsuario").value;
        console.log("IdAccion: ", IdAccion);
        console.log("IdUsuario: ", IdUsuario);
        FormReporte.append('IdAccion', IdAccion);
        FormReporte.append('IdUsuario', IdUsuario);

        //Consultar Condiciones Guardadas
        $.ajax({
            url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G38/Controller/ConsultaReporte.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormReporte,
            success: function(php_response) {
                Respuesta= php_response.msg;
                Resultado= php_response.Respuesta;
                console.log("Resultado: ", Resultado);

                //Contruccion Consulta Dinamica
                ArrayConsulta= Resultado.split(" WHERE ");
                ArrayUpdate= ArrayConsulta[0].split(" ");
                Condiciones= ArrayConsulta[1];
                BaseDatos= ArrayUpdate[1];
                Inner= ArrayUpdate[2];
                if(BaseDatos == "UPDATE"){
                    BaseDatos= ArrayUpdate[2];
                    Inner= ArrayUpdate[3];
                }
                
                /*console.log("ArrayConsulta: ", ArrayConsulta);
                console.log("ArrayUpdate: ", ArrayUpdate);*/
                console.log("BaseDatos: ", BaseDatos);

                if(Inner == "INNER"){
                    BaseDatos2= ArrayUpdate[4];
                    Llave= ArrayUpdate[6];
                    Llave2= ArrayUpdate[8];
                    Consulta= "SELECT * FROM "+BaseDatos+" INNER JOIN "+BaseDatos2;
                    NuevaConsulta= Consulta+" ON "+Llave+" = "+Llave2+" WHERE ";
                
                }else{
                    Consulta= "SELECT * FROM "+BaseDatos;
                    NuevaConsulta= Consulta + " WHERE ";
                }

                //Identificar Parentesis y Fechas
                var ContadorL= 0;
                var Comprobar= false;
                for (let i= 0; i < Resultado.length; i++) {
                    const Letra = Resultado[i];
                    if(Letra == "(" ){
                        Comprobar= "Ok";
                    }
                    if(Letra == "-" ){
                        ContadorL= ContadorL + 1;
                    }
                }

                //Si Existen Fechas
                if(ContadorL > 1){
                    for (let i= 0; i < Condiciones.length; i++) {
                        var Posicion= i;
                        const Letra_1 = Condiciones[Posicion];
                        const Letra_2 = Condiciones[Posicion+3];
                        
                        if((Letra_1 == "-") && (Letra_2 == "-" )){
                            var PosicionCambiar= Posicion-7;
                            var LetraCambiar= Condiciones[PosicionCambiar];

                            if(LetraCambiar == "=" ){
                                console.log(" ------- > ENTRO <--------- ");
                                var Rango_1= Condiciones.substr(0, PosicionCambiar);
                                var Posicion2= PosicionCambiar+1;
                                console.log("Posicion2: ", Posicion2);
                                var PosicionFinal= Condiciones.length;
                                var Rango_2= Condiciones.substr(Posicion2, PosicionFinal);
                                console.log("Rango_1: ", Rango_1);
                                console.log("Rango_2: ", Rango_2);
                                Pos1= PosicionFinal-3
                                Pos2= PosicionFinal-2
                                Dia= Condiciones.substr(Pos1, Pos2);
                                NewDia= parseInt(Dia) + 1;
                                Pos1= Rango_2.length;
                                Pos2= Pos1 - 3;
                                var FechaNew= Rango_2.substr(0, Pos2);
                                var FechaGenerica= FechaNew+NewDia+"'";
                                var Condiciones= Rango_1+"BETWEEN"+Rango_2+" AND"+FechaGenerica;
                                var CantidadLetras= Resultado.length;
                                console.log("PosicionCambiar: ", PosicionCambiar);
                                console.log("CantidadLetras: ", CantidadLetras);
                                if(PosicionCambiar > CantidadLetras){
                                    break;
                                }
                               
                            }

                        }
                        
                    }
                    
                }
                console.log("NuevaConsulta: ", NuevaConsulta);
                console.log("Condiciones: ", Condiciones);

                if(Comprobar == "Ok"){
                    ConsultaFinal= NuevaConsulta+Condiciones+");";
                }else{
                    ConsultaFinal= NuevaConsulta+Condiciones+";";
                }
                console.log("ConsultaFinal: ", ConsultaFinal);
                FormReporte.append('ConsultaFinal', ConsultaFinal);
                
                //Consultar Con La Nueva SQL Generada
                $.ajax({
                    url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G38/Controller/ConsultaReporte.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: FormReporte,
                    success: function(php_response) {
                        ResultadoR= php_response.Respuesta;
                        if (ResultadoR ==  undefined) {
                            swal({
                                icon: 'info',
                                title: ' 🤷🏽‍♂️  ¡Nada Por Aqui, Nada Por Alla! ', 
                                text: '!No Se Encontraron Resultados Para Esta Condiciones!',
                                confirmButtonColor: '#2892DB'
                            })
                        }
                        console.log("ResultadoR: ", ResultadoR);

                        //Pintar Columnas En la Tabla
                        function PintarColumnas(Columnas) {
                            var Contador= 0;
                            Columnas.forEach(Columna => {
                                var row= $("#ContTitulos");
                                Contador= Contador+1;
                                if((Contador == 1)||(Contador == 3)){
                                    row.append($("<th style='position: relative;' hidden='true'>").text(Columna));
                                }else if(Contador > 7){
                                    row.append($("<th style='position: relative;' hidden='true'>").text(Columna));
                                }else{
                                    row.append($("<th style='position: relative;'>").text(Columna));
                                }
                                $("#TablaReporte").append(row);

                            });
                            
                        };

                        //Pintar Datos En la Tabla
                        for (let n = 0; n < ResultadoR.length; n++) {
                            ArrayDatos= ResultadoR[n];
                            console.log("ArrayDatos: ", ArrayDatos);
                            
                            for (let d = 0; d < ArrayDatos.length; d++) {
                                JsonDatos= ArrayDatos[d];
                                console.log("JsonDatos: ", JsonDatos);
                                
                                var Columnas = [];
                                var Datos= [];
                                for(var i in JsonDatos){
                                    var Columna= i;
                                    var Dato= JsonDatos[i];
                                    //console.log("Columna: ", Columna);
                                    //console.log("Dato: ", Dato);
                                    Columnas.push(Columna);
                                    Datos.push(Dato);
                                }

                                if(n == 0){
                                    console.log("Columnas: ", Columnas);
                                    PintarColumnas(Columnas);
                                }
                                console.log("Datos: ", Datos);
                                
                                var Contador= 0;
                                var row = $("<tr id='ContFilas' style='text-align: center;'>");
                                Datos.forEach(Dato => {
                                    Contador= Contador+1;
                                    if((Contador == 1)||(Contador == 3)){
                                        row.append($("<td style='position: relative;' hidden='true'>").text(Dato));
                                    }else if(Contador > 7){
                                        row.append($("<td style='position: relative;' hidden='true'>").text(Dato));
                                    }else{
                                        row.append($("<td style='position: relative;'>").text(Dato));
                                    }
                                    $("#TablaReporte").append(row);
                                });
                            
                            }

                        }

                        $("#ModalReporte").modal();
                        BtnExportar.click();

                    },
                    error: function(php_response) {
                        swal({
                            icon: 'error',
                            title: '¡Error Servidor!  😵',
                            text: 'Por Favor, Verificar Sentencia SQL...',
                            confirmButtonColor: '#2892DB'
                        })
                        php_response = JSON.stringify(php_response);
                        console.log("php_response: ", php_response);

                    }
                });

            },
            error: function(php_response) {
                swal({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }
        });
    });


    //Descargar Archivo
    const $TablaReporte = document.querySelector("#TablaReporte");
    const BtnExportar = document.querySelector("#BtnDescargarReporte");

    BtnExportar.addEventListener("click", function() {
        let tableExport = new TableExport($TablaReporte, {
            exportButtons: true,  //Queremos botones
            filename: "Reporte Asignaciones",  //Nombre del archivo de Excel
            sheetname: "Reporte Asignaciones",  //Título de la hoja
        });
        let datos = tableExport.getExportData();
        let preferenciasDocumento = datos.tabla.xlsx;
        tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
       
    });

    function LimpiarTabla(){
        $("#ContTitulos").empty();
        $("#ContFilas").empty();
        $("#ModalReporte").modal('hide');
    };
    

</script>

