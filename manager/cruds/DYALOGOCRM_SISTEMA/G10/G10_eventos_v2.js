$(function () {

    $("#activarChat_1").change(function () {
        if ($(this).is(":checked")) {
            $('#s_chat_web').find('input, textarea, button, select').attr('disabled', false);
        } else {
            $('#s_chat_web').find('input, textarea, button, select').attr('disabled', true);
            $('#activarChat_1').attr('disabled', false);
        }
    });

    $("#activarChat_2").change(function () {
        if ($(this).is(":checked")) {
            $('#s_chat_whatsapp').find('input, textarea, button, select').attr('disabled', false);
        } else {
            $('#s_chat_whatsapp').find('input, textarea, button, select').attr('disabled', true);
            $('#activarChat_2').attr('disabled', false);
        }
    });

    $("#activarChat_3").change(function () {
        if ($(this).is(":checked")) {
            $('#s_chat_facebook').find('input, textarea, button, select').attr('disabled', false);

        } else {
            $('#s_chat_facebook').find('input, textarea, button, select').attr('disabled', true);
            $('#activarChat_3').attr('disabled', false);
        }
    });

    $("#ActivaMailCampana").change(function () {
        if ($(this).is(":checked")) {
            $('#s_32').find('input, textarea, button, select').attr('disabled', false);
        } else {
            $('#s_32').find('input, textarea, button, select').attr('disabled', true);
            $('#ActivaMailCampana').attr('disabled', false);
        }
    });

    //solo numeros
    $('.input-number').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    //function para HUESPED 

    $("#G10_C70").on('blur', function (e) { });

    //function para NOMBRE 

    $("#G10_C71").on('blur', function (e) { });

    //function para ACTIVA 

    $("#G10_C72").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para SCRIPT 


    $('#MailTipoCondicion').change(function () {
        var tipo_condicion = $(this).val();
        if (tipo_condicion != 100) {
            $('#MailCondicion').attr('disabled', false);
        } else {
            $('#MailCondicion').attr('disabled', true);
        }
    });


    //function para TIPO 

    $("#G10_C76").change(function () {
        $("#Seccion_Busqueda_TEL").hide();
        if ($(this).val() == '3') {
            $(".Seccion_Busqueda_IVR").prop("disabled", false);
        }
        
        if ($(this).val() == '2') {
            //$("#Seccion_Busqueda_TEL").show();
            $(".Seccion_Busqueda_IVR").prop("disabled", false);
        }

        if ($(this).val() == '1') {
            $(".Seccion_Busqueda_IVR").prop("disabled", true);
        }
    });

    // CAMBIO TIPO DE BÚSQUEDA
    // $(".groupTipoBusqueda").hide();
    // $("#checkBusqueda").change(function(){
    //     if($("#checkBusqueda").is(':checked')){
    //         $(".groupTipoBusqueda").show();
    //     }else{
    //         $(".groupTipoBusqueda").hide();
    //         $('input[name="TipoBusqManual"]').prop('checked', false);
    //     }
    // });

    // CAMBIO PERMITE INSERTAR REGISTRO
    $("#insertRegistro").change(function(){
        if($(this).val()== -1){
            $("#insertAuto").prop('disabled',false);
        }else{
            $("#insertAuto").prop('disabled',true);
        }
    });


    //function para ID CAMPAÑA CBX MARCADOR 

    $("#G10_C105").on('blur', function (e) { });

    //function para ID CAMPAÑA CBX ACD 

    $("#G10_C106").on('blur', function (e) { });

    //function para PAUSA CBX 

    $("#G10_C107").on('blur', function (e) { });

    //function para ACTUALIZAR BD 

    $("#G10_C78").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para MANEJA LIMITE DE REINTENTOS 

    $("#G10_C79").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para LIMITE DE REINTENTOS 

    $("#G10_C80").on('blur', function (e) { });

    //function para DETENER AUTOMÁTICAMENTE 

    $("#G10_C81").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para META CONTACTOS EFECTIVOS 

    $("#G10_C82").on('blur', function (e) { });

    //function para MAXIMO DIAS AGENDA 

    $("#G10_C83").on('blur', function (e) { });

    //function para MAXIMO AGENDAS POR DIA 

    $("#G10_C84").on('blur', function (e) { });

    //function para DETECCION DE MAQUINA 

    $("#G10_C85").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });


    //function para ACCION MAQUINA 

    $("#G10_C91").change(function () {

    });

    //function para ACELERACION 

    $("#G10_C92").on('blur', function (e) { });

    //function para CANTIDAD REGISTROS AISGNACION DINAMIMCA 

    $("#G10_C93").on('blur', function (e) { });

    //function para TIEMPO PREVIEW 

    $("#G10_C94").on('blur', function (e) { });

    //function para PRIORIZA ENTRANTES 

    $("#G10_C95").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para TIPIFICAICON BLEND 

    $("#G10_C98").change(function () {

    });

    //function para TIEMPO DE ESPERA ENTRE LLAMADAS 

    $("#G10_C99").on('blur', function (e) { });

    //function para PERMITE AGREGAR REGISTROS 

    $("#G10_C100").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para TIPIFICACION ERRADA 

    $("#G10_C101").change(function () {

    });

    //function para TIME OUT CLOSE 

    $("#G10_C102").on('blur', function (e) { });

    //function para CAMPO TIPIFICACION 

    $("#G10_C103").change(function () {

    });

    //function para TIPIFICACION TIME OUT 

    $("#G10_C104").change(function () {

    });

    //function para Lunes 

    $("#G10_C108").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para Hora Inicial Lunes 

    $("#G10_C109").on('blur', function (e) { });

    //function para Hora Final Lunes 

    $("#G10_C110").on('blur', function (e) { });

    //function para Martes 

    $("#G10_C111").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para Hora Inicial Martes 

    $("#G10_C112").on('blur', function (e) { });

    //function para Hora Final Martes 

    $("#G10_C113").on('blur', function (e) { });

    //function para Miercoles 

    $("#G10_C114").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para Hora Inicial Miercoles 

    $("#G10_C115").on('blur', function (e) { });

    //function para Hora Final Miercoles 

    $("#G10_C116").on('blur', function (e) { });

    //function para Jueves 

    $("#G10_C117").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para Hora Inicial Jueves 

    $("#G10_C118").on('blur', function (e) { });

    //function para Hora Final Jueves 

    $("#G10_C119").on('blur', function (e) { });

    //function para Viernes 

    $("#G10_C120").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para Hora Inicial Viernes 

    $("#G10_C121").on('blur', function (e) { });

    //function para Hora Final Viernes 

    $("#G10_C122").on('blur', function (e) { });

    //function para Sabado 

    $("#G10_C123").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para Hora Inicial Sabado 

    $("#G10_C124").on('blur', function (e) { });

    //function para Hora Final Sabado 

    $("#G10_C125").on('blur', function (e) { });

    //function para Domingo 

    $("#G10_C126").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para Hora Inicial Domingo 

    $("#G10_C127").on('blur', function (e) { });

    //function para Hora Final Domingo 

    $("#G10_C128").on('blur', function (e) { });

    //function para Festivos 

    $("#G10_C129").change(function () {
        if ($(this).is(":checked")) {

        } else {

        }
    });

    //function para Hora Inicial festivos 

    $("#G10_C130").on('blur', function (e) { });

    //function para Hora Final Festivos 

    $("#G10_C131").on('blur', function (e) { });
});

