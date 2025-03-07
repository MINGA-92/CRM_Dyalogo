
//Funciones Para IVR's 
$(document).ready(function(){
    var Titulo= document.getElementById("TtlListaOpciones");
	Titulo.className = "box with-border";
});


//Capturar Direccion IVR
function CapturarDireccionIVR(Dirreccion){
    const Servidor= Dirreccion;
    console.log("Servidor:", Servidor);
    const Url= Servidor+"cruds/DYALOGOCRM_SISTEMA/G11/";
    return Url;
}


//Guardar IVR
function GuardarIVRs(Dirreccion){
    var Url= CapturarDireccionIVR(Dirreccion);
    var Titulo= document.getElementById("TtlListaOpciones");
	Titulo.className = "box-header with-border";
    $("#TtlListaOpciones").show();

    var IdEstrategia= $("#IdEstrategia").val();
    var IdProyecto= $("#huesped").val();
    var NombreIVR= $("#InputNombreIVR").val();
    var NombreOpcion= $("#InputOpcionIVR").val();
    var GrabacionBienvenida= $("#SelectGrabaBienvenida").val();
    var GrabacionDigitos= $("#SelectGrabaDigitos").val();
    var TiempoEspera= $("#InputTiempoEspera").val();


    if((NombreIVR == null) || (NombreIVR == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Nombre De IVR"',
            confirmButtonColor: '#2892DB'
        })
    }else if((NombreOpcion == null) || (NombreOpcion == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Nombre De Opción"',
            confirmButtonColor: '#2892DB'
        })
    }else if((GrabacionBienvenida == null) || (GrabacionBienvenida == "0")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Grabación De Bienvenida"',
            confirmButtonColor: '#2892DB'
        })
    }else if((GrabacionDigitos == null) || (GrabacionDigitos == "0")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Grabación De Toma De Dígitos"',
            confirmButtonColor: '#2892DB'
        })
    }else if((TiempoEspera == null) || (TiempoEspera == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Tiempo De Espera"',
            confirmButtonColor: '#2892DB'
        })
    }else{
        $("#FormularioDatos2").hide();
        $("#Loading_4").show();
        var NombreInterno= NombreIVR.replace(" ", "_");

        let FormularioIVR = new FormData();
        FormularioIVR.append('IdEstrategia', IdEstrategia);
        FormularioIVR.append('IdProyecto', IdProyecto);
        FormularioIVR.append('NombreIVR', NombreIVR);
        FormularioIVR.append('NombreOpcion', NombreOpcion);
        FormularioIVR.append('NombreInterno', NombreInterno);
        FormularioIVR.append('GrabacionBienvenida', GrabacionBienvenida);
        FormularioIVR.append("GrabacionDigitos", GrabacionDigitos);
        FormularioIVR.append("TiempoEspera", TiempoEspera);

        $.ajax({
            url: Url+"Controller/GuardarIVRs.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioIVR,
            success: function(php_response) {
                Respuesta= php_response.msg;
                if (Respuesta == "Ok"){
                    console.log("Respuesta: ", Respuesta);
                    Swal.fire({
                        title: '¡Guardado!  😉',
                        text: '¡IVR Registrado Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    })
                }else if (Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Guardar Información!  🤨',
                        text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response.Falla);
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                $("#FormularioDatos2").show();
                $("#Loading_4").hide();
                console.log(php_response.msg);
                console.log(php_response);
            }
        });

    }

};

//Actualizar IVR
function ActualizarIVRs(Dirreccion){
    var Url= CapturarDireccionIVR(Dirreccion);

    var IdIVR= $("#IdIVR").val();
    var IdEstrategia= $("#IdEstrategia").val();
    var IdProyecto= $("#huesped").val();
    var NombreIVR= $("#InputNombreIVR").val();
    var NombreOpcion= $("#InputOpcionIVR").val();
    var GrabacionBienvenida= $("#SelectGrabaBienvenida").val();
    var GrabacionDigitos= $("#SelectGrabaDigitos").val();
    var TiempoEspera= $("#InputTiempoEspera").val();


    $("#FormularioDatos2").hide();
    $("#Loading_4").show();
    var NombreInterno= NombreIVR.replace(" ", "_");

    let FormularioIVR = new FormData();
    FormularioIVR.append('IdIVR', IdIVR);
    FormularioIVR.append('IdEstrategia', IdEstrategia);
    FormularioIVR.append('IdProyecto', IdProyecto);
    FormularioIVR.append('NombreIVR', NombreIVR);
    FormularioIVR.append('NombreOpcion', NombreOpcion);
    FormularioIVR.append('NombreInterno', NombreInterno);
    FormularioIVR.append('GrabacionBienvenida', GrabacionBienvenida);
    FormularioIVR.append("GrabacionDigitos", GrabacionDigitos);
    FormularioIVR.append("TiempoEspera", TiempoEspera);

    $.ajax({
        url: Url+"Controller/ActualizarIVRs.php",
        type: "POST",
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: FormularioIVR,
        success: function(php_response) {
            Respuesta= php_response.msg;
            if (Respuesta == "Ok"){
                console.log("Respuesta: ", Respuesta);
                Swal.fire({
                    title: '¡Actualizado!  😉',
                    text: '¡IVR Modificado Exitosamente!',
                    icon: 'success',
                    showConfirmButton: false,
                    confirmButtonColor: '#2892DB',
                    timer: 2000
                }).then(() => {
                    window.location.reload();
                })
            }else if (Respuesta == "Error"){
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Al Guardar Información!  🤨',
                    text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                console.log(php_response.Falla);
            }
        },
        error: function(php_response) {
            php_response = JSON.stringify(php_response);
            Swal.fire({
                icon: 'error',
                title: '¡Error Servidor!  😵',
                text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                confirmButtonColor: '#2892DB'
            })
            $("#FormularioDatos2").show();
            $("#Loading_4").hide();
            console.log(php_response.msg);
            console.log(php_response);
        }
    });

    
};

//Consultar Lista De Opciones
function ConsultarListaOpciones(IdIVR){

    var Url= CapturarDireccionIVR(Dirreccion);
    var Tabla= $("#TablaOpciones");
    let Form= new FormData();
    Form.append("IdIVR", IdIVR);
    
    $.ajax({
        url: Url+"Controller/ConsultarOpciones.php",
        type: "POST",
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: Form,
        success: function(php_response) {
            //console.log("php_response: ", php_response);
            if (php_response.msg == "Nada") {
                Tabla.empty();
            }else{
                var Resultado= php_response.Resultado;
                //console.log("Resultado: ", Resultado);
                var Contador= 0;
                Resultado.forEach(Opcion => {
                    Contador= Contador+1;
                    var IdOpcion= Opcion[4];
                    var Fila= $("<tr style='text-align: center; margin-top: 2%;' id='Fila_"+Contador+"'></tr>");
                    var BotonA= $('<td class="col-md-1"><button class="btn btn-primary" type="button" name="EditarOpcion_'+IdOpcion+'" id="EditarOpcion_'+IdOpcion+'" style="margin-top: 1%;" onclick="EditarOpcion('+IdOpcion+')"><i class="fa fa-edit" aria-hidden="true">&nbsp;</i></button></td>');
                    var BotonE= $('<td class="col-md-1"><button class="btn btn-danger" type="button" name="EliminarOpcion_'+IdOpcion+'" id="EliminarOpcion_'+IdOpcion+'" style="margin-top: 1%; margin-left: 2%;" onclick="EliminarOpcion('+IdOpcion+')"><i class="fa fa-trash-o"></i></button></td>');
                    
                    for (let i = 0; i < Opcion.length; i++){
                        const Dato = Opcion[i];
                        if(i >= 2){
                            var Celda= $("<td style='text-align: center;' hidden>");
                        }else{
                            var Celda= $("<td style='text-align: center;'>");
                        }
                        Celda.text(Dato);
                        Fila.append(Celda);
                        Fila.append(BotonA);
                        Fila.append(BotonE);
                    };

                    Tabla.append(Fila);
                });
            }
            
            

        },
        error: function(php_response) {
            php_response = JSON.stringify(php_response);
            Swal.fire({
                icon: 'error',
                title: '¡Error Servidor!  😵',
                text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                confirmButtonColor: '#2892DB'
            })
            $("#FormularioDatos2").show();
            $("#Loading_4").hide();
            console.log(php_response.msg);
            console.log(php_response);
        }
    });

}

//Limpiar IVRs
function LimpiarIVRs(){
    console.log("-------------- / LimpiarIVRs /-------- ");
    $("#TablaOpciones").empty();

    document.getElementById("SavedModelIVR").value= "";
    myDiagramIVR.model = go.Model.fromJson(document.getElementById("SavedModelIVR").value);
}

//Agregar Nueva Opcion
$("#BtnNuevaOpcion").click(function(){
    $("#ModalNuevaOpcion").modal();
    $("#TablaAccionesIVR").prop('hidden', true);
    $("#divBtnGuardarOpcion").prop('hidden', false);
    $("#divBtnActualizarOpcion").prop('hidden', true);
    console.log("------- / Nueva Opcion / -------");
});

//Guardar Nueva Opcion
function GuardarOpcionIVRs(Dirreccion) {
    var Url= CapturarDireccionIVR(Dirreccion);
    var IdIVR= $("#IdIVR").val();
    var OpcionNumero= $("#SelectOpcionNumero").val();
    var NombreOpcion= $("#NombreOpcion").val();
    var OpcionValida= document.getElementById("CheckOpcionValida").checked;

    if(OpcionValida == true){
        OpcionValida= 1;
    }else{
        OpcionValida= 0;
    }

    if((OpcionNumero == null) || (OpcionNumero == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Opción (Número A Marcar)"',
            confirmButtonColor: '#2892DB'
        })
    }else if((NombreOpcion == null) || (NombreOpcion == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Nombre De Opción"',
            confirmButtonColor: '#2892DB'
        })
    }else{
        let FormularioOpciones = new FormData();
        FormularioOpciones.append('IdIVR', IdIVR);
        FormularioOpciones.append('OpcionNumero', OpcionNumero);
        FormularioOpciones.append('NombreOpcion', NombreOpcion);
        FormularioOpciones.append('OpcionValida', OpcionValida);

        $.ajax({
            url: Url+"Controller/GuardarOpcion.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioOpciones,
            success: function(php_response) {
                Respuesta= php_response.msg;
                console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    Swal.fire({
                        title: '¡Guardado!  😉',
                        text: '¡Opción Registrada Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    })
                }else if(Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!  🤨',
                        text: 'Al Guardar Información...',
                        confirmButtonColor: '#2892DB'
                    })
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
            }
        });
    }

}

