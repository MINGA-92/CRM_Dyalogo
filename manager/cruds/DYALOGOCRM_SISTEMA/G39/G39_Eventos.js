
//Capturar Direccion
function CapturarDireccion(Dirreccion){
    const Servidor= Dirreccion;
    console.log("Servidor:", Servidor);
    const Url= Servidor+"cruds/DYALOGOCRM_SISTEMA/G39/";
    return Url;
}

//Ejecutar Funciones
function EjecutarFunciones(Dirreccion){
    
    var Url= CapturarDireccion(Dirreccion);

    //Check Limite LLamadas
    $('#CheckLimitarLlamadas').on('click', function(){
        var Check= this.checked;
        if(Check == true){
            $('#InputNumeroLimite').prop("disabled", false);
        }else{
            $('#InputNumeroLimite').prop("disabled", true);
        }
    });

    //Check Generar Pausa Antes De Contestar
    $('#CheckGenerarPausa').on('click', function(){
        var Check= this.checked;
        if(Check == true){
            $('#InputPausa').prop("disabled", false);
        }else{
            $('#InputPausa').prop("disabled", true);
        }
    });

    //Check Lista Negra
    $('#CheckListaNegra').on('click', function(){
        var Check= this.checked;
        if(Check == true){
            $('#divBtnListaNegra').prop("hidden", false);
            $("#BtnListaNegra").click();
        }else{
            $('#divBtnListaNegra').prop("hidden", true);
        }
    });

    //Validacion Nombres Repetidos
    $('#InputNombre').on('change', function(){
        var Nombre= this.value;
        if((Nombre == null) || (Nombre == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Nombre"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            let Formulario = new FormData();
            Formulario.append('Nombre', Nombre);
            $.ajax({
                url: Url+"Controller/ConsultaNombre.php",
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
                        $('#InputNumeroEntrada').prop("disabled", false);
                        $('#divcheck').prop("hidden", false);
                        $('#divCheckListaNegra').prop("hidden", false);
                        $('#divAvanzado').prop("hidden", false);
                        $("#ModalHorario").show();
                    }else if (Respuesta == "Ya Existe"){
                        var NombreBD= php_response.Resultado
                        Swal.fire({
                            icon: 'info',
                            title: '¿Repetido?  🤔',
                            text: '"'+NombreBD+'", Ya Se Encuentra Registrado En El Sistema...',
                            confirmButtonColor: '#2892DB'
                        })
                        $('#InputNumeroEntrada').prop("disabled", true);
                        $('#divcheck').prop("hidden", true);
                        $('#divCheckListaNegra').prop("hidden", true);
                        $('#divBtnListaNegra').prop("hidden", true);
                        $('#divAvanzado').prop("hidden", true);
                        $("#ModalHorario").hide();
                    }else if (Respuesta == "Error"){
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Al Consultar Información!  🤨',
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
                    console.log(php_response.msg);
                    console.log(php_response);
                }
            });
        }
    });

    //Validacion Numero De Entrada Repetidos
    $('#InputNumeroEntrada').on('change', function(){
        var NumeroEntrada= this.value;
        var CantidadNumeros= NumeroEntrada.length;
        if((NumeroEntrada == null) || (NumeroEntrada == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Número De Entrada"',
                confirmButtonColor: '#2892DB'
            })
        }else if ((CantidadNumeros < 4) || (CantidadNumeros > 20)) {
            Swal.fire({
                icon: 'info',
                title: '¡Atención!  🤨',
                text: 'Este Número, No Tiene La Cantidad De Digitos Adecuada',
                confirmButtonColor: '#2892DB'
            })
            $('#InputNombre').prop("disabled", true);
            $('#divcheck').prop("hidden", true);
            $('#divCheckListaNegra').prop("hidden", true);
            $('#divBtnListaNegra').prop("hidden", true);
            $('#divAvanzado').prop("hidden", true);
            $("#ModalHorario").hide();
        }else{
            let Formulario = new FormData();
            Formulario.append('NumeroEntrada', NumeroEntrada);
            $.ajax({
                url: Url+"Controller/ConsultaNumero.php",
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
                        $('#InputNombre').prop("disabled", false);
                        $('#divcheck').prop("hidden", false);
                        $('#divCheckListaNegra').prop("hidden", false);
                        $('#divAvanzado').prop("hidden", false);
                        $("#ModalHorario").show();
                    }else if (Respuesta == "Ya Existe"){
                        var NumeroBD= php_response.Resultado
                        Swal.fire({
                            icon: 'info',
                            title: '¿Repetido?  🤔',
                            text: 'El Número "'+NumeroBD+'", Ya Se Encuentra Registrado...',
                            confirmButtonColor: '#2892DB'
                        })
                        $('#InputNombre').prop("disabled", true);
                        $('#divcheck').prop("hidden", true);
                        $('#divCheckListaNegra').prop("hidden", true);
                        $('#divBtnListaNegra').prop("hidden", true);
                        $('#divAvanzado').prop("hidden", true);
                        $("#ModalHorario").hide();
                    }else if (Respuesta == "Error"){
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Al Consultar Información!  🤨',
                            text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                            confirmButtonColor: '#2892DB'
                        })
                        console.log(php_response.Falla);
                    }
                },
                error: function(php_response) {
                    Swal.fire({
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
    });


    //Mostrar lista Negra
    $('#BtnListaNegra').on('click', function(){
        let Formulario = new FormData();
        Huesped = $("#InputHuesped").val();
        Formulario.append('Huesped', Huesped);
        $.ajax({
            url: Url+"Controller/ConsultaListaNegra.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: Formulario,
            success: function(php_response) {
                Respuesta = php_response.msg;
                if (Respuesta == "Ok"){
                    var Resultado= php_response.ListaNegra;
                    //console.log("Resultado: ", Resultado);
                    Filas = "";
                    for (let i = 0; i < Resultado.length; i++) {
                        var ListaNegra= Resultado[i];
                        Contador= 0;
                        Celdas = "";
                        ListaNegra.forEach(element =>{
                            var Dato= element;
                            Contador= Contador + 1;
                            if(Contador == 4){
                                Celdas += `<th><button type="button" class="btn btn-primary" id="BtnEditar`+Dato+`" onclick="ActualizarNumero(`+Dato+`, '`+Url+`');"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></button></th>`
                            }else if(Contador == 5){
                                Celdas += `<th><button type="button" class="btn btn-danger" id="BtnEliminar`+Dato+`" onclick="EliminarNumero(`+Dato+`, '`+Url+`');"><i class="fa-solid fa-trash-can"></i></button></th>`
                            }else{
                                Celdas += `<th>`+Dato+`</th>`
                            }
                        });
                        Fila= document.getElementById("CeldasListaNegra").innerHTML = Celdas;
                        Filas += `<tr>`+Fila+`</tr>`;
                    }
                    document.getElementById("CuerpoTblListaNegra").innerHTML = Filas;
                    $("#Loading_2").hide();
                    $("#ListaNegra").show();
                }else if (Respuesta == "Nada"){
                    console.log(php_response.msg);
                    Swal.fire({
                        icon: 'info',
                        title: ' 🤷🏽‍♂️  ¡Nada Por Aqui, Nada Por Alla! ', 
                        text: '!No Se Encontraron Resultados!',
                        confirmButtonColor: '#2892DB'
                    })
                    $("#Loading_2").hide();
                    $("#ListaNegra").show();
                }else if (Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Consultar Información!  🤨',
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
                console.log(php_response.msg);
                console.log(php_response);
            }
        });
    });

    //Mostrar Formulario Nuevo lista Negra
    $('#BtnAddListaNegra').on('click', function(){
        $('#ModalAddListaNegra').modal();
    });

    //Guardar Nuevo Registro lista Negra
    $('#BtnAgregar').on('click', function(){
        var Huesped = $("#InputHuesped").val();
        var Telefono = $("#InputTelefono").val();
        var Motivo = $("#SelectListaMotivo").val();
        var CantidadNumeros= Telefono.length;

        if((Telefono == null) || (Telefono == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Número De Telefono"',
                confirmButtonColor: '#2892DB'
            })
        }else if ((CantidadNumeros < 7) || (CantidadNumeros > 10)) {
            Swal.fire({
                icon: 'info',
                title: '¡Atención!  🤨',
                text: 'Este Número, No Tiene Los Digitos De Un Numero Telefónico',
                confirmButtonColor: '#2892DB'
            })
        }else if((Motivo == null) || (Motivo == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Motivo o Razón"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            $("#BodyModalAddListaNegra").hide();
            $("#Loading_3").show();

            let Formulario = new FormData();
            Formulario.append('Huesped', Huesped);
            Formulario.append('Telefono', Telefono);
            Formulario.append('Motivo', Motivo);
            
            $.ajax({
                url: Url+"Controller/GuardarNumeroListaNegra.php",
                dataType: "json",
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: Formulario,
                success: function (php_response){
                    Respuesta = php_response.msg;
                    if(Respuesta == "Ok"){
                        Swal.fire({
                            title: '¡Guardado!  😉',
                            text: '¡Número Registrado Exitosamente!',
                            icon: 'success',
                            showConfirmButton: false,
                            confirmButtonColor: '#2892DB',
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                            $('#ModalLlamada').modal();
                            $('#ModalListaNegra').modal();
                        })
                    }else if(Respuesta == "Ya Existe"){
                        Swal.fire({
                            icon: 'info',
                            title: '¿Otra Vez?  🤔',
                            text: 'Este Número Ya Se Encuentra Registrado En El Sistema...',
                            confirmButtonColor: '#2892DB'
                        })
                        $("#Loading_3").hide();
                        $("#BodyModalAddListaNegra").show();
                        console.log(php_response.msg);
                    }else if(Respuesta == "Error"){
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Al Registrar Información!  🤨',
                            text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                            confirmButtonColor: '#2892DB'
                        })
                        $("#Loading_3").hide();
                        $("#BodyModalAddListaNegra").show();
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
    });

    //Actualizar Registro De lista Negra
    $('#BtnActualizar').on('click', function(){
        var Id= $("#InputId").val();
        var Huesped = $("#InputHuesped").val();
        var Numero = $("#InputEditarTelefono").val();
        var Motivo = $("#SelectEditarListaMotivo").val();
        var CantidadNumeros= Numero.length;

        if((Numero == null) || (Numero == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Número De Telefono"',
                confirmButtonColor: '#2892DB'
            })
        }else if ((CantidadNumeros < 7) || (CantidadNumeros > 10)) {
            Swal.fire({
                icon: 'info',
                title: '¡Atención!  🤨',
                text: 'Este Número, No Tiene Los Digitos De Un Numero Telefónico',
                confirmButtonColor: '#2892DB'
            })
        }else if((Motivo == null) || (Motivo == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Motivo o Razón"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            let Formulario = new FormData();
            Formulario.append('Id', Id);
            Formulario.append('Huesped', Huesped);
            Formulario.append('Numero', Numero);
            Formulario.append('Motivo', Motivo);
            $.ajax({
                url: Url+"Controller/ActualizarNumeroListaNegra.php",
                dataType: "json",
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: Formulario,
                success: function (php_response){
                    Respuesta = php_response.msg;
                    if(Respuesta == "Ok"){
                        Swal.fire({
                            title: '¡Actualizado!  😉',
                            text: '¡El Número Se Actualizo Exitosamente!',
                            icon: 'success',
                            showConfirmButton: false,
                            confirmButtonColor: '#2892DB',
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                            $('#ModalLlamada').modal();
                            $('#ModalListaNegra').modal();
                        })
                    }else if(Respuesta == "Ya Existe"){
                        Swal.fire({
                            icon: 'info',
                            title: '¿Otra Vez?  🤔',
                            text: 'Este Número Ya Se Encuentra Registrado En El Sistema...',
                            confirmButtonColor: '#2892DB'
                        })
                        console.log(php_response.msg);
                    }else if(Respuesta == "Error"){
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Al Registrar Información!  🤨',
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
    });

};


//Consultar y Mostrar Formulario Actualizar Registro lista Negra
function ActualizarNumero(Dato, Url){

    let Formulario = new FormData();
    var IdEditar= Dato
    var Huesped = $("#InputHuesped").val();
    Formulario.append('IdEditar', IdEditar);
    Formulario.append('Huesped', Huesped);
    $.ajax({
        url: Url+"Controller/ConsultaNumeroEditar.php",
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
                var Resultado= php_response.NumeroEditar;
                var Array= Resultado[0];
                var Id= Array[3];
                const InputId= document.getElementById("InputId");
                InputId.value = Id;
                var NumeroI= Array[1];
                let Numero = parseInt(NumeroI);
                const InputTelefono = document.getElementById("InputEditarTelefono");
                InputTelefono.value = Numero;
                var Motivo= Array[2];
                const SelectMotivo = document.getElementById("SelectEditarListaMotivo");
                SelectMotivo.value = Motivo;
            }else if (Respuesta == "Nada"){
                console.log(php_response.msg);
                Swal.fire({
                    icon: 'info',
                    title: ' 🤷🏽‍♂️  ¡Nada Por Aqui, Nada Por Alla! ', 
                    text: '!No Se Encontraron Resultados!',
                    confirmButtonColor: '#2892DB'
                })
            }else if (Respuesta == "Error"){
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Al Consultar Información!  🤨',
                    text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                console.log(php_response.Falla);
            }
        },
        error: function(php_response) {
            Swal.fire({
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
    $('#ModalEditarListaNegra').modal();
};

//Eliminar Registro De lista Negra
function EliminarNumero(Dato, Url){
    Swal.fire({
        title: '¿Está Seguro?',
        text: "¿Deseas Eliminar Este Numero De La Lista Negra?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, Eliminar!'
        }).then((result) => {
        if (result.isConfirmed) {
            let Formulario = new FormData();
            var IdEliminar= Dato
            var Huesped = $("#InputHuesped").val();
            Formulario.append('IdEliminar', IdEliminar);
            Formulario.append('Huesped', Huesped);
            $.ajax({
                url: Url+"Controller/EliminarNumeroListaNegra.php",
                dataType: "json",
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: Formulario,
                success: function (php_response){
                    Respuesta = php_response.msg;
                    if(Respuesta == "Ok"){
                        Swal.fire({
                            title: '¡Eliminado   🗑!',
                            text: 'El Numero Ha Sido Eliminado Exitosamente!',
                            icon: 'success',
                            showConfirmButton: false,
                            confirmButtonColor: '#2892DB',
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                            $('#ModalLlamada').modal();
                            $('#ModalListaNegra').modal();
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
};

//Funciones Para Horarios y Acciones
//Dentro De Horario
function PosiblesTareas_DentroH(){
    Tarea= $("#SelectPosiblesTareas").val();
    if(Tarea == "Número Externo"){
        $("#divInputNumeroExterno").show();
        $("#divSelectTroncales").show();
        $("#divSelectAudios").hide();
        $("#divSelectIVR").hide();
        $("#divSelectCampanas").hide();
    }else if(Tarea == "Reproducir Grabación"){
        $("#divSelectAudios").show();
        $("#divSelectIVR").hide();
        $("#divSelectCampanas").hide();
        $("#divInputNumeroExterno").hide();
        $("#divSelectTroncales").hide();
    }else if(Tarea == "Pasar a IVR"){
        $("#divSelectIVR").show();
        $("#divSelectTroncales").hide();
        $("#divSelectCampanas").hide();
        $("#divInputNumeroExterno").hide();
        $("#divSelectAudios").hide();
    }else if(Tarea == "Pasar a Una Campaña"){
        $("#divSelectCampanas").show();
        $("#divSelectTroncales").hide();
        $("#divInputNumeroExterno").hide();
        $("#divSelectAudios").hide();
        $("#divSelectIVR").hide();
    }
    $("#IptTareaActual").val("");
};

//Fuera De Horario
function PosiblesTareas_FueraH(){
    Tarea= $("#FueraHSelectPosiblesTareas").val();
    if(Tarea == "Número Externo"){
        $("#FueraHdivInputNumeroExterno").show();
        $("#FueraHdivSelectTroncales").show();
        $("#FueraHdivSelectAudios").hide();
        $("#FueraHdivSelectIVR").hide();
        $("#FueraHdivSelectCampanas").hide();
    }else if(Tarea == "Reproducir Grabación"){
        $("#FueraHdivSelectAudios").show();
        $("#FueraHdivSelectIVR").hide();
        $("#FueraHdivSelectCampanas").hide();
        $("#FueraHdivInputNumeroExterno").hide();
        $("#FueraHdivSelectTroncales").hide();
    }else if(Tarea == "Pasar a IVR"){
        $("#FueraHdivSelectIVR").show();
        $("#FueraHdivSelectTroncales").hide();
        $("#FueraHdivSelectCampanas").hide();
        $("#FueraHdivInputNumeroExterno").hide();
        $("#FueraHdivSelectAudios").hide();
    }else if(Tarea == "Pasar a Una Campaña"){
        $("#FueraHdivSelectCampanas").show();
        $("#FueraHdivSelectTroncales").hide();
        $("#FueraHdivInputNumeroExterno").hide();
        $("#FueraHdivSelectAudios").hide();
        $("#FueraHdivSelectIVR").hide();
    }
    //$("#IptTareaFueraHora").val("");
};

//Capturar Checks Dias
function CapturarChecksDias(DatosAcciones, Url){
    var ListaAcciones= DatosAcciones;
    console.log("ListaAcciones: ", ListaAcciones);
    var IdRuta= ListaAcciones[0];
    var Accion= ListaAcciones[1];
    var ValorAccion= ListaAcciones[2];
    var IdAccion= ListaAcciones[3];
    var Troncal= ListaAcciones[4];
    var AccionFuera= ListaAcciones[5];
    var ValorAccionFuera= ListaAcciones[6];
    var IdAccionFuera= ListaAcciones[7];
    var TroncalFuera= ListaAcciones[8];
    var Url= Url;

    if((Accion == 2)||(Accion == 4)){
        CrearFlechasRutaEntrate(Accion, IdAccion, AccionFuera, IdAccionFuera, Url);
    }
    
    function RegistrarHorario(DatosHorario, Url) {
        FormularioHorario = new FormData();
        var ListaHorario= DatosHorario;
        console.log("ListaHorario: ", ListaHorario);

        $("#BodyModal").hide();
        $("#ModalHorario").hide();
        $("#divBtnGuardar").hide();
        $("#Loading").show();

        var DiaInicial= ListaHorario[0];
        var DiaFinal= ListaHorario[5];
        if(DiaFinal == undefined){
            var DiaFinal= ListaHorario[0];
        }
        var DiasEjecucion= ListaHorario[1];
        var HoraInicial= ListaHorario[2];
        var HoraFinal= ListaHorario[3];
        var Orden= ListaHorario[4];
        
        FormularioHorario.append('IdRuta', IdRuta);
        FormularioHorario.append('DiasEjecucion', DiasEjecucion);
        FormularioHorario.append('Accion', Accion);
        FormularioHorario.append('ValorAccion', ValorAccion);
        FormularioHorario.append('IdAccion', IdAccion);
        FormularioHorario.append('HoraInicial', HoraInicial);
        FormularioHorario.append('HoraFinal', HoraFinal);
        FormularioHorario.append('DiaInicial', DiaInicial);
        FormularioHorario.append('DiaFinal', DiaFinal);
        FormularioHorario.append('Orden', Orden);
        FormularioHorario.append('Troncal', Troncal);
        FormularioHorario.append('AccionFuera', AccionFuera);
        FormularioHorario.append('ValorAccionFuera', ValorAccionFuera);
        FormularioHorario.append('IdAccionFuera', IdAccionFuera);
        FormularioHorario.append('TroncalFuera', TroncalFuera);
        
        $.ajax({
            url: Url+"Controller/GuardarHorario.php",
            dataType: "json",
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioHorario,
            success: function(php_response) {
                Respuesta = php_response.msg;
                if (Respuesta == "Ok") {
                    Swal.fire({
                        title: '¡Gestion Realizada!  😉',
                        text: '¡Información Guardada Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    })
                } else if (Respuesta == "Error") {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Guardar La Información!  🤨',
                        text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                        confirmButtonColor: '#2892DB'
                    })
                    $("#Loading").hide();
                    $("#BodyModal").show();
                    $("#ModalHorario").show();
                    $("#divBtnGuardar").show();
                    console.log(php_response.msg);
                }
            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Fallo Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                $("#Loading").hide();
                $("#BodyModal").show();
                $("#ModalHorario").show();
                $("#divBtnGuardar").show();
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }
        });

    };
    
    //Todos Los Dias
    CheckLunes= document.getElementById('CheckLunes').checked;
    CheckMartes= document.getElementById('CheckMartes').checked;
    CheckMiercoles= document.getElementById('CheckMiercoles').checked;
    CheckJueves= document.getElementById('CheckJueves').checked;
    CheckViernes= document.getElementById('CheckViernes').checked;
    CheckSabado= document.getElementById('CheckSabado').checked;
    CheckDomingo= document.getElementById('CheckDomingo').checked;
    CheckFestivos= document.getElementById('CheckFestivos').checked;

    //Lunes
    if(CheckLunes == true){
        DatosHorario = [];
        var Dia= "mon";
        var DiasEjecucion= "2_habiles";
        var HoraInicialLunes= $("#HoraILunes").val();
        var HoraFinalLunes= $("#HoraFLunes").val();

        if((HoraInicialLunes == null) || (HoraInicialLunes == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Inicial Del Lunes"',
                confirmButtonColor: '#2892DB'
            })
        }else if((HoraFinalLunes == null) || (HoraFinalLunes == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Final Del Lunes"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            DatosHorario.push(Dia, DiasEjecucion, HoraInicialLunes, HoraFinalLunes, "1");
            RegistrarHorario(DatosHorario, Url);
        }
    }

    //Martes
    if(CheckMartes == true){
        DatosHorario = [];
        var Dia= "tue";
        var DiasEjecucion= "2_habiles";
        var HoraInicialMartes= $("#HoraIMartes").val();
        var HoraFinalMartes= $("#HoraFMartes").val();

        if((HoraInicialMartes == null) || (HoraInicialMartes == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Inicial Del Martes"',
                confirmButtonColor: '#2892DB'
            })
        }else if((HoraFinalMartes == null) || (HoraFinalMartes == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Final Del Martes"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            DatosHorario.push(Dia, DiasEjecucion, HoraInicialMartes, HoraFinalMartes, "2");
            RegistrarHorario(DatosHorario, Url);
        }
    }
    
    //Miercoles
    if(CheckMiercoles == true){
        DatosHorario = [];
        var Dia= "wed";
        var DiasEjecucion= "2_habiles";
        var HoraInicialMiercoles= $("#HoraIMiercoles").val();
        var HoraFinalMiercoles= $("#HoraFMiercoles").val();

        if((HoraInicialMiercoles == null) || (HoraInicialMiercoles == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Inicial Del Miercoles"',
                confirmButtonColor: '#2892DB'
            })
        }else if((HoraFinalMiercoles == null) || (HoraFinalMiercoles == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Final Del Miercoles"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            DatosHorario.push(Dia, DiasEjecucion, HoraInicialMiercoles, HoraFinalMiercoles, "3");
            RegistrarHorario(DatosHorario, Url);
        }

    }

    //Jueves
    if(CheckJueves == true){
        DatosHorario = [];
        var Dia= "thu";
        var DiasEjecucion= "2_habiles";
        var HoraInicialJueves= $("#HoraIJueves").val();
        var HoraFinalJueves= $("#HoraFJueves").val();

        if((HoraInicialJueves == null) || (HoraInicialJueves == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Inicial Del Jueves"',
                confirmButtonColor: '#2892DB'
            })
        }else if((HoraFinalJueves == null) || (HoraFinalJueves == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Final Del Jueves"',
                confirmButtonColor: '#2892DB'
            })  
        }else{
            DatosHorario.push(Dia, DiasEjecucion, HoraInicialJueves, HoraFinalJueves, "4");
            RegistrarHorario(DatosHorario, Url);
        }
    }

    //Viernes
    if(CheckViernes == true){
        DatosHorario = [];
        var Dia= "fri";
        var DiasEjecucion= "2_habiles";
        var HoraInicialViernes= $("#HoraIViernes").val();
        var HoraFinalViernes= $("#HoraFViernes").val();

        if((HoraInicialViernes == null) || (HoraInicialViernes == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Inicial Del Viernes"',
                confirmButtonColor: '#2892DB'
            })
        }else if((HoraFinalViernes == null) || (HoraFinalViernes == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Final Del Viernes"',
                confirmButtonColor: '#2892DB'
            })  
        }else{
            DatosHorario.push(Dia, DiasEjecucion, HoraInicialLunes, HoraFinalLunes, "5");
            RegistrarHorario(DatosHorario, Url);
        }
    }

    //Sabado
    if(CheckSabado == true){
        DatosHorario = [];
        var Dia= "sat";
        var DiasEjecucion= "2_habiles";
        var HoraInicialSabado= $("#HoraISabado").val();
        var HoraFinalSabado= $("#HoraFSabado").val();

        if((HoraInicialSabado == null) || (HoraInicialSabado == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Inicial Del Sabado"',
                confirmButtonColor: '#2892DB'
            })
        }else if((HoraFinalSabado == null) || (HoraFinalSabado == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Final Del Sabado"',
                confirmButtonColor: '#2892DB'
            })  
        }else{
            DatosHorario.push(Dia, DiasEjecucion, HoraInicialSabado, HoraFinalSabado, "7");
            RegistrarHorario(DatosHorario, Url);
        }
    }
    
    //Domingo
    if(CheckDomingo == true){
        DatosHorario = [];
        var Dia= "sun";
        var DiasEjecucion= "1_festivos";
        var HoraInicialDomingo= $("#HoraIDomingo").val();
        var HoraFinalDomingo= $("#HoraFDomingo").val();

        if((HoraInicialDomingo == null) || (HoraInicialDomingo == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Inicial Del Domingo"',
                confirmButtonColor: '#2892DB'
            })
        }else if((HoraFinalDomingo == null) || (HoraFinalDomingo == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Final Del Domingo"',
                confirmButtonColor: '#2892DB'
            })  
        }else{
            DatosHorario.push(Dia, DiasEjecucion, HoraInicialDomingo, HoraFinalDomingo, "8");
            RegistrarHorario(DatosHorario, Url);
        }

    }

    //Festivos
    if(CheckFestivos == true){
        DatosHorario = [];
        var Dia= "hol";
        var DiasEjecucion= "1_festivos";
        var HoraInicialFestivos= $("#HoraIFestivos").val();
        var HoraFinalFestivos= $("#HoraFFestivos").val();

        if((HoraInicialFestivos == null) || (HoraInicialFestivos == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Inicial Del Festivos"',
                confirmButtonColor: '#2892DB'
            })
        }else if((HoraFinalFestivos == null) || (HoraFinalFestivos == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Se Debe Diligenciar El Campo "Hora Final Del Festivos"',
                confirmButtonColor: '#2892DB'
            })  
        }else{
            DatosHorario.push(Dia, DiasEjecucion, HoraInicialFestivos, HoraFinalFestivos, "9");
            RegistrarHorario(DatosHorario, Url);
        }
    }

    //Registrar Fuera De Horario
    var FueraHorario= true;
    if(FueraHorario == true){
        DatosHorario = [];
        var Dia= "mon";
        var DiasEjecucion= "3_ambos";
        var HoraInicialFueraH= "00:02";
        var HoraFinalFuera= "23:59";
        DatosHorario.push(Dia, DiasEjecucion, HoraInicialFueraH, HoraFinalFuera, "10", "sun");
        RegistrarHorario(DatosHorario, Url);
    }

}

//Validacion Campos Vacios Antes De Guardar
function GuardarRutaEntrante(Dirreccion){
    let Formulario = new FormData();
    var Url= CapturarDireccion(Dirreccion);
    
    Estrategia = $("#InputEstpas").val();
    Huesped = $("#InputHuesped").val();
    Nombre = $("#InputNombre").val();
    NumeroEntrada = $("#InputNumeroEntrada").val();
    NumeroLimite = $("#InputNumeroLimite").val();
    InputPausa = $("#InputPausa").val();
    ListaFestivos = $("#SelectListaFestivos").val();
    CheckListaNegra= document.getElementById('CheckListaNegra').checked;
    CheckGenerarTimbre= document.getElementById('CheckGenerarTimbre').checked;
    PosiblesTareas= $("#SelectPosiblesTareas").val();
    PosiblesTareas_FH= $("#FueraHSelectPosiblesTareas").val();
    
    if((Nombre == null) || (Nombre == "")){
        Swal.fire({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Se Debe Diligenciar El Campo "Nombre"',
            confirmButtonColor: '#2892DB'
        })
    }else if((NumeroEntrada == null) || (NumeroEntrada == "")){
        Swal.fire({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Se Debe Diligenciar El Campo "Número De Entrada"',
            confirmButtonColor: '#2892DB'
        })
    }else if((ListaFestivos == null) || (ListaFestivos == "")){
        Swal.fire({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Se Debe Diligenciar El Campo "Lista De Festivos"',
            confirmButtonColor: '#2892DB'
        })
    }else if((PosiblesTareas == null) || (PosiblesTareas == "")){
        Swal.fire({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Se Debe Configurar La Sección De "Acciones"',
            confirmButtonColor: '#2892DB'
        })
    }else if((PosiblesTareas_FH == null) || (PosiblesTareas_FH == "")){
        Swal.fire({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Se Debe Configurar La Sección "Fuera De Horario"',
            confirmButtonColor: '#2892DB'
        })
    }else{

        if((NumeroLimite == null) || (NumeroLimite == "")){
            NumeroLimite= "-1";
        }
        if((InputPausa == null) || (InputPausa == "")){
            InputPausa= "0";
        }

        if(CheckListaNegra == true){
            var ListaNegra= "1";
        }else{
            var ListaNegra= "0";
        }
        if(CheckGenerarTimbre == true){
            var GenerarTimbre= "1";
        }else{
            var GenerarTimbre= "0";
        }

        //Datos Formulario Dentro De Horario
        if(PosiblesTareas == "Pasar a Una Campaña"){
            var Accion= "2";
            var IdAccion= $("#SelectCampanas").val();
            var Select= document.getElementById('SelectCampanas');
            var ValorAccion= Select.options[Select.selectedIndex].text;
            var Troncales= "null";
        }else if((PosiblesTareas == "Reproducir Grabación")){
            var Accion= "3";
            var IdAccion= $("#SelectAudios").val();
            var Select= document.getElementById('SelectAudios');
            var ValorAccion= Select.options[Select.selectedIndex].text;
            var Troncales= "null";
        }else if((PosiblesTareas == "Pasar a IVR")){
            var Accion= "4";
            var IdAccion= $("#SelectIVR").val();
            var Select= document.getElementById('SelectIVR');
            var ValorAccion= Select.options[Select.selectedIndex].text;
            var Troncales= "null";
        }else if((PosiblesTareas == "Número Externo")){
            var Accion= "6";
            var ValorAccion= $("#InputNumeroExterno").val();
            var Troncales= $("#SelectTroncales").val();
            var IdAccion= Troncales;
        }

        //Datos Formulario Fuera De Horario
        console.log("PosiblesTareas_FH: ", PosiblesTareas_FH);
        if(PosiblesTareas_FH == "Pasar a Una Campaña"){
            var Accion_FH= "2";
            var IdAccion_FH= $("#FueraHSelectCampanas").val();
            var Select= document.getElementById('FueraHSelectCampanas');
            var ValorAccion_FH= Select.options[Select.selectedIndex].text;
            var Troncales_FH= "null";
        }else if((PosiblesTareas_FH == "Reproducir Grabación")){
            var Accion_FH= "3";
            var IdAccion_FH= $("#FueraHSelectAudios").val();
            var Select= document.getElementById('FueraHSelectAudios');
            var ValorAccion_FH= Select.options[Select.selectedIndex].text;
            var Troncales_FH= "null";
        }else if((PosiblesTareas_FH == "Pasar a IVR")){
            var Accion_FH= "4";
            var IdAccion_FH= $("#FueraHSelectIVR").val();
            var Select= document.getElementById('FueraHSelectIVR');
            var ValorAccion_FH= Select.options[Select.selectedIndex].text;
            var Troncales_FH= "null";
        }else if((PosiblesTareas_FH == "Número Externo")){
            var Accion_FH= "6";
            var ValorAccion_FH= $("#FueraHInputNumeroExterno").val();
            var Troncales_FH= $("#FueraHSelectTroncales").val();
            var IdAccion_FH= Troncales_FH;
        }

        if((ValorAccion == null) || (ValorAccion == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor a La Tarea Selecionada',
                confirmButtonColor: '#2892DB'
            })
            $('#SelectCampanas').prop("style", "border-color: #D22000");
            $("#InputNumeroExterno").prop("style", "border-color: #D22000");
            $("#SelectTroncales").prop("style", "border-color: #D22000");
            $("#SelectAudios").prop("style", "border-color: #D22000");
            $("#SelectIVR").prop("style", "border-color: #D22000");
            $("#SelectCampanas").prop("style", "border-color: #D22000");

        }else if((ValorAccion_FH == null) || (ValorAccion_FH == "")){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor a La Tarea Selecionada',
                confirmButtonColor: '#2892DB'
            })
            $('#FueraHSelectCampanas').prop("style", "border-color: #D22000");
            $("#FueraHInputNumeroExterno").prop("style", "border-color: #D22000");
            $("#FueraHSelectTroncales").prop("style", "border-color: #D22000");
            $("#FueraHSelectAudios").prop("style", "border-color: #D22000");
            $("#FueraHSelectIVR").prop("style", "border-color: #D22000");
            $("#FueraHSelectCampanas").prop("style", "border-color: #D22000");

        }else{
            $("#BodyModal").hide();
            $("#ModalHorario").hide();
            $("#divBtnGuardar").hide();
            $("#Loading").show();
            
            Formulario.append('Estrategia', Estrategia);
            Formulario.append('Huesped', Huesped);
            Formulario.append('Nombre', Nombre);
            Formulario.append('NumeroEntrada', NumeroEntrada);
            Formulario.append('NumeroLimite', NumeroLimite);
            Formulario.append('InputPausa', InputPausa);
            Formulario.append('ListaFestivos', ListaFestivos);
            Formulario.append('ListaNegra', ListaNegra);
            Formulario.append('GenerarTimbre', GenerarTimbre);
            
            $.ajax({
                url: Url+"Controller/GuardarRutaEntrante.php",
                dataType: "json",
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: Formulario,
                success: function(php_response) {
                    Respuesta = php_response.msg;
                    if (Respuesta == "Ok") {
                        DatosAcciones = [];
                        var IdRutaEntrante= php_response.Id;
                        DatosAcciones.push(IdRutaEntrante, Accion, ValorAccion, IdAccion, Troncales, Accion_FH, ValorAccion_FH, IdAccion_FH, Troncales_FH);
                        //DatosAcciones.push(IdRutaEntrante, Accion, ValorAccion, Troncales, Accion_FH, ValorAccion_FH, Troncales_FH);
                        CapturarChecksDias(DatosAcciones, Url);

                        //var Resp= php_response.Resp;
                        //console.log("Resp: ", Resp); 

                        Swal.fire({
                            title: '¡Gestion Realizada!  😉',
                            text: '¡Información Guardada Exitosamente!',
                            icon: 'success',
                            showConfirmButton: false,
                            confirmButtonColor: '#2892DB',
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        })

                    }else if (Respuesta == "Error") {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Al Guardar La Información!  🤨',
                            text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                            confirmButtonColor: '#2892DB'
                        })
                        $("#Loading").hide();
                        $("#BodyModal").show();
                        $("#ModalHorario").show();
                        $("#divBtnGuardar").show();
                        console.log(php_response.msg);
                    }
                },
                error: function(php_response) {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Fallo Servidor!  😵',
                        text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                        confirmButtonColor: '#2892DB'
                    })
                    $("#Loading").hide();
                    $("#BodyModal").show();
                    $("#ModalHorario").show();
                    $("#divBtnGuardar").show();
                    php_response = JSON.stringify(php_response);
                    console.log(php_response);
                }
            });
            
        }
        
    }
};

//Actualizar Ruta Entrante
function EnviarDatosActualizar(Formulario, Accion, ValorAccion, IdAccion, Troncales, Accion_FH, ValorAccion_FH, IdAccion_FH, Troncales_FH, Dirreccion){
    $("#BodyModal").hide();
    $("#ModalHorario").hide();
    $("#divBtnActualizar").hide();
    $("#Loading").show();

    var Url= CapturarDireccion(Dirreccion);
    $.ajax({
        url: Url+"Controller/ActualizarRutaEntrante.php",
        dataType: "json",
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        data: Formulario,
        success: function(php_response) {
            Respuesta = php_response.msg;
            if (Respuesta == "Ok") {
                DatosAcciones = [];
                var IdRutaEntrante= $("#IdRutaEntrante").val();
                DatosAcciones.push(IdRutaEntrante, Accion, ValorAccion, IdAccion, Troncales, Accion_FH, ValorAccion_FH, IdAccion_FH, Troncales_FH);
                //console.log("DatosAcciones: ", DatosAcciones);
                
                CapturarChecksDias(DatosAcciones, Url);

                Swal.fire({
                    title: '¡Actualizado!  😉',
                    text: '¡Información Actualizada Exitosamente!',
                    icon: 'success',
                    showConfirmButton: false,
                    confirmButtonColor: '#2892DB',
                    timer: 3000
                }).then(() => {
                    $("#BodyModal").show();
                    $("#ModalHorario").show();
                    $("#divBtnGuardar").show();
                    $("#Loading").hide();
                    window.location.reload();
                })

            }else if (Respuesta == "Error") {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Al Actualizar La Información!  🤨',
                    text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                $("#Loading").hide();
                $("#BodyModal").show();
                $("#ModalHorario").show();
                $("#divBtnActualizar").show();
                console.log(php_response.msg);
            }
        },
        error: function(php_response) {
            Swal.fire({
                icon: 'error',
                title: '¡Fallo Servidor!  😵',
                text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                confirmButtonColor: '#2892DB'
            })
            $("#Loading").hide();
            $("#BodyModal").show();
            $("#ModalHorario").show();
            $("#divBtnActualizar").show();
            php_response = JSON.stringify(php_response);
            console.log(php_response);
        }
    });
}

//Validacion Campos Vacios Antes De Actualizar
function ActualizarRutaEntrante(Dirreccion){
    let Formulario = new FormData();
    
    IdEstrategia= $("#InputEstpas").val();
    Huesped = $("#InputHuesped").val();
    Nombre = $("#InputNombre").val();
    NumeroEntrada = $("#InputNumeroEntrada").val();
    NumeroLimite = $("#InputNumeroLimite").val();
    InputPausa = $("#InputPausa").val();
    ListaFestivos = $("#SelectListaFestivos").val();
    CheckListaNegra= document.getElementById('CheckListaNegra').checked;
    CheckGenerarTimbre= document.getElementById('CheckGenerarTimbre').checked;
    PosiblesTareas= $("#SelectPosiblesTareas").val();
    PosiblesTareas_FH= $("#FueraHSelectPosiblesTareas").val();

    
    if((Nombre == null) || (Nombre == "")){
        Swal.fire({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Se Debe Diligenciar El Campo "Nombre"',
            confirmButtonColor: '#2892DB'
        })
    }else if((NumeroEntrada == null) || (NumeroEntrada == "")){
        Swal.fire({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Se Debe Diligenciar El Campo "Número De Entrada"',
            confirmButtonColor: '#2892DB'
        })
    }else if((ListaFestivos == null) || (ListaFestivos == "")){
        Swal.fire({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Se Debe Diligenciar El Campo "Lista De Festivos"',
            confirmButtonColor: '#2892DB'
        })
    }else if((PosiblesTareas == null) || (PosiblesTareas == "")){
        Swal.fire({
            icon: 'error',
            title: '🤨 Oops...',
            text: 'Se Debe Configurar La Sección De "Acciones"',
            confirmButtonColor: '#2892DB'
        })
    }else{

        if((NumeroLimite == null) || (NumeroLimite == "")){
            NumeroLimite= "-1";
        }
        if((InputPausa == null) || (InputPausa == "")){
            InputPausa= "0";
        }

        if(CheckListaNegra == true){
            var ListaNegra= "1";
        }else{
            var ListaNegra= "0";
        }
        if(CheckGenerarTimbre == true){
            var GenerarTimbre= "1";
        }else{
            var GenerarTimbre= "0";
        }

        //Datos Formulario Dentro De Horario
        if(PosiblesTareas == "Pasar a Una Campaña"){
            var Accion= "2";
            var IdAccion= $("#SelectCampanas").val();
            var Select= document.getElementById('SelectCampanas');
            var ValorAccion= Select.options[Select.selectedIndex].text;
            var Troncales= "null";
        }else if((PosiblesTareas == "Reproducir Grabación")){
            var Accion= "3";
            var IdAccion= $("#SelectAudios").val();
            var Select= document.getElementById('SelectAudios');
            var ValorAccion= Select.options[Select.selectedIndex].text;
            var Troncales= "null";
        }else if((PosiblesTareas == "Pasar a IVR")){
            var Accion= "4";
            var IdAccion= $("#SelectIVR").val();
            var Select= document.getElementById('SelectIVR');
            var ValorAccion= Select.options[Select.selectedIndex].text;
            var Troncales= "null";
        }else if((PosiblesTareas == "Número Externo")){
            var Accion= "6";
            var ValorAccion= $("#InputNumeroExterno").val();
            var Troncales= $("#SelectTroncales").val();
            var IdAccion= Troncales;
        }

        //Datos Formulario Fuera De Horario
        if(PosiblesTareas_FH == "Pasar a Una Campaña"){
            var Accion_FH= "2";
            var IdAccion_FH= $("#FueraHSelectCampanas").val();
            var Select= document.getElementById('FueraHSelectCampanas');
            var ValorAccion_FH= Select.options[Select.selectedIndex].text;
            var Troncales_FH= "null";
        }else if((PosiblesTareas_FH == "Reproducir Grabación")){
            var Accion_FH= "3";
            var IdAccion_FH= $("#FueraHSelectAudios").val();
            var Select= document.getElementById('FueraHSelectAudios');
            var ValorAccion_FH= Select.options[Select.selectedIndex].text;
            var Troncales_FH= "null";
        }else if((PosiblesTareas_FH == "Pasar a IVR")){
            var Accion_FH= "4";
            var IdAccion_FH= $("#FueraHSelectIVR").val();
            var Select= document.getElementById('FueraHSelectIVR');
            var ValorAccion_FH= Select.options[Select.selectedIndex].text;
            var Troncales_FH= "null";
        }else if((PosiblesTareas_FH == "Número Externo")){
            var Accion_FH= "6";
            var ValorAccion_FH= $("#FueraHInputNumeroExterno").val();
            var Troncales_FH= $("#FueraHSelectTroncales").val();
            var IdAccion_FH= Troncales_FH;
        }

        
        //Si Dentro De Horario es vacio
        if((IdAccion == null) || (IdAccion == "0") || (IdAccion == undefined)){
            Swal.fire({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor a La Tarea Selecionada',
                confirmButtonColor: '#2892DB'
            })
            $('#SelectCampanas').prop("style", "border-color: #D22000");
            $("#InputNumeroExterno").prop("style", "border-color: #D22000");
            $("#SelectTroncales").prop("style", "border-color: #D22000");
            $("#SelectAudios").prop("style", "border-color: #D22000");
            $("#SelectIVR").prop("style", "border-color: #D22000");
            $("#SelectCampanas").prop("style", "border-color: #D22000");
        }else{
            
            //Si Fuera De Horario es vacio 
            if((IdAccion_FH == null) || (IdAccion_FH == "") || (IdAccion_FH == "0")){
                Swal.fire({
                    icon: 'error',
                    title: '🤨 Oops...',
                    text: 'Debe Agregar Un Valor a La Tarea Selecionada',
                    confirmButtonColor: '#2892DB'
                })
                $('#FueraHSelectCampanas').prop("style", "border-color: #D22000");
                $("#FueraHInputNumeroExterno").prop("style", "border-color: #D22000");
                $("#FueraHSelectTroncales").prop("style", "border-color: #D22000");
                $("#FueraHSelectAudios").prop("style", "border-color: #D22000");
                $("#FueraHSelectIVR").prop("style", "border-color: #D22000");
                $("#FueraHSelectCampanas").prop("style", "border-color: #D22000");
            }else{

                //Todo Ok
                if(PosiblesTareas_FH == "Pasar a Una Campaña"){
                    var Accion_FH= "2";
                    var IdAccion_FH= $("#FueraHSelectCampanas").val();
                    var Select= document.getElementById('FueraHSelectCampanas');
                    var ValorAccion_FH= Select.options[Select.selectedIndex].text;
                    var Troncales_FH= "null";
                }else if((PosiblesTareas_FH == "Reproducir Grabación")){
                    var Accion_FH= "3";
                    var IdAccion_FH= $("#FueraHSelectAudios").val();
                    var Select= document.getElementById('FueraHSelectAudios');
                    var ValorAccion_FH= Select.options[Select.selectedIndex].text;
                    var Troncales_FH= "null";
                }else if((PosiblesTareas_FH == "Pasar a IVR")){
                    var Accion_FH= "4";
                    var IdAccion_FH= $("#FueraHSelectIVR").val();
                    var Select= document.getElementById('FueraHSelectIVR');
                    var ValorAccion_FH= Select.options[Select.selectedIndex].text;
                    var Troncales_FH= "null";
                }else if((PosiblesTareas_FH == "Número Externo")){
                    var Accion_FH= "6";
                    var ValorAccion_FH= $("#FueraHInputNumeroExterno").val();
                    var Troncales_FH= $("#FueraHSelectTroncales").val();
                    var IdAccion_FH= Troncales_FH;
                }
                
                Formulario.append('IdEstrategia', IdEstrategia);
                Formulario.append('Huesped', Huesped);
                Formulario.append('Nombre', Nombre);
                Formulario.append('NumeroEntrada', NumeroEntrada);
                Formulario.append('NumeroLimite', NumeroLimite);
                Formulario.append('InputPausa', InputPausa);
                Formulario.append('ListaFestivos', ListaFestivos);
                Formulario.append('ListaNegra', ListaNegra);
                Formulario.append('GenerarTimbre', GenerarTimbre);
                console.log("(☞ツ)☞   Ok");
                EnviarDatosActualizar(Formulario, Accion, ValorAccion, IdAccion, Troncales, Accion_FH, ValorAccion_FH, IdAccion_FH, Troncales_FH, Dirreccion);
            
            }
        }
    }
}

//Crear Conexiones Ruta Entrate
function CrearFlechasRutaEntrate(Accion, IdAccion, AccionFuera, IdAccionFuera, Url) {
    let FormularioFlecha = new FormData();
    var IdEstrategia= $("#InputEstpas").val();
    var IdFlujograma= $("#IdFlujograma").val();
    
    if(Accion == 2){
        var TipoAccion= "Campaña";
    }else if(Accion == 4){
        var TipoAccion= "IVR";
    }
    
    if(AccionFuera == 2){
        var TipoAccionFuera= "Campaña";
    }else if(AccionFuera == 4){
        var TipoAccionFuera= "IVR";
    }

    console.log("IdEstrategia: ", IdEstrategia);
    console.log("IdFlujograma: ", IdFlujograma);
    console.log("TipoAccion: ", TipoAccion);
    console.log("IdAccion: ", IdAccion);
    console.log("TipoAccionFuera: ", TipoAccionFuera);
    console.log("IdAccionFuera: ", IdAccionFuera);


    FormularioFlecha.append('IdEstrategia', IdEstrategia);
    FormularioFlecha.append('IdFlujograma', IdFlujograma);
    FormularioFlecha.append('TipoAccion', TipoAccion);
    FormularioFlecha.append('IdAccion', IdAccion);
    FormularioFlecha.append('TipoAccionFuera', TipoAccionFuera);
    FormularioFlecha.append('IdAccionFuera', IdAccionFuera);

    $.ajax({
        url: Url+"Controller/CrearFlechas.php",
        dataType: "json",
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        data: FormularioFlecha,
        success: function(php_response) {
            Respuesta = php_response.msg;
            if (Respuesta == "Ok") {
                console.log("Respuesta: ", Respuesta);


            }else if (Respuesta == "Error") {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Al Guardar La Información!  🤨',
                    text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                $("#Loading").hide();
                $("#BodyModal").show();
                $("#ModalHorario").show();
                $("#divBtnGuardar").show();
                console.log(php_response.msg);
            }

        },
        error: function(php_response) {
            Swal.fire({
                icon: 'error',
                title: '¡Fallo Servidor!  😵',
                text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                confirmButtonColor: '#2892DB'
            })
            $("#Loading").hide();
            $("#BodyModal").show();
            $("#ModalHorario").show();
            $("#divBtnGuardar").show();
            php_response = JSON.stringify(php_response);
            console.log(php_response);
        }
    });

}


//Habilitar y Deshabilitar Campos Hora
$("#CheckLunes").click(function(){
    var CheckLunes= document.getElementById('CheckLunes').checked;
    if(CheckLunes == true){
        $("#HoraILunes").prop('disabled', false);
        $("#HoraFLunes").prop('disabled', false);
        HoraPorDefectoLunes();
    }else{
        $("#HoraILunes").prop('disabled', true);
        $("#HoraILunes").val("");
        $("#HoraFLunes").prop('disabled', true);
        $("#HoraFLunes").val("");
    }
});
$("#CheckMartes").click(function(){
    var CheckMartes= document.getElementById('CheckMartes').checked;
    if(CheckMartes == true){
        $("#HoraIMartes").prop('disabled', false);
        $("#HoraFMartes").prop('disabled', false);
        HoraPorDefectoMartes();
    }else{
        $("#HoraIMartes").prop('disabled', true);
        $("#HoraIMartes").val("");
        $("#HoraFMartes").prop('disabled', true);
        $("#HoraFMartes").val("");
    }
});
$("#CheckMiercoles").click(function(){
    var CheckMiercoles= document.getElementById('CheckMiercoles').checked;
    console.log("CheckMiercoles: ", CheckMiercoles);
    if(CheckMiercoles == true){
        $("#HoraIMiercoles").prop('disabled', false);
        $("#HoraFMiercoles").prop('disabled', false);
        HoraPorDefectoMiercoles();
    }else{
        $("#HoraIMiercoles").prop('disabled', true);
        $("#HoraIMiercoles").val("");
        $("#HoraFMiercoles").prop('disabled', true);
        $("#HoraFMiercoles").val("");
    }
});
$("#CheckJueves").click(function(){
    var CheckJueves= document.getElementById('CheckJueves').checked;
    if(CheckJueves == true){
        $("#HoraIJueves").prop('disabled', false);
        $("#HoraFJueves").prop('disabled', false);
        HoraPorDefectoJueves();
    }else{
        $("#HoraIJueves").prop('disabled', true);
        $("#HoraIJueves").val("");
        $("#HoraFJueves").prop('disabled', true);
        $("#HoraFJueves").val("");
    }
});
$("#CheckViernes").click(function(){
    var CheckViernes= document.getElementById('CheckViernes').checked;
    if(CheckViernes == true){
        $("#HoraIViernes").prop('disabled', false);
        $("#HoraFViernes").prop('disabled', false);
        HoraPorDefectoViernes();
    }else{
        $("#HoraIViernes").prop('disabled', true);
        $("#HoraIViernes").val("");
        $("#HoraFViernes").prop('disabled', true);
        $("#HoraFViernes").val("");
    }
});
$("#CheckSabado").click(function(){
    var CheckSabado= document.getElementById('CheckSabado').checked;
    if(CheckSabado == true){
        $("#HoraISabado").prop('disabled', false);
        $("#HoraFSabado").prop('disabled', false);
        HoraPorDefectoSabado();
    }else{
        $("#HoraISabado").prop('disabled', true);
        $("#HoraISabado").val("");
        $("#HoraFSabado").prop('disabled', true);
        $("#HoraFSabado").val("");
    }
});
$("#CheckDomingo").click(function(){
    var CheckDomingo= document.getElementById('CheckDomingo').checked;
    if(CheckDomingo == true){
        $("#HoraIDomingo").prop('disabled', false);
        $("#HoraFDomingo").prop('disabled', false);
        HoraPorDefectoDomingo();
    }else{
        $("#HoraIDomingo").prop('disabled', true);
        $("#HoraIDomingo").val("");
        $("#HoraFDomingo").prop('disabled', true);
        $("#HoraFDomingo").val("");
    }
});
$("#CheckFestivos").click(function(){
    var CheckFestivos= document.getElementById('CheckFestivos').checked;
    if(CheckFestivos == true){
        $("#HoraIFestivos").prop('disabled', false);
        $("#HoraFFestivos").prop('disabled', false);
        HoraPorDefectoFestivos();
    }else{
        $("#HoraIFestivos").prop('disabled', true);
        $("#HoraIFestivos").val("");
        $("#HoraFFestivos").prop('disabled', true);
        $("#HoraFFestivos").val("");
    }
});


//Establecer Horas Por Defecto
function HoraPorDefectoLunes() {
    document.getElementById("HoraILunes").value= "08:00:00";
    document.getElementById("HoraFLunes").value= "17:00:00";
}
function HoraPorDefectoMartes(){
    document.getElementById("HoraIMartes").value= "08:00:00";
    document.getElementById("HoraFMartes").value= "17:00:00";
}
function HoraPorDefectoMiercoles() {
    document.getElementById("HoraIMiercoles").value= "08:00:00";
    document.getElementById("HoraFMiercoles").value= "17:00:00";
}
function HoraPorDefectoJueves() {
    document.getElementById("HoraIJueves").value= "08:00:00";
    document.getElementById("HoraFJueves").value= "17:00:00";
}
function HoraPorDefectoViernes() {
    document.getElementById("HoraIViernes").value= "08:00:00";
    document.getElementById("HoraFViernes").value= "17:00:00";
}
function HoraPorDefectoSabado() {
    document.getElementById("HoraISabado").value= "08:00:00";
    document.getElementById("HoraFSabado").value= "14:00:00";
}
function HoraPorDefectoDomingo() {
    document.getElementById("HoraIDomingo").value= "08:00:00";
    document.getElementById("HoraFDomingo").value= "17:00:00";
}
function HoraPorDefectoFestivos() {
    document.getElementById("HoraIFestivos").value= "08:00:00";
    document.getElementById("HoraFFestivos").value= "17:00:00";
}