/**
 * [requiredCampo] Creamos una propieda en el HTML y la carapturamos desde el [div]
 */

const requiredCampo = () => {
    let value = true
    const campo = document.querySelectorAll('div[requiredSelect]')
    const collapseLlamadas = document.querySelector('.collapseLlamadas')

    // console.log('collapseLlamadas :>> ', collapseLlamadas);
    try {
        campo.forEach(element => {
            const select = element.querySelector('select')
            const label = element.querySelector('label')
            if (select.value === '0') {
                value = false
                element.classList.add('has-error')
                //collapseLlamadas === null ? null : collapseLlamadas.setAttribute('style', 'color:red')
                console.log('label :>> ', label.textContent);
                label.textContent === "" ? null : alertify.error(`El campo ${label.textContent} es obligatorio`)
            } else {
                element.classList.remove('has-error')
            }
        });

    } catch (error) {
        console.log(error);
    }

    return value;
}

function before_save() {
    let valido = true;
    if ($("#G10_C108").is(":checked")) {
        if ($("#G10_C109").val().lenght < 1) {
            valido = false;
        }

        if ($("#G10_C110").val().lenght < 1) {
            valido = false;
        }

        var hora1 = $("#G10_C109").val().replace(':', '');
        var hora2 = $("#G10_C110").val().replace(':', '');
        hora1 = hora1.replace(':', '');
        hora2 = hora2.replace(':', '');
        if (hora1 > hora2) {
            valido = false;
        }
    }

    if ($("#G10_C111").is(":checked")) {
        if ($("#G10_C112").val().lenght < 1) {
            valido = false;
        }

        if ($("#G10_C113").val().lenght < 1) {
            valido = false;
        }

        var hora1 = $("#G10_C112").val().replace(':', '');
        var hora2 = $("#G10_C113").val().replace(':', '');
        hora1 = hora1.replace(':', '');
        hora2 = hora2.replace(':', '');
        if (hora1 > hora2) {
            valido = false;
        }
    }

    if ($("#G10_C114").is(":checked")) {
        if ($("#G10_C115").val().lenght < 1) {
            valido = false;
        }

        if ($("#G10_C116").val().lenght < 1) {
            valido = false;
        }

        var hora1 = $("#G10_C115").val().replace(':', '');
        var hora2 = $("#G10_C116").val().replace(':', '');
        hora1 = hora1.replace(':', '');
        hora2 = hora2.replace(':', '');
        if (hora1 > hora2) {
            valido = false;
        }
    }

    if ($("#G10_C117").is(":checked")) {
        if ($("#G10_C118").val().lenght < 1) {
            valido = false;
        }

        if ($("#G10_C119").val().lenght < 1) {
            valido = false;
        }

        var hora1 = $("#G10_C118").val().replace(':', '');
        var hora2 = $("#G10_C119").val().replace(':', '');
        hora1 = hora1.replace(':', '');
        hora2 = hora2.replace(':', '');
        if (hora1 > hora2) {
            valido = false;
        }
    }

    if ($("#G10_C120").is(":checked")) {
        if ($("#G10_C121").val().lenght < 1) {
            valido = false;
        }

        if ($("#G10_C122").val().lenght < 1) {
            valido = false;
        }

        var hora1 = $("#G10_C121").val().replace(':', '');
        var hora2 = $("#G10_C122").val().replace(':', '');
        hora1 = hora1.replace(':', '');
        hora2 = hora2.replace(':', '');
        if (hora1 > hora2) {
            valido = false;
        }
    }

    if ($("#G10_C123").is(":checked")) {
        if ($("#G10_C124").val().lenght < 1) {
            valido = false;
        }

        if ($("#G10_C125").val().lenght < 1) {
            valido = false;
        }

        var hora1 = $("#G10_C124").val().replace(':', '');
        var hora2 = $("#G10_C125").val().replace(':', '');
        hora1 = hora1.replace(':', '');
        hora2 = hora2.replace(':', '');
        if (hora1 > hora2) {
            valido = false;
        }
    }

    if ($("#G10_C126").is(":checked")) {
        if ($("#G10_C127").val().lenght < 1) {
            valido = false;
        }

        if ($("#G10_C128").val().lenght < 1) {
            valido = false;
        }

        var hora1 = $("#G10_C127").val().replace(':', '');
        var hora2 = $("#G10_C128").val().replace(':', '');
        hora1 = hora1.replace(':', '');
        hora2 = hora2.replace(':', '');
        if (hora1 > hora2) {
            valido = false;
        }
    }

    if ($("#G10_C129").is(":checked")) {
        if ($("#G10_C130").val().lenght < 1) {
            valido = false;
        }

        if ($("#G10_C131").val().lenght < 1) {
            valido = false;
        }

        var hora1 = $("#G10_C130").val().replace(':', '');
        var hora2 = $("#G10_C131").val().replace(':', '');
        hora1 = hora1.replace(':', '');
        hora2 = hora2.replace(':', '');
        if (hora1 > hora2) {
            valido = false;
        }
    }

    if($("#G10_C77").val() == '0' && $("#G10_C77_pre").val() == '-1'){
        valido=false;
        $("#modalPredefinida").modal();
    }

    return valido;
}

function after_save() { }

function after_save_error() { }

function before_edit() { }

function after_edit() { }

function before_add() { }

function after_add() { }

function before_delete() { }

function after_delete() { }

function after_delete_error() { }