//Consultar Opcion a Editar
function EditarOpcion(Id){
    var IdOpcion= Id;
    var Url= CapturarDireccionIVR(Dirreccion);
    $("#ModalNuevaOpcion").modal();
    $("#TablaAccionesIVR").prop('hidden', false);
    $("#divBtnGuardarOpcion").prop('hidden', true);
    $("#divBtnActualizarOpcion").prop('hidden', false);

    let Form= new FormData();
    Form.append("IdOpcion", IdOpcion);
    
    $.ajax({
        url: Url+"Controller/ConsultarOpcion.php",
        type: "POST",
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: Form,
        success: function(php_response) {
            //console.log("php_response: ", php_response);
            $("#IdOpcion").val(IdOpcion);
            Resultado= php_response.Resultado;
            ArrayResultado= Resultado[0];
            //console.log("ArrayResultado: ", ArrayResultado);
            $("#SelectOpcionNumero").val(ArrayResultado[0]);
            $("#NombreOpcion").val(ArrayResultado[1]);
            $("#TltOpcion").text(ArrayResultado[1]+"': ");
            if(ArrayResultado[3] == 1){
                $('#CheckOpcionValida').prop("checked", true);
            }else{
                $('#CheckOpcionValida').prop("checked", false);
            }
            ConsultarListaAcciones(IdOpcion, Url);
        },
        error: function(php_response) {
            php_response = JSON.stringify(php_response);
            Swal.fire({
                icon: 'error',
                title: '¡Error Servidor!  😵',
                text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                confirmButtonColor: '#2892DB'
            })
            console.log(php_response.msg);
            console.log(php_response);
        }
    });
    
}

//Actualizar Opcion
function ActualizarOpcionIVRs(Dirreccion) {
    var Url= CapturarDireccionIVR(Dirreccion);
    var IdOpcion= $("#IdOpcion").val();
    var OpcionNumero= $("#SelectOpcionNumero").val();
    var NombreOpcion= $("#NombreOpcion").val();
    var OpcionValida= document.getElementById("CheckOpcionValida").checked;

    if(OpcionValida == true){
        OpcionValida= 1;
    }else{
        OpcionValida= 0;
    }

    if((OpcionNumero == null) || (OpcionNumero == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Opción (Número A Marcar)"',
            confirmButtonColor: '#2892DB'
        })
    }else if((NombreOpcion == null) || (NombreOpcion == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Nombre De Opción"',
            confirmButtonColor: '#2892DB'
        })
    }else{
        let FormularioOpciones = new FormData();
        FormularioOpciones.append('IdOpcion', IdOpcion);
        FormularioOpciones.append('OpcionNumero', OpcionNumero);
        FormularioOpciones.append('NombreOpcion', NombreOpcion);
        FormularioOpciones.append('OpcionValida', OpcionValida);

        $.ajax({
            url: Url+"Controller/EditarOpcion.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioOpciones,
            success: function(php_response) {
                Respuesta= php_response.msg;
                console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    Swal.fire({
                        title: '¡Actualizado!  😉',
                        text: '¡Opción Modificada Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    })
                }else if(Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!  🤨',
                        text: 'Al Actualizar Información...',
                        confirmButtonColor: '#2892DB'
                    })
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
            }
        });

    }

}

//Eliminar Opcion
function EliminarOpcion(Id) {
    var IdOpcion= Id;
    var Url= CapturarDireccionIVR(Dirreccion);

    Swal.fire({
        title: '¿Está Seguro?',
        text: "¿Deseas Eliminar Esta Opción?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, Eliminar!'
    }).then((result) => {
        if (result.isConfirmed) {
            let Form = new FormData();
            Form.append('IdOpcion', IdOpcion);
            $.ajax({
                url: Url+"Controller/EliminarOpcion.php",
                dataType: "json",
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: Form,
                success: function (php_response){
                    Respuesta = php_response.msg;
                    if(Respuesta == "Ok"){
                        Swal.fire({
                            title: '¡Eliminada   🗑!',
                            text: 'La Opción Ha Sido Eliminada Exitosamente!',
                            icon: 'success',
                            showConfirmButton: false,
                            confirmButtonColor: '#2892DB',
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        })
                    }else if(Respuesta == "Error"){
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Al Eliminar!  🤨',
                            text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                            confirmButtonColor: '#2892DB'
                        })
                        console.log(php_response.msg);
                    }
                },
                error: function (php_response){
                    php_response = JSON.stringify(php_response);
                    Swal.fire({
                        icon: 'error',
                        title: '¡Fallo La Comunicacion Con El Servidor!  😵',
                        text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response);
                }
            });
        }
    })

}

//Limpiar Opciones
function LimpiarOpciones(){
    $("#SelectOpcionNumero").val("Elige Una Opción");
    $("#NombreOpcion").val("");
    $('#CheckOpcionValida').prop("checked", false);
    $("#BodyTablaAccionesIVR").empty();
    
}

//Consultar Lista De Acciones
function ConsultarListaAcciones(IdOpcion, Url) {

    let Form= new FormData();
    var Tabla= $("#BodyTablaAccionesIVR");
    Form.append("IdOpcion", IdOpcion);
    
    $.ajax({
        url: Url+"Controller/ConsultarAcciones.php",
        type: "POST",
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: Form,
        success: function(php_response) {
            //console.log("php_response: ", php_response);
            if (php_response.msg == "Nada") {
                Tabla.empty();
            }else{
                var Resultado= php_response.Resultado;
                //console.log("Resultado: ", Resultado);
                var Contador= 0;
                Resultado.forEach(Accion => {
                    Contador= Contador+1;
                    var IdAccion= Accion[3];
                    var Fila= $("<tr style='text-align: center; margin-top: 2%;' id='Fila_"+Contador+"'></tr>");
                    var BotonA= $('<button class="btn btn-primary btn-sm" type="button" name="EditarAccion_'+IdAccion+'" id="EditarAccion_'+IdAccion+'" style="margin-top: 1%;" onclick="EditarAccion('+IdAccion+')"><i class="fa fa-edit" aria-hidden="true">&nbsp;</i></button>');
                    var BotonE= $('<button class="btn btn-danger btn-sm" type="button" name="EliminarAccion_'+IdAccion+'" id="EliminarAccion_'+IdAccion+'" style="margin-top: 1%; margin-left: 2%;" onclick="EliminarAccion('+IdAccion+')"><i class="fa fa-trash-o"></i></button></td>');
                    
                    for (let i = 0; i < Accion.length; i++){
                        const Dato = Accion[i];
                        if(i == 1){
                            if(Dato == 2){
                                var ValorDato= "Pasar a Una Campaña";
                            }else if(Dato == 3){
                                var ValorDato= "Reproducir Grabacion";
                            }else if(Dato == 4){
                                var ValorDato= "Pasar a Otro IVR";
                            }else if(Dato == 6){
                                var ValorDato= "Numero Externo";
                            }else if(Dato == 9){
                                var ValorDato= "Pasar a Encuesta";
                            }else if(Dato == 11){
                                var ValorDato= "Avanzado";
                            }
                            
                        }else{
                            var ValorDato= Dato;
                        }

                        if(i >= 3){
                            var Celda= $("<td style='text-align: center;' hidden>");
                        }else{
                            var Celda= $("<td style='text-align: center;'>");
                        }
                        Celda.text(ValorDato);
                        Fila.append(Celda);
                        Fila.append(BotonA);
                        Fila.append(BotonE);
                    };

                    Tabla.append(Fila);
                });
            }
        },
        error: function(php_response) {
            php_response = JSON.stringify(php_response);
            Swal.fire({
                icon: 'error',
                title: '¡Error Servidor!  😵',
                text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                confirmButtonColor: '#2892DB'
            })
            console.log(php_response.msg);
            console.log(php_response);
        }
    });
}

//Agregar Nueva Accion
$("#BtnNuevaAccion").click(function(){
    $("#ModalNuevaAccion").modal();
    $("#divBtnGuardarAccion").prop('hidden', false);
    $("#divBtnActualizarAccion").prop('hidden', true);
    console.log("------- / Nueva Accion / -------");
});

//Mostrar o Ocultar Acciones
function MostrarOcultarAcciones(ValorAccion) {
    //console.log("ValorAccion: ", ValorAccion);
    if(ValorAccion == "Numero Externo"){
        $("#divNumeroExterno").show();
        $("#divCampana").hide();
        $("#divGraba").hide();
        $("#divListaIVR").hide();
        $("#divEncuesta").hide();
        $("#divAvanzadoIVR").hide();
    }else if(ValorAccion == "Pasar a Una Campaña"){
        $("#divNumeroExterno").hide();
        $("#divCampana").show();
        $("#divGraba").hide();
        $("#divListaIVR").hide();
        $("#divEncuesta").hide();
        $("#divAvanzadoIVR").hide();
    }else if(ValorAccion == "Reproducir Grabacion"){
        $("#divNumeroExterno").hide();
        $("#divCampana").hide();
        $("#divGraba").show();
        $("#divListaIVR").hide();
        $("#divEncuesta").hide();
        $("#divAvanzadoIVR").hide();
    }else if(ValorAccion == "Pasar a Otro IVR"){
        $("#divNumeroExterno").hide();
        $("#divCampana").hide();
        $("#divGraba").hide();
        $("#divListaIVR").show();
        $("#divEncuesta").hide();
        $("#divAvanzadoIVR").hide();
    }else if(ValorAccion == "Pasar a Encuesta"){
        $("#divNumeroExterno").hide();
        $("#divCampana").hide();
        $("#divGraba").hide();
        $("#divListaIVR").hide();
        $("#divEncuesta").show();
        $("#divAvanzadoIVR").hide();
    }else if(ValorAccion == "Avanzado"){
        $("#divNumeroExterno").hide();
        $("#divCampana").hide();
        $("#divGraba").hide();
        $("#divListaIVR").hide();
        $("#divEncuesta").hide();
        $("#divAvanzadoIVR").show();
    }else{
        $("#divNumeroExterno").hide();
        $("#divCampana").hide();
        $("#divGraba").hide();
        $("#divListaIVR").hide();
        $("#divEncuesta").hide();
        $("#divAvanzadoIVR").hide();
    }
}

//Capturar Valor Accion
$("#SelectAccion").change(function(){
    var ValorAccion= $("#SelectAccion").val();
    MostrarOcultarAcciones(ValorAccion);
});

//Guardar Nueva Accion
function GuardarDatosAccion(FormularioAcciones) {
    
    var Url= CapturarDireccionIVR(Dirreccion);
    console.log("Url: ", Url);
    $.ajax({
        url: Url+"Controller/GuardarAccion.php",
        type: "POST",
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: FormularioAcciones,
        success: function(php_response) {
            Respuesta= php_response.msg;
            console.log("Respuesta: ", Respuesta);
            if(Respuesta == "Ok"){
                Swal.fire({
                    title: '¡Guardado!  😉',
                    text: '¡Acción Registrada Exitosamente!',
                    icon: 'success',
                    showConfirmButton: false,
                    confirmButtonColor: '#2892DB',
                    timer: 2000
                }).then(() => {
                    window.location.reload();
                })
            }else if(Respuesta == "Error"){
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!  🤨',
                    text: 'Al Guardar Información...',
                    confirmButtonColor: '#2892DB'
                })
            }
        },
        error: function(php_response) {
            php_response = JSON.stringify(php_response);
            Swal.fire({
                icon: 'error',
                title: '¡Error Servidor!  😵',
                text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                confirmButtonColor: '#2892DB'
            })
        }
    });

}

//Validaciones Para Guardar Nueva Accion
function GuardarAccionIVRs(Dirreccion) {
    var Url= CapturarDireccionIVR(Dirreccion);
    var IdOpcion= $("#IdOpcion").val();
    var OrdenEjecucion= $("#OrdenEjecucion").val();
    var Accion= $("#SelectAccion").val();

    if((OrdenEjecucion == 0) || (OrdenEjecucion == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Orden"',
            confirmButtonColor: '#2892DB'
        })
    }else if((Accion == null) || (Accion == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Acción"',
            confirmButtonColor: '#2892DB'
        })
    }else{
        console.log("IdOpcion: ", IdOpcion);
        console.log("OrdenEjecucion: ", OrdenEjecucion);
        console.log("Accion: ", Accion);

        if(Accion == "Numero Externo"){
            var NumAccion= 6;
            var IdTroncal= $("#SelectLinea").val();
            var ValorAccion= $("#NumeroExterno").val();
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Pasar a Una Campaña"){
            var NumAccion= 2;
            var IdTroncal= null;
            var SelectCampana= document.getElementById("SelectCampana");
            var IdCampana= SelectCampana.value;
            var ValorAccion= SelectCampana.options[SelectCampana.selectedIndex].text;
            var TransEncuesta= document.getElementById("CheckEncuesta").checked;
            var IdGrabacion= null;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
            if(TransEncuesta == true){
                var TransEncuesta= 1;
            }else{
                var TransEncuesta= 0;
            }
        }else if(Accion == "Reproducir Grabacion"){
            var NumAccion= 3;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var SelectGraba= document.getElementById("SelectGraba");
            var IdGrabacion= SelectGraba.value;
            var ValorAccion= SelectGraba.options[SelectGraba.selectedIndex].text;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Pasar a Otro IVR"){
            var NumAccion= 4;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var ListaIVR= document.getElementById("ListaIVR");
            var IdIVR= ListaIVR.value;
            var ValorAccion= ListaIVR.options[ListaIVR.selectedIndex].text;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Pasar a Encuesta"){
            var NumAccion= 9;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var IdIVR= null;
            var SelectEncuesta= document.getElementById("SelectEncuesta");
            var IdEncuesta= SelectEncuesta.value;
            var ValorAccion= SelectEncuesta.options[SelectEncuesta.selectedIndex].text;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Avanzado"){
            var NumAccion= 11;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= $("#InputParametros").val();
            var Etiqueta= $("#InputEtiqueta").val();
            if((Etiqueta == null) || (Etiqueta == "")){
                swal({
                    icon: 'error',
                    title: '🤨 Oops...',
                    text: 'Debe Agregar Un Valor En El Campo "Etiqueta"',
                    confirmButtonColor: '#2892DB'
                })
            }else{
                var SelectAplicacion = document.getElementById("SelectAplicacion");
                var IdAplicacion= SelectAplicacion.value;
                var TextoAplicacion = SelectAplicacion.options[SelectAplicacion.selectedIndex].text;
                var ValorAccion= TextoAplicacion+"("+Parametros+")";
            }
            
        }
         
        /* console.log("NumAccion: ", NumAccion);
        console.log("IdTroncal: ", IdTroncal);
        console.log("IdCampana: ", IdCampana);
        console.log("TransEncuesta: ", TransEncuesta);
        console.log("IdGrabacion: ", IdGrabacion);
        console.log("IdIVR: ", IdIVR);
        console.log("IdEncuesta: ", IdEncuesta);
        console.log("Parametros: ", Parametros);
        console.log("Etiqueta: ", Etiqueta);
        console.log("IdAplicacion: ", IdAplicacion);
        console.log("ValorAccion: ", ValorAccion); */
        

        if((ValorAccion == "") || (ValorAccion == null)){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debes Diligenciar Todos Los Campos',
                confirmButtonColor: '#2892DB'
            })
        }else{
            var CantidadAccion= ValorAccion.length;
            console.log("CantidadAccion: ", CantidadAccion);
            if(CantidadAccion = 18){
                var NumCorte= CantidadAccion - 2;
                var CorteAccion= ValorAccion.substring(0, NumCorte);
                console.log("CorteAccion: ", CorteAccion);
    
                if(CorteAccion == "Elige Una Opción"){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Debes Diligenciar Todos Los Campos',
                        confirmButtonColor: '#2892DB'
                    })
                }else{
                    let FormularioAcciones = new FormData();
                    FormularioAcciones.append('IdOpcion', IdOpcion);
                    FormularioAcciones.append('OrdenEjecucion', OrdenEjecucion);
                    FormularioAcciones.append('Accion', NumAccion);
                    FormularioAcciones.append('IdTroncal', IdTroncal);
                    FormularioAcciones.append('IdCampana', IdCampana);
                    FormularioAcciones.append('TransEncuesta', TransEncuesta);
                    FormularioAcciones.append('IdGrabacion', IdGrabacion);
                    FormularioAcciones.append('IdIVR', IdIVR);
                    FormularioAcciones.append('IdEncuesta', IdEncuesta);
                    FormularioAcciones.append('Parametros', Parametros);
                    FormularioAcciones.append('Etiqueta', Etiqueta);
                    FormularioAcciones.append('IdAplicacion', IdAplicacion);
                    FormularioAcciones.append('ValorAccion', ValorAccion);
        
                    GuardarDatosAccion(FormularioAcciones);

                }
            }
        }

    }

}

//Consultar Accion a Editar
function EditarAccion(Id){
    var IdAccion= Id;
    var Url= CapturarDireccionIVR(Dirreccion);
    $("#ModalNuevaAccion").modal();
    $("#divBtnGuardarAccion").prop('hidden', true);
    $("#divBtnActualizarAccion").prop('hidden', false);

    let Form= new FormData();
    Form.append("IdAccion", IdAccion);
    
    $.ajax({
        url: Url+"Controller/ConsultarAccion.php",
        type: "POST",
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: Form,
        success: function(php_response) {
            $("#IdAccion").val(IdAccion);
            Resultado= php_response.Resultado;
            ArrayResultado= Resultado[0];
            //console.log("ArrayResultado: ", ArrayResultado);
            
            $("#OrdenEjecucion").val(ArrayResultado[0]);
            var ValorAccion= ArrayResultado[1];
            if(ValorAccion == 2){
                var TipoAccion= "Pasar a Una Campaña";
                var TransEncuesta= ArrayResultado[3];
                if(TransEncuesta == 1){
                    $("#CheckEncuesta").prop("checked", true);
                }else{
                    $("#CheckEncuesta").prop("checked", false);
                }
                var Campana= ArrayResultado[8];
                $("#SelectCampana").val(Campana);
                
            }else if(ValorAccion == 3){
                var TipoAccion= "Reproducir Grabacion";
                var Grabacion= ArrayResultado[9];
                $("#SelectGraba").val(Grabacion);

            }else if(ValorAccion == 4){
                var TipoAccion= "Pasar a Otro IVR";
                var Ivr= ArrayResultado[10];
                $("#ListaIVR").val(Ivr);

            }else if(ValorAccion == 6){
                var TipoAccion= "Numero Externo";
                var Troncal= ArrayResultado[7];
                var Numero= ArrayResultado[2];
                $("#SelectLinea").val(Troncal);
                $("#NumeroExterno").val(Numero);

            }else if(ValorAccion == 9){
                var TipoAccion= "Pasar a Encuesta";
                var Encuesta= ArrayResultado[11];
                $("#SelectEncuesta").val(Encuesta);

            }else if(ValorAccion == 11){
                var TipoAccion= "Avanzado";
                var Etiqueta= ArrayResultado[4];
                $("#InputEtiqueta").val(Etiqueta);
                var Aplicacion= ArrayResultado[5];
                $("#SelectAplicacion").val(Aplicacion);
                var Parametros= ArrayResultado[6];
                $("#InputParametros").val(Parametros);
                

            }
            $("#SelectAccion").val(TipoAccion);
            MostrarOcultarAcciones(TipoAccion);

        },
        error: function(php_response) {
            php_response = JSON.stringify(php_response);
            Swal.fire({
                icon: 'error',
                title: '¡Error Servidor!  😵',
                text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                confirmButtonColor: '#2892DB'
            })
            console.log(php_response.msg);
            console.log(php_response);
        }
    });
    
}

//Eliminar Accion
function EliminarAccion(Id) {
    var IdAccion= Id;
    var Url= CapturarDireccionIVR(Dirreccion);

    Swal.fire({
        title: '¿Está Seguro?',
        text: "¿Deseas Eliminar Esta Acción?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, Eliminar!'
        }).then((result) => {
        if (result.isConfirmed) {
            let Form = new FormData();
            Form.append('IdAccion', IdAccion);
            $.ajax({
                url: Url+"Controller/EliminarAccion.php",
                dataType: "json",
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: Form,
                success: function (php_response){
                    Respuesta = php_response.msg;
                    if(Respuesta == "Ok"){
                        Swal.fire({
                            title: '¡Eliminada   🗑!',
                            text: 'La Acción Ha Sido Eliminada Exitosamente!',
                            icon: 'success',
                            showConfirmButton: false,
                            confirmButtonColor: '#2892DB',
                            timer: 2000
                        }).then(() => {
                            //window.location.reload();
                            $("#ModalAvanzado").modal('hide');
                            $("#tbodyAvanzado").empty();

                        })
                    }else if(Respuesta == "Error"){
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Al Eliminar!  🤨',
                            text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                            confirmButtonColor: '#2892DB'
                        })
                        console.log(php_response.msg);
                    }
                },
                error: function (php_response){
                    php_response = JSON.stringify(php_response);
                    Swal.fire({
                        icon: 'error',
                        title: '¡Fallo La Comunicacion Con El Servidor!  😵',
                        text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response);
                }
            });
        }
    })

}

//Actualizar Accion
function ActualizarDatosAccion(FormularioAcciones) {
    var Url= CapturarDireccionIVR(Dirreccion);
    console.log("Url: ", Url);
    console.log("FormularioAcciones: ", FormularioAcciones);
    
    $.ajax({
        url: Url+"Controller/EditarAccion.php",
        type: "POST",
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: FormularioAcciones,
        success: function(php_response) {
            Respuesta= php_response.msg;
            console.log("Respuesta: ", Respuesta);
            if(Respuesta == "Ok"){
                Swal.fire({
                    title: '¡Actualizado!  😉',
                    text: '¡Acción Modificada Exitosamente!',
                    icon: 'success',
                    showConfirmButton: false,
                    confirmButtonColor: '#2892DB',
                    timer: 2000
                }).then(() => {
                    window.location.reload();
                })
            }else if(Respuesta == "Error"){
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!  🤨',
                    text: 'Al Actualizar La Información...',
                    confirmButtonColor: '#2892DB'
                })
            }
        },
        error: function(php_response) {
            php_response = JSON.stringify(php_response);
            Swal.fire({
                icon: 'error',
                title: '¡Error Servidor!  😵',
                text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                confirmButtonColor: '#2892DB'
            })
        }
    });
}

//Validaciones Para Actualizar Nueva Accion
function ActualizarAccionIVRs(Dirreccion) {
    var Url= CapturarDireccionIVR(Dirreccion);
    var IdAccion= $("#IdAccion").val();
    var OrdenEjecucion= $("#OrdenEjecucion").val();
    var Accion= $("#SelectAccion").val();

    if((OrdenEjecucion == 0) || (OrdenEjecucion == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Orden"',
            confirmButtonColor: '#2892DB'
        })
    }else if((Accion == null) || (Accion == "")){
        swal({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Debe Agregar Un Valor En El Campo "Acción"',
            confirmButtonColor: '#2892DB'
        })
    }else{

        console.log('IdAccion:', IdAccion);
        console.log('OrdenEjecucion:', OrdenEjecucion);
        console.log('Accion:', Accion);

        if(Accion == "Numero Externo"){
            var NumAccion= 6;
            var IdTroncal= $("#SelectLinea").val();
            var ValorAccion= $("#NumeroExterno").val();
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Pasar a Una Campaña"){
            var NumAccion= 2;
            var IdTroncal= null;
            var SelectCampana= document.getElementById("SelectCampana");
            var IdCampana= SelectCampana.value;
            var ValorAccion= SelectCampana.options[SelectCampana.selectedIndex].text;
            var TransEncuesta= document.getElementById("CheckEncuesta").checked;
            var IdGrabacion= null;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
            if(TransEncuesta == true){
                var TransEncuesta= 1;
            }else{
                var TransEncuesta= 0;
            }
        }else if(Accion == "Reproducir Grabacion"){
            var NumAccion= 3;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var SelectGraba= document.getElementById("SelectGraba");
            var IdGrabacion= SelectGraba.value;
            var ValorAccion= SelectGraba.options[SelectGraba.selectedIndex].text;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Pasar a Otro IVR"){
            var NumAccion= 4;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var ListaIVR= document.getElementById("ListaIVR");
            var IdIVR= ListaIVR.value;
            var ValorAccion= ListaIVR.options[ListaIVR.selectedIndex].text;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Pasar a Encuesta"){
            var NumAccion= 9;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var IdIVR= null;
            var SelectEncuesta= document.getElementById("SelectEncuesta");
            var IdEncuesta= SelectEncuesta.value;
            var ValorAccion= SelectEncuesta.options[SelectEncuesta.selectedIndex].text;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Avanzado"){
            var NumAccion= 11;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= $("#InputParametros").val();
            var Etiqueta= $("#InputEtiqueta").val();
            if((Etiqueta == null) || (Etiqueta == "")){
                swal({
                    icon: 'error',
                    title: '🤨 Oops...',
                    text: 'Debe Agregar Un Valor En El Campo "Etiqueta"',
                    confirmButtonColor: '#2892DB'
                })
            }else{
                var SelectAplicacion = document.getElementById("SelectAplicacion");
                var IdAplicacion= SelectAplicacion.value;
                var TextoAplicacion = SelectAplicacion.options[SelectAplicacion.selectedIndex].text;
                var ValorAccion= TextoAplicacion+"("+Parametros+")";
            }
            
        }

        if((ValorAccion == "") || (ValorAccion == null)){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debes Diligenciar Todos Los Campos',
                confirmButtonColor: '#2892DB'
            })
        }else{
            var CantidadAccion= ValorAccion.length;
            //console.log("CantidadAccion: ", CantidadAccion);
            if(CantidadAccion = 18){
                var NumCorte= CantidadAccion - 2;
                var CorteAccion= ValorAccion.substring(0, NumCorte);
                //console.log("CorteAccion: ", CorteAccion);
    
                if(CorteAccion == "Elige Una Opción"){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Debes Diligenciar Todos Los Campos',
                        confirmButtonColor: '#2892DB'
                    })
                }else{
                    let FormularioAcciones = new FormData();
                    FormularioAcciones.append('IdAccion', IdAccion);
                    FormularioAcciones.append('OrdenEjecucion', OrdenEjecucion);
                    FormularioAcciones.append('Accion', NumAccion);
                    FormularioAcciones.append('IdTroncal', IdTroncal);
                    FormularioAcciones.append('IdCampana', IdCampana);
                    FormularioAcciones.append('TransEncuesta', TransEncuesta);
                    FormularioAcciones.append('IdGrabacion', IdGrabacion);
                    FormularioAcciones.append('IdIVR', IdIVR);
                    FormularioAcciones.append('IdEncuesta', IdEncuesta);
                    FormularioAcciones.append('Parametros', Parametros);
                    FormularioAcciones.append('Etiqueta', Etiqueta);
                    FormularioAcciones.append('IdAplicacion', IdAplicacion);
                    FormularioAcciones.append('ValorAccion', ValorAccion);
        
                    ActualizarDatosAccion(FormularioAcciones);

                }
            }
        }

    }
}

//Limpiar Acciones
function LimpiarAcciones(){
    $("#OrdenEjecucion").val("");
    $("#SelectAccion").val("Elige Una Opción");
    $('#CheckEncuesta').prop("checked", false);
    $("#divNumeroExterno").hide();
    $("#divCampana").hide();
    $("#divGraba").hide();
    $("#divListaIVR").hide();
    $("#divEncuesta").hide();
    $("#divAvanzadoIVR").hide();
}
