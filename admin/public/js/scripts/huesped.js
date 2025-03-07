$(document).ready(function(){

    ocultarCollapse();

    $('.text-hora').timepicker({
        timeFormat: 'HH:mm:ss',
    });

    // $('input[type="checkbox"].minimal').iCheck({
    //     checkboxClass: 'icheckbox_minimal-blue'
    // });

    function ocultarCollapse(){
        $('.e-collapse').hide();
    }

    function mostrarCollapse(){
        // Menu tabs
        $('.e-collapse').show();
    }

    $(".chb").change(function() {
        $(".chb").prop('checked', false);
        $(this).prop('checked', true);
    });

    function validarCampos(accion = 'nuevo'){

        $('.error').remove();
        var mensajeObligatorio = '<label class="error">Este campo es obligatorio.</label>';
        var mensajeSizeMax = '<label class="error">El archivo no debe superar los 3MB.</label>';
        var mensajeExtencion = '<label class="error">El archivo no tiene la extensión adecuada.</label>';
        var valido = true;

        $('#collapseHuesped').collapse('show');

        if($('#form-huesped #nombre').val().length < 1){
            $('#form-huesped #nombre').focus();
            $('#form-huesped #nombre').after(mensajeObligatorio);
            valido = false;
        }

        if($('#form-huesped #direccion').val().length < 1){
            $('#form-huesped #direccion').focus();
            $('#form-huesped #direccion').after(mensajeObligatorio);
            valido = false;
        }

        if($('#form-huesped #razon_social').val().length < 1){
            $('#form-huesped #razon_social').focus();
            $('#form-huesped #razon_social').after(mensajeObligatorio);
            valido = false;
        }

        if($('#form-huesped #nit').val().length < 1){
            $('#form-huesped #nit').focus();
            $('#form-huesped #nit').after(mensajeObligatorio);
            valido = false;
        }

        if($('#form-huesped #pais').val().length < 1){
            $('#form-huesped #pais').focus();
            $('#form-huesped #pais').after(mensajeObligatorio);
            valido = false;
        }

        if($('#form-huesped #ciudad').val().length < 1){
            $('#form-huesped #ciudad').focus();
            $('#form-huesped #ciudad').after(mensajeObligatorio);
            valido = false;
        }

        if($('#form-huesped #telefono1').val().length < 1){
            $('#form-huesped #telefono1').focus();
            $('#form-huesped #telefono1').after(mensajeObligatorio);
            valido = false;
        }

        $('#panel-contacto table tbody tr.linea-contacto').each(function(){
            var contactNombre = $(this).find('.contac-nombre');
            if(contactNombre.val().length < 1){
                contactNombre.focus();
                contactNombre.after(mensajeObligatorio);
            }

            var contactEmail = $(this).find('.contac-email');
            if(contactEmail.val().length < 1){
                contactEmail.focus();
                contactEmail.after(mensajeObligatorio);
            }

            var contactTelefono = $(this).find('.contac-telefono');
            if(contactTelefono.val().length < 1){
                contactTelefono.focus();
                contactTelefono.after(mensajeObligatorio);
            }
        });

        if($('#camara_comercio').val() != ''){
            var fileName = $('#camara_comercio').get(0).files[0].name;
	        var fileSize = $('#camara_comercio').get(0).files[0].size;
            if(fileSize > 4000000){
                $('#camara_comercio').focus();
                $('#camara_comercio').after(mensajeSizeMax);
                valido = false;
            }else{
		        var ext = fileName.split('.').pop();
                ext = ext.toLowerCase();

                if(ext != 'pdf'){
                    $('#camara_comercio').focus();
                    $('#camara_comercio').after(mensajeExtencion);
                    valido = false;
                }
            }
        }else{
            if(accion != 'editar'){
                $('#camara_comercio').focus();
                $('#camara_comercio').after(mensajeObligatorio);
                valido = false;
            }
        }

        if($('#rut').val() != ''){
            var fileName = $('#rut').get(0).files[0].name;
	        var fileSize = $('#rut').get(0).files[0].size;
            if(fileSize > 4000000){
                $('#rut').focus();
                $('#rut').after(mensajeSizeMax);
                valido = false;
            }else{
		        var ext = fileName.split('.').pop();
                ext = ext.toLowerCase();

                if(ext != 'pdf'){
                    $('#rut').focus();
                    $('#rut').after(mensajeExtencion);
                    valido = false;
                }
            }
        }else{
            if(accion != 'editar'){
                $('#rut').focus();
                $('#rut').after(mensajeObligatorio);
                valido = false;
            }
        }

        if($('#certificacion_bancaria').val() != ''){
            var fileName = $('#certificacion_bancaria').get(0).files[0].name;
	        var fileSize = $('#certificacion_bancaria').get(0).files[0].size;
            if(fileSize > 4000000){
                $('#certificacion_bancaria').focus();
                $('#certificacion_bancaria').after(mensajeSizeMax);
                valido = false;
            }else{
		        var ext = fileName.split('.').pop();
                ext = ext.toLowerCase();

                if(ext != 'pdf'){
                    $('#certificacion_bancaria').focus();
                    $('#certificacion_bancaria').after(mensajeExtencion);
                    valido = false;
                }
            }
        }else{
            if(accion != 'editar'){
                $('#certificacion_bancaria').focus();
                $('#certificacion_bancaria').after(mensajeObligatorio);
                valido = false;
            }
        }

        if($('#orden_compra').val() != ''){
            var fileName = $('#orden_compra').get(0).files[0].name;
	        var fileSize = $('#orden_compra').get(0).files[0].size;
            if(fileSize > 4000000){
                $('#orden_compra').focus();
                $('#orden_compra').after(mensajeSizeMax);
                valido = false;
            }else{
		        var ext = fileName.split('.').pop();
                ext = ext.toLowerCase();

                if(ext != 'pdf'){
                    $('#orden_compra').focus();
                    $('#orden_compra').after(mensajeExtencion);
                    valido = false;
                }
            }
        }else{
            if(accion != 'editar'){
                $('#orden_compra').focus();
                $('#orden_compra').after(mensajeObligatorio);
                valido = false;
            }
        }

        if($('#alcances').val() != ''){
            var fileName = $('#alcances').get(0).files[0].name;
	        var fileSize = $('#alcances').get(0).files[0].size;
            if(fileSize > 4000000){
                $('#alcances').focus();
                $('#alcances').after(mensajeSizeMax);
                valido = false;
            }else{
		        var ext = fileName.split('.').pop();
                ext = ext.toLowerCase();

                if(ext != 'pdf'){
                    $('#alcances').focus();
                    $('#alcances').after(mensajeExtencion);
                    valido = false;
                }
            }
        }else{
            if(accion != 'editar'){
                $('#alcances').focus();
                $('#alcances').after(mensajeObligatorio);
                valido = false;
            }
        }

        if($('#cantidadMaxAgentesSimultaneos').val().length < 1){
            $('#collapseCantidadesAutorizadas').collapse('show');
            $('#cantidadMaxAgentesSimultaneos').focus();
            $('#cantidadMaxAgentesSimultaneos').after(mensajeObligatorio);
            valido = false;
        }

        if($('#cantidadMaximaSupervisores').val().length < 1){
            $('#collapseCantidadesAutorizadas').collapse('show');
            $('#cantidadMaximaSupervisores').focus();
            $('#cantidadMaximaSupervisores').after(mensajeObligatorio);
            valido = false;
        }

        if($('#cantidadMaximaBackoffice').val().length < 1){
            $('#collapseCantidadesAutorizadas').collapse('show');
            $('#cantidadMaximaBackoffice').focus();
            $('#cantidadMaximaBackoffice').after(mensajeObligatorio);
            valido = false;
        }

        if($('#cantidadMaximaCalidad').val().length < 1){
            $('#collapseCantidadesAutorizadas').collapse('show');
            $('#cantidadMaximaCalidad').focus();
            $('#cantidadMaximaCalidad').after(mensajeObligatorio);
            valido = false;
        }

        if($('#horaEntrada').val().length < 1){
            $('#collapsePausas').collapse('show');
            $('#horaEntrada').focus();
            $('#horaEntrada').after(mensajeObligatorio);
            valido = false;
        }

        if($('#horaSalida').val().length < 1){
            $('#collapsePausas').collapse('show');
            $('#horaSalida').focus();
            $('#horaSalida').after(mensajeObligatorio);
            valido = false;
        }

        if($('#emails_notificar_incumplimientos').val().length < 1){
            $('#collapseNotificaciones').collapse('show');
            $('#emails_notificar_incumplimientos').focus();
            $('#emails_notificar_incumplimientos').after(mensajeObligatorio);
            valido = false;
        }

        if($('#notificacion_usuario').val().length < 1){
            $('#collapseNotificaciones').collapse('show');
            $('#notificacion_usuario').focus();
            $('#notificacion_usuario').after(mensajeObligatorio);
            valido = false;
        }

        if($('#notificacion_password').val().length < 1){
            $('#collapseNotificaciones').collapse('show');
            $('#notificacion_password').focus();
            $('#notificacion_password').after(mensajeObligatorio);
            valido = false;
        }

        return valido;
    }

    /**
     * Envia el formulario para crear nuevo huesped
     */
    $('#store').click(function(e){
        e.preventDefault();

        if(validarCampos()){

            $('#myTab a[href="#tab_1"]').tab('show');
            $('#mostrar_loading').addClass('loader');

            $('.error_c').remove();
            $('.error').remove();

            var data = new FormData($('#form-huesped')[0]);
            var token = $("input[name=_token]").val();

            $.ajax({
                url: 'huesped',
                headers: {'X-CSRF-TOKEN':token},
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: data,
                success: function(response){
                    toastr.success(response.message);
                    $("#form-huesped")[0].reset();

                    $('.list-huesped').append(agregarHuesped(response.huesped));
                },
                error: function(response){
                    console.log('log', response);
                    if(response.status === 422){
                        $.each(response.responseJSON.errors, function(key, value){
                            var name = $("[name='"+key+"']");
                            if(key.indexOf(".") != -1){
                                var arr = key.split(".");
                                name = $("[name='"+arr[0]+"[]']:eq("+arr[1]+")");
                            }
                            name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                            name.focus();
                        });

                        $('#collapseCantidadesAutorizadas').collapse('show');
                        $('#collapseNotificaciones').collapse('show');

                        toastr.info('Hubo un problema al validar tus datos');

                        var errores = '<ul class="list-unstyled">';
                        $.each(response.responseJSON.errors, function(key, value){
                            errores += '<li>'+value+'</li>';
                        });
                        errores += '</ul>'

                        Swal.fire({
                            icon: 'info',
                            title: 'Hubo un problema al validar tus datos',
                            html: errores
                        });
                    }else{
                        if (typeof response.message !== 'undefined') {
                            toastr.error(response.message);
                        }else if(typeof response.responseJSON !== 'undefined' && typeof response.responseJSON.message !== 'undefined'){
                            toastr.error(response.responseJSON.message);
                        }else{
                            toastr.error('Se ha presentado un error al guardar los datos');
                        }
                    }
                },
                complete:function(){
                    $('#mostrar_loading').removeClass('loader');
                }
            });
        }else{
            toastr.info('Datos invalidos');
        }

    });

    /**
     * Actualiza el huesped actual
     */
    $('#update').click(function(e){
        e.preventDefault();

        if(validarCampos('editar')){

            $('#myTab a[href="#tab_1"]').tab('show');
            $('#mostrar_loading').addClass('loader');

            $('.error_c').remove();
            $('.error').remove();

            var data = new FormData($('#form-huesped')[0]);
            var token = $("input[name=_token]").val();
            var id = $('input#id').val();

            data.append('_method', 'PUT');

            $.ajax({
                url: 'huesped/'+id,
                headers: {'X-CSRF-TOKEN':token},
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: data,
                success: function(response){
                    toastr.success(response.message);

                    cambiarHuesped(response.huesped);
                },
                error: function(response){
                    console.log('log',response);
                    if(response.status === 422){
                        $.each(response.responseJSON.errors, function(key, value){
                            var name = $("[name='"+key+"']");
                            if(key.indexOf(".") != -1){
                                var arr = key.split(".");
                                name = $("[name='"+arr[0]+"[]']:eq("+arr[1]+")");
                            }
                            name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                            name.focus();
                        });
                        $('#collapseCantidadesAutorizadas').collapse('show');
                        $('#collapseNotificaciones').collapse('show');
                        $('#collapseTwo').collapse('show');

                        toastr.info('Hubo un problema al validar tus datos');

                        var errores = '<ul class="list-unstyled">';
                        $.each(response.responseJSON.errors, function(key, value){
                            errores += '<li>'+value+'</li>';
                        });
                        errores += '</ul>'

                        Swal.fire({
                            icon: 'info',
                            title: 'Hubo un problema al validar tus datos',
                            html: errores
                        });
                    }else{
                        if (typeof response.message !== 'undefined') {
                            toastr.error(response.message);
                        }else if(typeof response.responseJSON !== 'undefined' && typeof response.responseJSON.message !== 'undefined'){
                            toastr.error(response.responseJSON.message);
                        }else{
                            toastr.error('Se ha presentado un error al guardar los datos');
                        }
                    }

                },
                complete:function(){
                    $('#mostrar_loading').removeClass('loader');
                }
            });

        }else{
            toastr.info('Datos invalidos');
        }

    });

    /**
     * Busca un huesped en especifico
     */
    $('#search-huesped').on('keyup', '#texto', function(e){
        e.preventDefault();
        let texto = $(this).val();

        $.ajax({
            type: 'GET',
            url: 'huesped/search',
            data: {'texto': texto},
            success: function (response) {
                let html = "";

                $.each(response.huespedes, function(key, value){
                    html += agregarHuesped(value);
                });

                $('.list-huesped').html(html);
            },
            error: function(response) {
                console.log(response);
            }
        })
    });

    $('#search-huesped').submit(function(e){
        e.preventDefault();
    });

    /**
     * Retorna lista de huespedes en html
     */
    function agregarHuesped(huesped){
        let html = `
            <a href="#" class="list-group-item item-huesped">
                <input type="text" class="huesped_id" style="display: none" value="${huesped.id}">
                <h4 class="list-group-item-heading"><b>${huesped.nombre}</b></h4>
                <p class="list-group-item-text text-muted">Teléfono ${huesped.telefono1}</p>
            </a>
            `;
        return html;
    }

    /**
     * Evento que muestra la informacion del Huesped seleccionado
     */
    $('.list-huesped').on('click', '.item-huesped', function(e){
        e.preventDefault();
        $('#mostrar_loading').addClass('loader');
        $('.error_c').remove();
        $('.error').remove();

        var id_huesped = $(this).children("input.huesped_id").val();

        $.ajax({
            type: 'GET',
            url: 'huesped/'+id_huesped,
            success: function (response) {

                $('h3.title').text(response.huesped.nombre);

                $('#form-huesped')[0].reset();
                $('#update').show();
                $('#store').hide();
                $('.data-usuari').hide();

                mostrarCollapse();
                cambiarHuesped(response.huesped, response.troncales);

                getCuentasCorreo(response.huesped.id);
                getProveedoresSms(response.huesped.id);
                listarFestivos(response.huesped.id);
                // listarPausas(response.huesped.id, response.huesped.pausa_por_defecto_1, response.huesped.pausa_por_defecto_2, response.huesped.pausa_por_defecto_3);

            },
            error: function(response) {
                if (typeof response.message !== 'undefined') {
                    toastr.error(response.message);
                }else if(typeof response.responseJSON.message !== 'undefined'){
                    toastr.error(response.responseJSON.message);
                }else{
                    toastr.error('Se presentó un error al obtener los datos');
                }
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })
    });

    /**
     * Sobrescribe los datos del huésped en #form-huesped
     */
    function cambiarHuesped(huespedData, troncales = null){
        let h = huespedData;
        let huesped = $('#form-huesped');

        huesped.find('input#id').val(h.id);
        huesped.find('input#nombre').val(h.nombre);
        huesped.find('input#razon_social').val(h.razon_social);
        huesped.find('input#nit').val(h.nit);
        huesped.find('input#direccion').val(h.direccion);
        huesped.find('input#telefono1').val(h.telefono1);
        huesped.find('input#telefono2').val(h.telefono2);
        huesped.find('input#telefono3').val(h.telefono3);
        huesped.find('input#fotoUsuarioObligatoria').prop('checked', (h.foto_usuario_obligatoria == 1) ? true : false);

        if(h.doble_factor_autenticacion == '1'){
            $('#dobleFactorAuth').prop('checked', true);
        }

        $('#passLongitudRequerida').prop('checked', (h.pass_longitud_requerida == 1) ? true : false);
        $('#passMayusculaRequerida').prop('checked', (h.pass_mayuscula_requerida == 1) ? true : false);
        $('#passNumeroRequerido').prop('checked', (h.pass_numero_requerido == 1) ? true : false);
        $('#passSimboloRequerido').prop('checked', (h.pass_simbolo_requerido == 1) ? true : false);
        $('#passCambioObligatorioRequerido').prop('checked', (h.pass_cambio_login_requerido == 1) ? true : false);
        $('#passHistoricoRequerido').prop('checked', (h.pass_historico_requerido == 1) ? true : false);
        $('#passCambioPeriodicoRequerido').prop('checked', (h.pass_cambio_periodico_requerido == 1) ? true : false).trigger('change');
        $('#passDiasCambioPeriodico').val(h.pass_dias_cambio_periodico);


        $('#collapseNotificaciones input#notificar_pausas').prop('checked', (h.notificar_pausas == 1) ? true : false);
        $('#collapseNotificaciones input#notificar_sesiones').prop('checked', (h.notificar_sesiones == 1) ? true : false);
        $('#collapseNotificaciones input#notificar_incumplimientos').prop('checked', (h.notificar_incumplimientos == 1) ? true : false);
        $('#collapseNotificaciones input#emails_notificar_pausas').val(h.emails_notificar_pausas);
        $('#collapseNotificaciones input#emails_notificar_sesiones').val(h.emails_notificar_sesiones);
        $('#collapseNotificaciones input#emails_notificar_incumplimientos').val(h.emails_notificar_incumplimientos);

        $('#tipoPausa').val(h.tipo_gestion_pausa);
        $('#mallaTurnoRequerida').prop('checked', (h.malla_turno_requerida == 1) ? true : false);
        $('#mallaTurnoHorarioDefecto').prop('checked', (h.malla_turno_horario_por_defecto == 1) ? true : false);
        $('#horaEntrada').val(h.hora_entrada_por_defecto);
        $('#horaSalida').val(h.hora_salida_por_defecto);
        if(h.proyecto){
            $('#collapseCantidadesAutorizadas input#cantidadMaxAgentesSimultaneos').val(h.proyecto.cantidad_max_agentes_simultaneos);
        }
        $('#collapseCantidadesAutorizadas input#cantidadMaximaSupervisores').val(h.cantidad_max_supervisores);
        $('#collapseCantidadesAutorizadas input#cantidadMaximaBackoffice').val(h.cantidad_max_bo);
        $('#collapseCantidadesAutorizadas input#cantidadMaximaCalidad').val(h.cantidad_max_calidad);



        if(h.mail_notificacion){
            $('#collapseNotificaciones input#notificacion_servidor_smtp').val(h.mail_notificacion.servidor_smtp);
            $('#collapseNotificaciones input#notificacion_dominio').val(h.mail_notificacion.dominio);
            $('#collapseNotificaciones input#notificacion_puerto').val(h.mail_notificacion.puerto);
            $('#collapseNotificaciones input#notificacion_usuario').val(h.mail_notificacion.usuario);
            $('#collapseNotificaciones input#notificacion_password').val(h.mail_notificacion.password);
            $('#collapseNotificaciones input#notificacion_auth').prop('checked', (h.mail_notificacion.auth == 1) ? true : false);
            $('#collapseNotificaciones input#notificacion_ttls').prop('checked', (h.mail_notificacion.ttls == 1) ? true : false);
            // Agrega el id de mail_notificacion al data-id del boton de test
            $('#btn-test-notificacion-cuenta').data('id', h.mail_notificacion.id);
        }
        $('#btn-test-notificacion-cuenta').show();

        $('#collapseMensajeActual #mensajeActual').val(h.mensaje);

        $.get('pais/ciudad/'+h.id_pais_ciudad+'', function(response){

            huesped.find('select#pais').val(response.pais);

            $.when(getAllCiudades(response.pais)).then(function(){
                huesped.find('select#ciudad').val(h.id_pais_ciudad);
            });
        });

        let html_contacto = '';

        $.each(h.contactos, function(key, value){
            html_contacto += generateContacto(value);
        });
        $('#panel-contacto table tbody').html(html_contacto);

        let huesped_archivos = $('#panel-archivos');

        huesped_archivos.find('a.camara_comercio').attr('href', 'huesped/file/'+h.id+'/camara_comercio.pdf');
        huesped_archivos.find('a.rut').attr('href', 'huesped/file/'+h.id+'/rut.pdf');
        huesped_archivos.find('a.certificacion_bancaria').attr('href', 'huesped/file/'+h.id+'/certificacion_bancaria.pdf');
        huesped_archivos.find('a.orden_compra').attr('href', 'huesped/file/'+h.id+'/orden_compra.pdf');
        huesped_archivos.find('a.alcances').attr('href', 'huesped/file/'+h.id+'/alcances.pdf');

        // muestra las troncales
        var html_troncales = '';

        if(troncales !== null){
            if(troncales.length > 0){
                $.each(troncales, function(key, value){
                    html_troncales += getHtmlTroncal(value);
                });
            }else{
                html_troncales =`
                    <tr>
                        <td colspan="5"><h4>El huésped aún no ha creado troncales</h4></td>
                    </tr>
                `;
            }
            $('#secTroncales table tbody').html(html_troncales);
        }

        // Obtiene el estado de las troncales
        getEstadosTroncales(h.id);

        getUsuarios(h.id);

        listarPatrones(h.id);

        listarServices(h.id);

        getChannelsW(h.id);

        obtenerCanalesInstagram(h.id);

        cargarListaPlantillas(h.id);

        getChannelsF(h.id);
    }

    /**
     * Muestra el formulario para crear huesped
     */
    $('#create').on('click', function(){

        $('#form-huesped')[0].reset();
        $('.error_c').remove();
        $('.error').remove();

        $('h3.title').text('Registrar huésped');

        $('#panel-contacto table tbody').html(generateContacto());

        $('#store').show();
        $('#update').hide();
        $('.data-usuari').show();

        $('#panel-archivos a.doc').attr('href', '#');

        // Agrega el id por defecto de mail_notificacion al data-id del boton de test
        $('#btn-test-notificacion-cuenta').data('id', -1);
        $('#btn-test-notificacion-cuenta').hide();

        ocultarCollapse();

    });

    /**
     * Agrega nuevo campo para registrar nuevo contacto
     */
    $("#add-contacto").click(function(){
        $('#panel-contacto table tbody').append(generateContacto());
    });

    $("#panel-contacto").on('click', '.eliminarContacto', function(){
        $(this).closest('tr').remove();
    });

    /**
     * Crear por defecto objeto contacto vacio
     */
    function Contacto(){
        this.id = -1;
        this.nombre = '';
        this.email = '';
        this.tipo = '';
        this.telefono1 = '';
        this.telefono2 = '';
    }

    /**
     * Genera Codigo html para añadir nuevo contacto
     */
    function generateContacto(contacto = new Contacto){
        let html = `
        <tr class="linea-contacto">
            <input type="hidden" name="contacto_id[]" value="${contacto.id}" form="form-huesped">
            <td>
                <input type="text" class="form-control contac-nombre" name="contacto_nombre[]" placeholder="Ingresa el nombre" value="${contacto.nombre}" form="form-huesped">
            </td>
            <td>
                <input type="email" class="form-control contac-email" name="contacto_email[]" placeholder="Ingresa el email" value="${contacto.email}" form="form-huesped">
            </td>
            <td>
                <select name="contacto_tipo[]" class="form-control" form="form-huesped">
                    <option value="T" ${contacto.tipo == "T" ? 'selected' : ''}>Tecnico</option>
                    <option value="P" ${contacto.tipo == "P" ? 'selected' : ''}>Pagos</option>
                    <option value="F" ${contacto.tipo == "F" ? 'selected' : ''}>Funcional</option>
                </select>
            </td>
            <td>
                <input type="tel" class="form-control contac-telefono" name="contacto_telefono1[]" placeholder="Ingresa el numero de teléfono" value="${contacto.telefono1}" form="form-huesped">
            </td>
            <td>
                <input type="tel" class="form-control" name="contacto_telefono2[]" placeholder="Ingresa el numero de teléfono" value="${contacto.telefono2}" form="form-huesped">
            </td>
            <td>
                <button type="button" class="btn btn-danger eliminarContacto"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
        `;

        return html;
    }

    /**
     * Devuelve todas las ciudades del pais seleccionado
    */
   $('#form-huesped').on('change', '#pais', function(event){
        getAllCiudades(event.target.value);
    });

    /**
     * Busca todos las ciudades de un pais
     */
    function getAllCiudades(pais){
        var d1 = $.Deferred();
        $.get('pais/ciudades/'+pais+'', function(response, ciudad){

            $('#ciudad').empty();
            $('#ciudad').append("<option value=''>Seleccionar</option>");
            for(i=0; i<response.length; i++){
                $('#ciudad').append("<option value='"+response[i].id+"'>"+response[i].ciudad+"</option>");
            }
            d1.resolve();
        });
        return d1.promise();
    }

    /** Ejecuta el test de cuenta de notificaciones smtp */
    $('#btn-test-notificacion-cuenta').on('click', function(){
        var idCuentaNotificacion = $(this).data('id');
        $('#modal-prueba-notificacionCuenta #notificacionLog').html('');

        $('#modal-prueba-notificacionCuenta .estado-test-notificacion').hide();

        if(idCuentaNotificacion == -1){
            alert('No se puede realizar la prueba hasta no haber registrado el huésped');
        }else{

            $('#modal-prueba-notificacionCuenta').modal('show');

            $('#mostrar_loading').addClass('loader');

            var token = $("input[name=_token]").val();
            var id_huesped = $('input#id').val();
            var f = new Date();
            var dia = f.getDate() +'-'+ (f.getMonth() + 1) +'-'+ f.getFullYear();
            var hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();

            $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia +' '+hora+' iniciado &#13;&#10;');

            $.ajax({
                url: 'huesped/prueba-email-notificacion-smtp/'+id_huesped,
                headers: {'X-CSRF-TOKEN':token},
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response){

                    $('#modal-prueba-notificacionCuenta .estado-test-notificacion').text('Prueba fallida').removeClass('text-success').addClass('text-danger').show();
                    if(response.json){

                        if(response.json.strEstado_t == 'ok'){
                            $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia +' '+hora+' Estado: '+ response.json.strEstado_t +' &#13;&#10;');
                            $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia +' '+hora+' Se ha ejecutado la prueba exitosamente &#13;&#10;');
                            $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia +' '+hora+' Prueba finalizada &#13;&#10;');

                            $('#modal-prueba-notificacionCuenta .estado-test-notificacion').text('Prueba exitosa').removeClass('text-danger').addClass('text-success');
                        }else{
                            hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();
                            $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia +' '+hora+' Estado: '+response.json.strEstado_t+' &#13;&#10;');
                            $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia +' '+hora+' Hubo un error al ejecutar la prueba de entrada de correo &#13;&#10;');
                        }

                    }else{
                        $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia +' '+hora+' Ha habido un problema al comunicarse con la api &#13;&#10;');
                    }

                },
                error: function(response){
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al consumir la api.');
                    }
                },
                complete:function(){
                    $('#mostrar_loading').removeClass('loader');
                }
            });
        }
    });


    /**--------------Seccion politicas de contraseñas------------- */


    $('#passCambioPeriodicoRequerido').on('change',function(){
        if($(this).is(':checked')){
            $('#passDiasCambioPeriodico').attr('disabled',false);
        }else{
            $('#passDiasCambioPeriodico').attr('disabled',true);
        }
    });



    /**--------------Seccion pausas------------- */
    function listarPausas(id_huesped, pausa1, pausa2, pausa3){
        $.ajax({
            type: 'GET',
            url: 'huesped/listar-pausas/'+id_huesped,
            success: function (response) {

                let html11 = '';
                if(response.listaPausas){
                    $.each(response.listaPausas, function(key, value){
                        html11 += agregarPausas(value);
                    });
                }else{
                    html11 = `
                    <tr>
                        <td colspan="8"><h4>El huésped aún no tiene pausas asignadas</h4></td>
                    </tr>
                    `;
                }

                $('#secPausas table tbody').html(html11);
                $('input[name="hora_inicial[]"]').timepicker({
                    timeFormat: 'HH:mm:ss',
                });
                $('input[name="hora_final[]"]').timepicker({
                    timeFormat: 'HH:mm:ss',
                });
            },
            error: function(response) {
                 toastr.error("No se pudo obtener la lista de pausas");
            },
            complete: function(){
                $('#form-pausas table tbody tr#'+pausa1).find('input.nombre_pausa').prop('readonly','true');
                $('#form-pausas table tbody tr#'+pausa1).find('select option:not(:selected)').prop('disabled','true');
                $('#form-pausas table tbody tr#'+pausa2).find('input.nombre_pausa').prop('readonly','true');
                $('#form-pausas table tbody tr#'+pausa2).find('select option:not(:selected)').prop('disabled','true');
                $('#form-pausas table tbody tr#'+pausa3).find('input.nombre_pausa').prop('readonly','true');
                $('#form-pausas table tbody tr#'+pausa3).find('select option:not(:selected)').prop('disabled','true');
            }
        })
    }

    /**Esta funcion retorna las pausas que tiene el huesped */
    function agregarPausas(data){
        let html = `
        <tr id="${data.id}">
            <input type="hidden" name="id_pausa[]" value="${data.id}">
            <td><input type="text" style="width:200px;" name="nombre_pausa[]" class="form-control nombre_pausa" value="${data.tipo}"></td>
            <td>
                <select name="clasificacion[]" class="form-control">
                    <option value="">Seleccionar</option>
                    <option value="0" ${data.descanso == "0" ? 'selected' : ''}>Laboral</option>
                    <option value="1" ${data.descanso == "1" ? 'selected' : ''}>Descanso</option>
                </select>
            </td>
            <td>
                <select name="tipo[]" class="form-control tipo-pausa">
                    <option value="">Seleccionar</option>
                    <option value="0" ${data.tipo_pausa == "0" ? 'selected' : ''}>Pausas sin horario</option>
                    <option value="1" ${data.tipo_pausa == "1" ? 'selected' : ''}>Otras pausas con horario</option>
                </select>
            </td>
            <td><input type="text" name="hora_inicial[]" class="form-control" value="${data.hora_inicial_por_defecto ? data.hora_inicial_por_defecto : ''}" ${data.tipo_pausa == "0" ? 'readonly' : ''}></td>
            <td><input type="text" name="hora_final[]" class="form-control" value="${data.hora_final_por_defecto ? data.hora_final_por_defecto : ''}" ${data.tipo_pausa == "0" ? 'readonly' : ''}></td>
            <td><input type="number" name="duracion_maxima[]" max="10" class="form-control" value="${data.duracion_maxima ? data.duracion_maxima : ''}" ${data.tipo_pausa == "1" ? 'readonly' : ''}></td>
            <td><input type="number" name="cantidad_maxima[]" max="3" class="form-control" value="${data.cantidad_maxima_evento_dia ? data.cantidad_maxima_evento_dia : ''}" ${data.tipo_pausa == "1" ? 'readonly' : ''}></td>
            <td>
                <button type="button" data-id="${data.id}" class="btn btn-danger btn-eliminar-pausa"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
        `;
        return html;
    }

    /** Agregar nueva pausa a la tabla */
    $('#btn-agregar-pausa').on('click', function(){
        let html = `
        <tr>
            <input type="hidden" name="id_pausa[]" value="-1">
            <td><input type="text" name="nombre_pausa[]" class="form-control"></td>
            <td>
                <select name="clasificacion[]" class="form-control">
                    <option value="">Seleccionar</option>
                    <option value="0">Laboral</option>
                    <option value="1">Descanso</option>
                </select>
            </td>
            <td>
                <select name="tipo[]" class="form-control tipo-pausa">
                    <option value="">Seleccionar</option>
                    <option value="0">Pausas sin horario</option>
                    <option value="1">Otras pausas con horario</option>
                </select>
            </td>
            <td><input type="text" name="hora_inicial[]" class="form-control"></td>
            <td><input type="text" name="hora_final[]" class="form-control"></td>
            <td><input type="number" name="duracion_maxima[]" max="10" class="form-control"></td>
            <td><input type="number" name="cantidad_maxima[]" max="3" class="form-control"></td>
            <td>
                <button type="button" data-id="-1" class="btn btn-danger btn-eliminar-pausa"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
        `;

        $('#secPausas table tbody').append(html);
        $('input[name="hora_inicial[]"]').timepicker({
            timeFormat: 'HH:mm:ss',
        });
        $('input[name="hora_final[]"]').timepicker({
            timeFormat: 'HH:mm:ss',
        });
    });

    /** cambiar si es readonly dependiendo del tipo de pausa */
    $('#secPausas table').on('change', '.tipo-pausa', function(){
        var idTipoPausa = $(this).val();
        var trPadre = $(this).closest('tr');

        if(idTipoPausa == 0){
            trPadre.find("[name='hora_inicial[]']").prop('readonly','true');
            trPadre.find("[name='hora_final[]']").prop('readonly','true');
            trPadre.find("[name='duracion_maxima[]']").prop('readonly','');
            trPadre.find("[name='cantidad_maxima[]']").prop('readonly','');
        }else if(idTipoPausa == 1){
            trPadre.find("[name='hora_inicial[]']").prop('readonly','');
            trPadre.find("[name='hora_final[]']").prop('readonly','');
            trPadre.find("[name='duracion_maxima[]']").prop('readonly','true');
            trPadre.find("[name='cantidad_maxima[]']").prop('readonly','true');
        }

    });

    /** Eliminar pausa */
    $('#secPausas table').on('click', '.btn-eliminar-pausa', function(){
        $(this).closest('tr').remove();
    });

    /** Guardar Cambios en la tabla de pausas */
    $('#btn-guardar-cambios-pausas').on('click', function(e){
        e.preventDefault();

        $('#mostrar_loading').addClass('loader');
        $('#form-pausas .error_c').remove();

        var data = new FormData($('#form-pausas')[0]);
        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();

        $.ajax({
            url: 'huesped/guardar-pausas/'+id_huesped,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function(response){
                toastr.success(response.message);
                listarPausas(id_huesped, 0, 0, 0);
            },
            error: function(response){
                if(response.status === 422){
                    $.each(response.responseJSON.errors, function(key, value){
                        var name = $("#form-pausas [name='"+key+"']");
                        if(key.indexOf(".") != -1){
                            var arr = key.split(".");
                            name = $("#form-pausas [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                        }
                        name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                        name.focus();
                    });
                    toastr.info('Hubo un problema al validar tus datos');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al guardar los datos.');
                    }
                }
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });

    });


    /*------------- Seccion Festivos -------------*/

    /** Listar fechas */
    function listarFestivos(id_huesped){

        $.ajax({
            type: 'GET',
            url: 'huesped/listar-festivos/'+id_huesped,
            success: function (response) {

                let html11 = '';
                if(response.listaFestivos && response.listaFestivos.festivos.length > 0){
                    $('#collapseFestivos .text-festivo').text(response.listaFestivos.nombre);
                    $.each(response.listaFestivos.festivos, function(key, value){
                        html11 += agregarFestivos(value, key);
                    });
                }else{
                    $('#collapseFestivos .text-festivo').text('');
                    html11 = `
                    <tr>
                        <td colspan="5"><h4>El huésped aún no tiene asignada fechas de festivos</h4></td>
                    </tr>
                    `;
                }

                $('#collapseFestivos table tbody').html(html11);

                // Agrega las propiedades de .datepicker al campo
                inicializarDatePicker();
            },
            error: function(response) {
                 toastr.error("No se pudo obtener la lista de festivos");
            }
        })
    }

    function agregarFestivos(data, key){
        let html = `
        <tr>
            <input type="hidden" name="festivo_id[]" value="${data.id}">
            <td>${key+1}</td>
            <td><input type="text" name="nombre_festivo[]" class="form-control" value="${data.nombre}"></td>
            <td><input type="text" name="fecha_festivo[]" class="form-control datepicker" value="${data.fecha}"></td>
            <td>
                <button type="button" data-id="${data.id}" class="btn btn-danger btn-eliminar-fecha"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
        `;
        return html;
    }

    /** Agregar nueva fecha */
    $('#btn-agregar-fecha').on('click', function(){
        let html = `
        <tr>
            <input type="hidden" name="festivo_id[]" value="-1">
            <td></td>
            <td><input type="text" name="nombre_festivo[]" class="form-control"></td>
            <td><input type="text" name="fecha_festivo[]" class="form-control datepicker"></td>
            <td>
                <button type="button" data-id="-1" class="btn btn-danger btn-eliminar-fecha"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
        `;

        $('#collapseFestivos table tbody').append(html);
        inicializarDatePicker();
    });

    /** Eliminar fecha */
    $('#collapseFestivos table').on('click', '.btn-eliminar-fecha', function(){

        // let id_festivo = $(this).data('id');

        // if(id_festivo < 0){
        //     $(this).closest('tr').remove();
        // }
        $(this).closest('tr').remove();

    });

    /** Guardar Cambios fecha */
    $('#btn-guardar-cambios-fecha').on('click', function(e){
        e.preventDefault();

        $('#mostrar_loading').addClass('loader');
        $('#form-festivos .error_c').remove();

        var data = new FormData($('#form-festivos')[0]);
        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();

        $.ajax({
            url: 'huesped/guardar-festivos/'+id_huesped,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function(response){
                toastr.success(response.message);
                listarFestivos(id_huesped);
            },
            error: function(response){
                if(response.status === 422){
                    $.each(response.responseJSON.errors, function(key, value){
                        var name = $("#form-festivos [name='"+key+"']");
                        if(key.indexOf(".") != -1){
                            var arr = key.split(".");
                            name = $("#form-festivos [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                        }
                        name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                        name.focus();
                    });
                    toastr.info('Hubo un problema al validar tus datos');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al guardar los datos.');
                    }
                }
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });

    });

    $('#btn-generar-festivo').on('click', function(e){
        e.preventDefault();

        $('#mostrar_loading').addClass('loader');

        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();

        $.ajax({
            url: 'huesped/generar-festivos/'+id_huesped,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: {id_huesped: id_huesped},
            success: function(response){
                toastr.success(response.message);
                listarFestivos(id_huesped);
            },
            error: function(response){

                if (typeof response.message !== 'undefined') {
                    toastr.error(response.message);
                }else if(typeof response.responseJSON.message !== 'undefined'){
                    toastr.error(response.responseJSON.message);
                }else{
                    toastr.error('Se presentó un error al obtener los datos');
                }

            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });
    });

    function inicializarDatePicker(){
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    }

    /*------------- Proveedores de sms -------------*/

    /** Evento button que muestra la modal para crear un nuevo registro */
    $('#btn-crear-proveedor-sms').on('click', function(){
        $("#form-proveedor-sms")[0].reset();
        $('#modal-proveedorSms .modal-title').text("Registrar proveedor SMS");
        $('#form-proveedor-sms .error_c').remove();
        $("#form-proveedor-sms #accion").val('registrar');
        $('#modal-proveedorSms').modal('show');
    });

    $("#form-proveedor-sms #proveedor").on('change', function(){

        let proveedor = $("#form-proveedor-sms #proveedor").val();

        $("#form-proveedor-sms #url_api").parent().show();
        $("#form-proveedor-sms #url_api_ssl").parent().show();
        $("#form-proveedor-sms #api_key").parent().show();

        if(proveedor == 'dyinfobip'){
            $("#form-proveedor-sms #url_api").parent().hide();
            $("#form-proveedor-sms #url_api_ssl").parent().hide();
            $("#form-proveedor-sms #api_key").parent().hide();
        }
    });

    /**
     * Evento button que muestra el modal para editar
     */
    $('#secProveedoresSms table').on('click', '.btn-editar-proveedor-sms', function(){

        $("#form-proveedor-sms")[0].reset();
        $('#modal-proveedorSms .modal-title').text("Editar proveedor SMS");
        $("#form-proveedor-sms #accion").val('actualizar');
        $('#modal-proveedorSms').modal('show');

        $('#form-proveedor-sms .error_c').remove();
        $('#mostrar_loading').addClass('loader');

        var id = $(this).data('id');

        $.ajax({
            type: 'GET',
            url: 'huesped/proveedor-sms/'+id,
            success: function (response) {
                editarProveedorSms(response.proveedorSms);
            },
            error: function(response) {
                 toastr.error(response.message);
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })
    });

    /** Guarda el registro del formulario store y update */
    $('#modal-proveedorSms #btn-guardar-proveedor-sms').on('click', function(e){
        e.preventDefault();
        $('#mostrar_loading').addClass('loader');
        $('#form-proveedor-sms .error_c').remove();
        var data = new FormData($('#form-proveedor-sms')[0]);
        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();
        var accion = $('#form-proveedor-sms #accion').val();

        switch (accion) {
            case 'registrar':
                $.ajax({
                    url: 'huesped/crear-proveedor-sms/'+id_huesped,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){

                        toastr.success(response.message);
                        $("#form-proveedor-sms")[0].reset();
                        getProveedoresSms(id_huesped);
                        $('#modal-proveedorSms').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-proveedor-sms [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-proveedor-sms [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                                toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                        }
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;

            case 'actualizar':
                data.append('_method', 'PUT');
                var id = $('#form-proveedor-sms #id_proveedorSms').val();
                $.ajax({
                    url: 'huesped/actualizar-proveedor-sms/'+id,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){
                        toastr.success(response.message);
                        getProveedoresSms(response.proveedorSms.id_huesped);
                        $('#modal-proveedorSms').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-proveedor-sms [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-proveedor-sms [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                                toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                        }
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;
            default:
                break;
        }
    });

    /** Elimina el registro seleccionado */
    $('#secProveedoresSms table').on('click', '.btn-eliminar-proveedor-sms', function(){
        var id = $(this).data('id');
        var id_huesped = $('input#id').val();
        var token = $("input[name=_token]").val();

        Swal.fire({
            title: 'Deseas eliminar este proveedor de SMS?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                $('#mostrar_loading').addClass('loader');
                $.ajax({
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'DELETE',
                    url: 'huesped/eliminar-proveedor-sms/'+id,
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        getProveedoresSms(id_huesped);
                        Swal.fire(
                            'Eliminado!',
                            'Su registro ha sido eliminado.',
                            'success'
                        )
                    },
                    error: function(response) {
                         toastr.error(response.responseJSON.message);
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                })

            }
        })
    });

    /** Inserta los datos de proveedor Sms al formulario de editar */
    function editarProveedorSms(data){
        let cuentaCorreo = $('#form-proveedor-sms');

        cuentaCorreo.find('input#id_proveedorSms').val(data.id);
        cuentaCorreo.find('input#nombre').val(data.nombre);
        cuentaCorreo.find('select#proveedor').val(data.proveedor);
        cuentaCorreo.find('input#url_api').val(data.url_api);
        cuentaCorreo.find('input#url_api_ssl').val(data.url_api_ssl);
        cuentaCorreo.find('input#api_key').val(data.api_key);
        cuentaCorreo.find('input#longitud_maxima').val(data.longitud_maxima_sms);

    }

    /**Busca todos las cuentas de correo perteneciente a un huesped */
    function getProveedoresSms(id){

        $.ajax({
            type: 'GET',
            url: 'huesped/listar-proveedores-sms/'+id,
            success: function (response) {
                let html11 = '';
                if(response.proveedoresSms.length > 0){
                    $.each(response.proveedoresSms, function(key, value){
                        html11 += agregarProveedorSms(value, key);
                    });
                }else{
                    html11 = `
                    <tr>
                        <td colspan="5"><h4>El huésped aún no tiene proveedores sms</h4></td>
                    </tr>
                    `;
                }

                $('#secProveedoresSms table tbody').html(html11);
                // Desabilito los botones de los registros que no se pueden tocar
                $(".desabilitar-negativo").attr("disabled", true);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener los proveedores SMS");
            }
        })
    };

    /** Agrega una fila a la tabla de proveedores de sms */
    function agregarProveedorSms(data, key){

        let miClase = "";

        if(data.id_huesped == -1){
            miClase = "desabilitar-negativo"
        }

        let html = `
        <tr>
            <td>${key+1}</td>
            <td>${data.nombre}</td>
            <td>${data.proveedor}</td>
            <td>
                <button data-id="${data.id}" class="btn btn-primary btn-sm btn-editar-proveedor-sms ${miClase}" title="Editar">
                    <i class="fa fa-edit"></i>
                </button>
                <button data-id="${data.id}" class="btn btn-danger btn-sm btn-eliminar-proveedor-sms ${miClase}" title="Eliminar">
                    <i class="fa fa-trash"></i>
                </button>
                <button class="btn btn-info btn-sm btn-prueba-sms" data-id="${data.id}" data-huesped="${data.id_huesped}" data-nombre="${data.nombre}">Prueba</button>
            </td>
        </tr>
        `;

        return html;
    }

    /** Abre la modal para la prueba de sms */
    $('#secProveedoresSms table').on('click', '.btn-prueba-sms', function(){

        $("#form-prueba-proveedor-sms")[0].reset();
        $('#modal-prueba-proveedorSms').modal('show');

        $('#modal-prueba-proveedorSms .estado-test-sms').hide();

        $('#form-prueba-proveedor-sms .error_c').remove();

        var id = $(this).data('id');
        var id_huesped = $(this).data('huesped');
        var nombre = $(this).data('nombre');

        $('#form-prueba-proveedor-sms #id_proveedorSms').val(id);
        $('#form-prueba-proveedor-sms #id_huesped').val(id_huesped);
        $('#form-prueba-proveedor-sms .nombre-proveedor-sms').text(nombre);

        $('#modal-prueba-proveedorSms #consoleLog').hide();
    });

    /** Ejecuta la prueba de sms */
    $('#modal-prueba-proveedorSms #btn-probar-proveedor-sms').on('click', function(){
        $('#mostrar_loading').addClass('loader');
        $('#form-prueba-proveedor-sms .error_c').remove();
        var data = new FormData($('#form-prueba-proveedor-sms')[0]);
        var token = $("input[name=_token]").val();

        $('#modal-prueba-proveedorSms #consoleLog').html('');
        $('#modal-prueba-proveedorSms #consoleLog').show();

        var f = new Date();

        var dia = f.getDate() +'-'+ (f.getMonth() + 1) +'-'+ f.getFullYear();
        var hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();

        $('#modal-prueba-proveedorSms #consoleLog').append('Test de proveedor de SMS &#13;&#10;');
        $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Test iniciado &#13;&#10;');

        $.ajax({
            url: 'huesped/prueba-proveedor-sms',
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function(response){

                $('#modal-prueba-proveedorSms .estado-test-sms').text('Prueba fallida').removeClass('text-success').addClass('text-danger').show();

                if(response.json){
                    if(response.json.strEstado_t == 'ok'){
                        hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();
                        $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Estado '+response.json.strEstado_t+' &#13;&#10;');
                        $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Mensaje '+response.message+' &#13;&#10;');
                        $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Test finalizado &#13;&#10;');

                        $('#modal-prueba-proveedorSms .estado-test-sms').text('Prueba exitosa').removeClass('text-danger').addClass('text-success');
                    }else{
                        hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();
                        $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Estado '+response.json.strEstado_t+' &#13;&#10;');
                        $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Mensaje '+response.json.strMensaje_t+' &#13;&#10;');
                        $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Test finalizado &#13;&#10;');
                    }
                }else{
                    hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();
                    $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Ha habido un problema al comunicarse con la api &#13;&#10;');
                    $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Test finalizado &#13;&#10;');
                }

            },
            error: function(response){
                if(response.status === 422){
                    $.each(response.responseJSON.errors, function(key, value){
                        var name = $("#form-prueba-proveedor-sms [name='"+key+"']");
                        if(key.indexOf(".") != -1){
                            var arr = key.split(".");
                            name = $("#form-prueba-proveedor-sms [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                        }
                        name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                        name.focus();
                    });
                    toastr.info('Hubo un problema al validar tus datos');
                    hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();
                    $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' No se ha podido enviar la información  &#13;&#10;');
                    $('#modal-prueba-proveedorSms #consoleLog').append(dia +' '+hora+' Test finalizado &#13;&#10;');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al enviar los datos.');
                    }
                }
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });
    });

    /*------------- Cuentas de Correo -------------*/

    $("#abrirAyudaEmail").on('click', function(){
        $("#modal-ayuda-email").modal('show');
    });

    /** Evento button que muestra la modal para crear un nuevo registro */
    $('#btn-crear-cuenta-correo').on('click', function(){

        $("#form-cuenta-correo")[0].reset();
        $('#modal-cuentaCorreo .modal-title').text("Registrar cuenta de correo");
        $("#form-cuenta-correo #accion").val('registrar');
        $('#modal-cuentaCorreo').modal('show');

        $("#mensaje_google").hide();
    });

    /**
     * Evento button que muestra el modal para editar
     */
    $('#secCuentasCorreo table').on('click', '.btn-editar-cuenta-correo', function(){

        $("#form-cuenta-correo")[0].reset();
        $('#modal-cuentaCorreo .modal-title').text("Editar cuenta de correo");
        $("#form-cuenta-correo #accion").val('actualizar');
        $('#modal-cuentaCorreo').modal('show');

        $('#form-cuenta-correo .error_c').remove();
        $('#mostrar_loading').addClass('loader');

        var id = $(this).data('id');

        $.ajax({
            type: 'GET',
            url: 'huesped/cuenta-correo/'+id,
            success: function (response) {
                editarCuentaCorreo(response.cuentaCorreo);
            },
            error: function(response) {
                 toastr.error(response.message);
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })
    });

    /** Llenado de datos proveedor automaticamente */
    $( "#form-cuenta-correo #proveedor" ).on('change', function() {
        let opcionProveedor = $(this).val();
        let cuentaCorreo = $('#form-cuenta-correo');
        let accion = $("#form-cuenta-correo #accion").val();

        if(opcionProveedor != 'infobip'){
            $("#s-entrante").show();
        }else{
            $("#s-entrante").hide();
        }

        $("#mensaje_google").hide();

        if(opcionProveedor == 'gmail'){
            $("#mensaje_google").show();
        }

        if(accion == 'registrar'){
            switch (opcionProveedor) {
                case 'gmail':
                    cuentaCorreo.find('input#servidor_saliente_direccion').val('smtp.gmail.com');
                    cuentaCorreo.find('select#servidor_saliente_tipo').val(1);
                    cuentaCorreo.find('input#servidor_saliente_usar_auth').prop('checked', true);
                    cuentaCorreo.find('input#servidor_saliente_usar_start_ttls').prop('checked',true);
                    cuentaCorreo.find('input#servidor_saliente_usar_ssl').prop('checked', false);
                    cuentaCorreo.find('input#servidor_saliente_puerto').val(587);
                    cuentaCorreo.find('input#servidor_entrante_direccion').val('imap.gmail.com');
                    cuentaCorreo.find('select#servidor_entrante_tipo').val(3);
                    cuentaCorreo.find('input#servidor_entrante_usar_auth').prop('checked', true);
                    cuentaCorreo.find('input#servidor_entrante_usar_start_ttls').prop('checked', true);
                    cuentaCorreo.find('input#servidor_entrante_usar_ssl').prop('checked', false);
                    cuentaCorreo.find('input#servidor_entrante_puerto').val(993);
                    break;

                case 'microsoft':
                    cuentaCorreo.find('input#servidor_saliente_direccion').val('smtp.office365.com');
                    cuentaCorreo.find('select#servidor_saliente_tipo').val(1);
                    cuentaCorreo.find('input#servidor_saliente_usar_auth').prop('checked', true);
                    cuentaCorreo.find('input#servidor_saliente_usar_start_ttls').prop('checked', true);
                    cuentaCorreo.find('input#servidor_saliente_usar_ssl').prop('checked', false);
                    cuentaCorreo.find('input#servidor_saliente_puerto').val(587);
                    cuentaCorreo.find('input#servidor_entrante_direccion').val('imap.office365.com');
                    cuentaCorreo.find('select#servidor_entrante_tipo').val(3);
                    cuentaCorreo.find('input#servidor_entrante_usar_auth').prop('checked', false);
                    cuentaCorreo.find('input#servidor_entrante_usar_start_ttls').prop('checked', false);
                    cuentaCorreo.find('input#servidor_entrante_usar_ssl').prop('checked', true);
                    cuentaCorreo.find('input#servidor_entrante_puerto').val(993);
                    break;

                case 'infobip':
                    cuentaCorreo.find('input#servidor_saliente_direccion').val('dyinfobip.cr.dyalogo.cloud');
                    cuentaCorreo.find('select#servidor_saliente_tipo').val(1);
                    cuentaCorreo.find('input#servidor_saliente_usar_auth').prop('checked', true);
                    cuentaCorreo.find('input#servidor_saliente_usar_start_ttls').prop('checked', true);
                    cuentaCorreo.find('input#servidor_saliente_usar_ssl').prop('checked', false);
                    cuentaCorreo.find('input#servidor_saliente_puerto').val(8080);
                    cuentaCorreo.find('input#servidor_entrante_direccion').val('none');
                    cuentaCorreo.find('select#servidor_entrante_tipo').val(3);
                    cuentaCorreo.find('input#servidor_entrante_usar_auth').prop('checked', false);
                    cuentaCorreo.find('input#servidor_entrante_usar_start_ttls').prop('checked', false);
                    cuentaCorreo.find('input#servidor_entrante_usar_ssl').prop('checked', true);
                    cuentaCorreo.find('input#servidor_entrante_puerto').val(993);

                    $(".s-entrante").hide();
                    break;

                case 'otros':
                    cuentaCorreo.find('input#servidor_saliente_direccion').val('');
                    cuentaCorreo.find('select#servidor_saliente_tipo').val('');
                    cuentaCorreo.find('input#servidor_saliente_usar_auth').prop('checked', false);
                    cuentaCorreo.find('input#servidor_saliente_usar_start_ttls').prop('checked', false);
                    cuentaCorreo.find('input#servidor_saliente_usar_ssl').prop('checked', false);
                    cuentaCorreo.find('input#servidor_saliente_puerto').val('');
                    cuentaCorreo.find('input#servidor_entrante_direccion').val('');
                    cuentaCorreo.find('select#servidor_entrante_tipo').val('');
                    cuentaCorreo.find('input#servidor_entrante_usar_auth').prop('checked', false);
                    cuentaCorreo.find('input#servidor_entrante_usar_start_ttls').prop('checked', false);
                    cuentaCorreo.find('input#servidor_entrante_usar_ssl').prop('checked', false);
                    cuentaCorreo.find('input#servidor_entrante_puerto').val('');
                    break;

                default:
                    break;
            }
        }
    });

    /** Si el campo usuario esta vacio automaticamente se agrega el correo electronico */
    $("#form-cuenta-correo #direccion_correo_electronico").focusout(function(){
        let correo = $('#form-cuenta-correo #direccion_correo_electronico');
        let usuario = $('#form-cuenta-correo #usuario');

        if(usuario.val().length == 0){
            usuario.val(correo.val());
        }
    });

    /** Guarda el registro del formulario store y update */
    $('#modal-cuentaCorreo #btn-guardar-cuenta-correo').on('click', function(e){
        e.preventDefault();
        $('#mostrar_loading').addClass('loader');
        $('#form-cuenta-correo .error_c').remove();
        var data = new FormData($('#form-cuenta-correo')[0]);
        var token = $("input[name=_token]").val();
        var id = $('input#id').val();
        var accion = $('#form-cuenta-correo #accion').val();

        switch (accion) {
            case 'registrar':
                $.ajax({
                    url: 'huesped/crear-cuenta-correo/'+id,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){
                        toastr.success(response.message);
                        $("#form-cuenta-correo")[0].reset();
                        getCuentasCorreo(id);
                        $('#modal-cuentaCorreo').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-cuenta-correo [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-cuenta-correo [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                                toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                        }
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;

            case 'actualizar':
                data.append('_method', 'PUT');
                var id_registro = $('#form-cuenta-correo #cc_id').val();
                $.ajax({
                    url: 'huesped/actualizar-cuenta-correo/'+id_registro,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){
                        toastr.success(response.message);
                        getCuentasCorreo(response.cuentaCorreo.id_huesped);
                        $('#modal-cuentaCorreo').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-cuenta-correo [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-cuenta-correo [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                                toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                        }
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;
            default:
                break;
        }
    });

    /** Elimina el registro seleccionado */
    $('#secCuentasCorreo table').on('click', '.btn-eliminar-cuenta-correo', function(){
        var id = $(this).data('id');
        var id_huesped = $('input#id').val();
        var token = $("input[name=_token]").val();

        Swal.fire({
            title: 'Deseas eliminar esta cuenta de correo?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                $('#mostrar_loading').addClass('loader');
                $.ajax({
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'DELETE',
                    url: 'huesped/eliminar-cuenta-correo/'+id,
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        getCuentasCorreo(id_huesped);
                        Swal.fire(
                            'Eliminado!',
                            'Su registro ha sido eliminado.',
                            'success'
                        )
                    },
                    error: function(response) {
                         toastr.error(response.responseJSON.message);
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                })

            }
        })
    });

    /**Busca todos las cuentas de correo perteneciente a un huesped */
    function getCuentasCorreo(id){

        $.ajax({
            type: 'GET',
            url: 'huesped/listar-cuentas-correo/'+id,
            success: function (response) {
                let html11 = '';
                if(response.cuentasCorreo.length > 0){
                    $.each(response.cuentasCorreo, function(key, value){
                        html11 += agregarCuentaCorreo(value);
                    });
                }else{
                    html11 = `
                    <tr>
                        <td colspan="5"><h4>El huésped aún no tiene cuentas de correo</h4></td>
                    </tr>
                    `;
                }

                 $('#secCuentasCorreo table tbody').html(html11);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener las cuentas de correo del huésped");
            }
        })
    };

    /** Agrega al formulario la data de la cuenta de correo seleccionada */
    function editarCuentaCorreo(data){

        let cuentaCorreo = $('#form-cuenta-correo');

        cuentaCorreo.find('input#cc_id').val(data.id);
        cuentaCorreo.find('input#nombre').val(data.nombre);
        cuentaCorreo.find('input#direccion_correo_electronico').val(data.direccion_correo_electronico);
        cuentaCorreo.find('input#servidor_saliente_direccion').val(data.servidor_saliente_direccion);
        cuentaCorreo.find('select#servidor_saliente_tipo').val(data.servidor_saliente_tipo);
        cuentaCorreo.find('input#servidor_saliente_usar_auth').prop('checked', (data.servidor_saliente_usar_auth == 1) ? true : false);
        cuentaCorreo.find('input#servidor_saliente_usar_start_ttls').prop('checked', (data.servidor_saliente_usar_start_ttls == 1) ? true : false);
        cuentaCorreo.find('input#servidor_saliente_usar_ssl').prop('checked', (data.servidor_saliente_usar_ssl == 1) ? true : false);
        cuentaCorreo.find('input#servidor_saliente_puerto').val(data.servidor_saliente_puerto);
        cuentaCorreo.find('input#saliente_responder_a').val(data.saliente_responder_a);
        cuentaCorreo.find('input#saliente_nombre_remitente').val(data.saliente_nombre_remitente);
        cuentaCorreo.find('select#protocoloEntrada').val(data.servidor_entrante_protocolo);
        cuentaCorreo.find('input#servidor_entrante_direccion').val(data.servidor_entrante_direccion);
        cuentaCorreo.find('select#servidor_entrante_tipo').val(data.servidor_entrante_tipo);
        cuentaCorreo.find('input#servidor_entrante_usar_auth').prop('checked', (data.servidor_entrante_usar_auth == 1) ? true : false);
        cuentaCorreo.find('input#servidor_entrante_usar_start_ttls').prop('checked', (data.servidor_entrante_usar_start_ttls == 1) ? true : false);
        cuentaCorreo.find('input#servidor_entrante_usar_ssl').prop('checked', (data.servidor_entrante_usar_ssl == 1) ? true : false);
        cuentaCorreo.find('input#servidor_entrante_puerto').val(data.servidor_entrante_puerto);
        cuentaCorreo.find('input#usuario').val(data.usuario);
        cuentaCorreo.find('input#borrar_correos_procesados').prop('checked', (data.borrar_correos_procesados == 1) ? true : false);
        cuentaCorreo.find('select#estado').val(data.estado);
        cuentaCorreo.find('input#mensajes_estado').val(data.mensajes_estado);
        cuentaCorreo.find('input#intervalo_refresque').val(data.intervalo_refresque);
        cuentaCorreo.find('input#buzon').val(data.buzon);
        cuentaCorreo.find('select#proveedor').val(data.proveedor).change();
    }

    /** Actualiza la tabla de las cuentas de correo del huesped */
    function agregarCuentaCorreo(data){
        let html = `
        <tr>
            <td>${data.nombre}</td>
            <td>${data.proveedor}</td>
            <td>${data.direccion_correo_electronico}</td>
            <td>${data.estado == 1 ? 'activo':'detenido'}</td>
            <td>
                <button data-id="${data.id}" class="btn btn-primary btn-sm btn-editar-cuenta-correo" title="Editar">
                    <i class="fa fa-edit"></i>
                </button>
                <button data-id="${data.id}" class="btn btn-danger btn-sm btn-eliminar-cuenta-correo" title="Eliminar">
                    <i class="fa fa-trash"></i>
                </button>
                <button data-id="${data.id}" data-huesped="${data.id_huesped}" data-usuario="${data.usuario}" data-nombre="${data.direccion_correo_electronico}" class="btn btn-info btn-sm btn-test-cuenta">Prueba</button>
            </td>
        </tr>
        `;

        return html;
    }

    /** Muestra la modal para hacer la prueba de cuentas de correo */
    $('#secCuentasCorreo table').on('click', '.btn-test-cuenta', function(){

        $("#form-test-cuenta-correo")[0].reset();
        $('#modal-test-cuentaCorreo').modal('show');

        $('#modal-test-cuentaCorreo .estado-test-mail-entrada').hide();
        $('#modal-test-cuentaCorreo .estado-test-mail-salida').hide();

        $('#modal-test-cuentaCorreo #consoleLogCorreo').hide();

        $('#form-test-cuenta-correo .error_c').remove();

        var id = $(this).data('id');
        var id_huesped = $(this).data('huesped');
        var usuario = $(this).data('usuario');
        var nombre = $(this).data('nombre');

        $('#form-test-cuenta-correo #id_cuentaCorreo').val(id);
        $('#form-test-cuenta-correo #cuentaCorreoUsuario').val(usuario);
        $('#form-test-cuenta-correo #id_huesped').val(id_huesped);
        $('#form-test-cuenta-correo .cuenta-correo-nombre').text(nombre);
    });

    /** Ejectuta la prueba para la cuentas de correo */
    $('#modal-test-cuentaCorreo #btn-test-cuenta').on('click', function(){
        $('#mostrar_loading').addClass('loader');
        $('#form-test-cuenta-correo .error_c').remove();
        var data = new FormData($('#form-test-cuenta-correo')[0]);

        $('#modal-test-cuentaCorreo #consoleLogCorreo').html('');
        $('#modal-test-cuentaCorreo #consoleLogCorreo').show();

        testSendMailService(data);
    });

    function testSendMailService(data1){
        var data = data1;
        var token = $("input[name=_token]").val();

        var f = new Date();

        var dia = f.getDate() +'-'+ (f.getMonth() + 1) +'-'+ f.getFullYear();
        var hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();

        $('#modal-test-cuentaCorreo #consoleLogCorreo').append('Prueba cuenta de correo &#13;&#10;');
        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Prueba de correo de salida iniciado  &#13;&#10;');

        $.ajax({
            url: 'huesped/test-correo-send-mail-service',
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function(response){

                $('#modal-test-cuentaCorreo .estado-test-mail-salida').text('Prueba de salida fallida').removeClass('text-success').addClass('text-danger').show();
                if(response.json){

                    if(response.json.strEstado_t == 'ok'){
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Estado: '+response.json.strEstado_t+' &#13;&#10;');
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Se ha ejecutado la prueba de correo de salida exitosamente &#13;&#10;');
                        $('#modal-test-cuentaCorreo .estado-test-mail-salida').text('Prueba de salida exitosa').removeClass('text-danger').addClass('text-success');
                    }else{
                        hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Estado: '+response.json.strEstado_t+' &#13;&#10;');
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Mensaje: '+response.json.strMensaje_t+' &#13;&#10;');
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Hubo un error al ejecutar la prueba de salida de correo &#13;&#10;');
                    }


                    //toastr.success(response.message + response.json.strStatus_t);

                }else{
                    $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Ha habido un problema al comunicarse con la api send mail &#13;&#10;');
                }

                testEntrada(data);
            },
            error: function(response){
                if(response.status === 422){
                    $.each(response.responseJSON.errors, function(key, value){
                        var name = $("#form-test-cuenta-correo [name='"+key+"']");
                        if(key.indexOf(".") != -1){
                            var arr = key.split(".");
                            name = $("#form-test-cuenta-correo [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                        }
                        name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                        name.focus();
                    });
                    toastr.info('Hubo un problema al validar tus datos');
                    hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();
                    $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' No se ha podido enviar la información  &#13;&#10;');
                    $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Prueba finalizada &#13;&#10;');
                }else if(response.status === 502){
                    toastr.error('Se ha presentado un error en el envio de email.');
                    $('#mostrar_loading').removeClass('loader');
                }
                else if(response.status === 504){
                    toastr.error('Se ha presentado un error en el envio de email.');
                    $('#mostrar_loading').removeClass('loader');
                }
                else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al enviar los datos.');
                    }
                }

                $('#mostrar_loading').removeClass('loader');
            },
            complete:function(){
                //$('#mostrar_loading').removeClass('loader');
            }
        });
    }

    function testEntrada(data1){

        var data = data1;
        var token = $("input[name=_token]").val();

        var f = new Date();

        var dia = f.getDate() +'-'+ (f.getMonth() + 1) +'-'+ f.getFullYear();
        var hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();

        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Prueba de correo de entrada iniciado &#13;&#10;');

        $.ajax({
            url: 'huesped/test-correo-in',
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function(response){

                $('#modal-test-cuentaCorreo .estado-test-mail-entrada').text('Prueba de entrada fallida').removeClass('text-success').addClass('text-danger').show();
                if(response.json){

                    if(response.json.strEstado_t == 'ok'){
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Se ha ejecutado la prueba de entrada de correo exitosamente &#13;&#10;');
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Prueba de correo de entrada finalizada &#13;&#10;');
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Prueba finalizada &#13;&#10;');
                        $('#modal-test-cuentaCorreo .estado-test-mail-entrada').text('Prueba de entrada exitosa').removeClass('text-danger').addClass('text-success');
                    }else{
                        hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Estado: '+response.json.strEstado_t+' &#13;&#10;');
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Mensaje: '+response.json.strMensaje_t+' &#13;&#10;');
                        $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Hubo un error al ejecutar la prueba de entrada de correo &#13;&#10;');
                    }
                    //toastr.success(response.message + response.json.strStatus_t);

                }else{
                    $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Ha habido un problema al comunicarse con la api de mail entrada &#13;&#10;');
                }

            },
            error: function(response){
                if(response.status === 422){
                    $.each(response.responseJSON.errors, function(key, value){
                        var name = $("#form-test-cuenta-correo [name='"+key+"']");
                        if(key.indexOf(".") != -1){
                            var arr = key.split(".");
                            name = $("#form-test-cuenta-correo [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                        }
                        name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                        name.focus();
                    });
                    toastr.info('Hubo un problema al validar tus datos');
                    hora = f.getHours() +':'+ f.getMinutes() +':'+ f.getSeconds();
                    $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' No se ha podido enviar la información  &#13;&#10;');
                    $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' Prueba finalizada &#13;&#10;');
                }else if(response.status === 502){
                    toastr.error('Se ha presentado un error en el envio de email.');
                    $('#mostrar_loading').removeClass('loader');
                    $('#modal-test-cuentaCorreo .estado-test-mail-entrada').text('Prueba de entrada fallida').removeClass('text-success').addClass('text-danger').show();
                    $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' No se pudo recibir respuesta del servidor  &#13;&#10;');
                }
                else if(response.status === 504){
                    toastr.error('Se ha presentado un error en el envio de email.');
                    $('#mostrar_loading').removeClass('loader');
                    $('#modal-test-cuentaCorreo .estado-test-mail-entrada').text('Prueba de entrada fallida').removeClass('text-success').addClass('text-danger').show();
                    $('#modal-test-cuentaCorreo #consoleLogCorreo').append(dia +' '+hora+' No se pudo recibir respuesta del servidor  &#13;&#10;');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al enviar los datos.');
                    }
                }
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });
    }

    $('#form-prueba-proveedor-sms').submit(function(e){
        e.preventDefault();
    });

    $('#form-test-cuenta-correo').submit(function(e){
        e.preventDefault();
    });

    /*--------------------------------- Troncales  ---------------------------------*/

    /** Retorna una fila de la troncal */
    function getHtmlTroncal(data){
        let html = `
        <tr>
            <td>${data.nombre_usuario}</td>
            <td>${data.nombre_interno}</td>
            <td id="estadoTroncal${data.id}"><spam class="label label-info">Cargando...</spam></td>
            <td>
                <button data-id="${data.id}" type="button" class="btn btn-primary btn-sm btn-editar-troncal" title="Editar"><i class="fa fa-edit"></i></button>
                <button data-id="${data.id_proyecto}" type="button" class="btn btn-info btn-sm btn-consultar-estado" title="Estado"><i class="fa fa-repeat"></i></button>
                <button data-id="${data.id}" type="button" class="btn btn-danger btn-sm btn-eliminar-troncal" title="Eliminar"><i class="fa fa-trash"></i></button>
             </td>
        </tr>
        `;

        return html;
    }

    /** Esta funcion retorna la tabla con todas las troncales del huesped */
    function getTroncales(id){

        $.ajax({
            type: 'GET',
            url: 'huesped/listar-troncales/'+id,
            success: function (response) {
                let html11 = '';
                if(response.troncales.length > 0){
                    $.each(response.troncales, function(key, value){
                        html11 += getHtmlTroncal(value);
                    });
                }else{
                    html11 = `
                    <tr>
                        <td colspan="5"><h4>El huésped aún no tiene troncales</h4></td>
                    </tr>
                    `;
                }

                $('#secTroncales table tbody').html(html11);
                getEstadosTroncales(id);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener las cuentas de correo del huésped");
            }
        })
    };

    function getEstadosTroncales(id_huesped){
        $.ajax({
            type: 'GET',
            url: 'huesped/estado-troncal/'+id_huesped,
            success: function (response) {

                for (const [key, value] of Object.entries(response.troncalEstado)) {

                    let my_html = '';
                    switch (value) {
                        case 'ok':
                            my_html = '<spam class="label label-success">'+value+'</spam>';
                            break;
                        case 'no_configurada':
                                my_html = '<spam class="label label-primary">'+value+'</spam>';
                            break;
                        case 'error':
                                my_html = '<spam class="label label-danger">'+value+'</spam>';
                            break;
                        case 'no_existe':
                            my_html = '<spam class="label label-danger">'+value+'</spam>';
                            break;
                        case 'no_autorizado':
                            my_html = '<spam class="label label-default">'+value+'</spam>';
                            break;
                        case 'error_api':
                                my_html = '<spam class="label label-danger">'+value+'</spam>';
                            break;
                        default:
                                my_html = '<spam class="label label-default">'+value+'</spam>';
                            break;
                    }
                    $('#estadoTroncal'+key).html(my_html);
                }
            },
            error: function(response) {
                if (typeof response.message !== 'undefined') {
                    toastr.error(response.message);
                }else if(typeof response.responseJSON.message !== 'undefined'){
                    toastr.error(response.responseJSON.message);
                }else{
                    toastr.error('Se presentó un error al obtener los datos');
                }
            }
        })
    }

    /** Llena el formulario de troncal con los datoas recibidos para la edicion */
    function editarTroncal(data){
        let troncal = $('#form-troncal');

        troncal.find('input#id_troncal').val(data.id);
        troncal.find('input#troncal_nombre_usuario').val(data.nombre_usuario);
        troncal.find('input#troncal_codigo_cuenta').val(data.codigo_cuenta);

        if(data.usar_codigo_antepuesto == 1){
            troncal.find('input#troncal_usar_codigo_antepuesto').prop('checked', true);
            $('#troncal_codigo_antepuesto').val(data.codigo_antepuesto);
        }else{
            troncal.find('input#troncal_usar_codigo_antepuesto').prop('checked', false);
            $('#troncal_codigo_antepuesto').val('');
        }

        activaInputCodigoAntepuesto();

        // En esta seccion inserta las propiedades de la troncal

        data.configuraciones.forEach( item => {
            switch (item.propiedad.propiedad) {
                case 'type':
                    troncal.find('select#troncal_tipo').val(item.valor);
                    break;
                case 'host':
                    troncal.find('input#troncal_direccion_servidor').val(item.valor);
                    break;
                case 'defaultuser':
                    troncal.find('input#troncal_usuario_defecto').val(item.valor);
                    break;
                case 'fromuser':
                    troncal.find('input#troncal_fuente').val(item.valor);
                    break;
                case 'secret':
                    troncal.find('input#troncal_contrasena').val(item.valor);
                    break;
                case 'rfc2833compensate':
                    troncal.find('select#troncal_compensar_rfc2833').val(item.valor);
                    break;
                case 'call-limit':
                    troncal.find('input#troncal_limite_llamadas').val(item.valor);
                    break;
                case 'context':
                    troncal.find('input#troncal_contexto').val(item.valor);
                    break;
                case 'canreinvite':
                    troncal.find('select#troncal_habilitar_puente_rtp').val(item.valor);
                    break;
                case 'insecure':
                    troncal.find('input#troncal_autenticacion').val(item.valor);
                    break;
                case 'dtmfmode':
                    troncal.find('select#troncal_mododtmf').val(item.valor);
                    break;
                case 'nat':
                    troncal.find('select#troncal_nat').val(item.valor);
                    break;
                case 'qualify':
                    troncal.find('select#troncal_permitir_verificacion').val(item.valor);
                    break;
                case 'Codec_U':
                    troncal.find('input#troncal_codec_u').prop('checked', (item.valor == 1) ? true : false);
                    break;
                case 'Codec_A':
                    troncal.find('input#troncal_codec_a').prop('checked', (item.valor == 1) ? true : false);
                    break;
                case 'Codec_G729':
                    troncal.find('input#troncal_g729').prop('checked', (item.valor == 1) ? true : false);
                    break;
                default:
                    let id = agregarNuevaPropiedad(false, true);
                    $("#troncal_prop_id_"+id).val(item.id);
                    $("#troncal_prop_tipo_"+id).val(item.id_propiedad).change();
                    $("#troncal_prop_valor_"+id).val(item.valor);
                    break;
            }
        });
    }

    function activaInputCodigoAntepuesto(){

        $("#troncal_codigo_antepuesto").hide();

        if( $('#troncal_usar_codigo_antepuesto').is(':checked') ) {
            $("#troncal_codigo_antepuesto").show();
        }

    }

    $("#agregarPropiedad").on('click', function(){
        agregarNuevaPropiedad();
    });

    $("#agregarPropiedadNueva").on('click', function(){
        agregarNuevaPropiedad(true);
    });

    function agregarNuevaPropiedad(propiedadNueva = false,devolverId = false){

        let uniqueId = Math.random().toString(30).substring(2);

        let propsTroncales = '';

        let campoPropiedad = '';

        if(!propiedadNueva){
            propieadadesTroncales.forEach(element => {
                propsTroncales += `<option value="${element.id}">${element.nombre}</option>`
            });

            campoPropiedad = `<select name="troncal_prop_tipo[]" id="troncal_prop_tipo_${uniqueId}" class="form-control input-sm">
                ${propsTroncales}
            </select>`;
        }else{
            campoPropiedad = `<input type="text" class="form-control input-sm" placeholder="Propiedad nueva" name="troncal_prop_tipo[]" id="troncal_prop_tipo_${uniqueId}">`;
        }

        let row = `
        <li class="list-group-item" id="propiedad_${uniqueId}" style="padding-bottom: 0px">
            <div class="form-group row">
                <input type="hidden" name="troncal_prop_id[]" id="troncal_prop_id_${uniqueId}" value="0">
                <div class="col-md-1">
                    <span class="glyphicon glyphicon-resize-vertical"></span>
                </div>
                <div class="col-md-4">
                    ${campoPropiedad}
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-sm" placeholder="Valor" name="troncal_prop_valor[]" id="troncal_prop_valor_${uniqueId}">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm pull-right borrar-parametro-troncal" data-id="${uniqueId}"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </li>
        `;
        $("#propiedadesAdicionales").append(row);

        if(!propiedadNueva){
            $("#troncal_prop_tipo_"+uniqueId).select2();
        }

        if(devolverId){
            return uniqueId;
        }
    }

    $("#troncal_usar_codigo_antepuesto").on('click', function(){
        activaInputCodigoAntepuesto();
    });

    $("#propiedadesAdicionales").on('click', '.borrar-parametro-troncal', function(){
        
        let id = $(this).attr('data-id');
        let token = $("input[name=_token]").val();

        // Busco el id del registro
        let propiedadId = $("#troncal_prop_id_"+id).val();

        Swal.fire({
            title: 'Deseas eliminar esta propiedad?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {    

                if(propiedadId != 0){
                    $('#mostrar_loading').addClass('loader');
                    $.ajax({
                        headers: {'X-CSRF-TOKEN':token},
                        type: 'post',
                        url: 'huesped/eliminar-propiedad-troncal',
                        data: {
                            "propiedadId": propiedadId
                        },
                        success: function (response) {
                            Swal.fire(
                                'Eliminado!',
                                'Propiedad eliminada',
                                'success'
                            )
                        },
                        error: function(response) {
                            toastr.error(response.responseJSON.message);
                        },
                        complete:function(){
                            $('#mostrar_loading').removeClass('loader');
                        }
                    })
                }

                $('#propiedad_'+id).remove();    
            }
        });
    });

    /** Este boton despliega una modal para registrar una troncal */
    $('#btn-agregar-troncal').on('click', function(){
        $("#form-troncal")[0].reset();
        $('#modal-troncal .modal-title').text("Registrar troncal");
        $("#form-troncal #accion").val('registrar');
        $('#form-troncal .error_c').remove();
        $('#modal-troncal').modal('show');

        // Limpio el campo de las propiedades adicionales
        $("#propiedadesAdicionales").html('');
        Sortable.create(propiedadesAdicionales, { /* options */ });

        let token = $("input[name=_token]").val();

        // Obtenemos las propiedades de la troncal
        $.ajax({
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            url: 'huesped/obtener-propiedades',
            dataType: 'json',
            success: function (response) {
                // llenamos las propieades
                if(response.propiedades){
                    propieadadesTroncales = [];
                    response.propiedades.forEach(element => {
                        propieadadesTroncales.push({'id': element.id, 'nombre':element.propiedad});
                    });
                }
            },
            error: function(response) {
                if (typeof response.message !== 'undefined') {
                    toastr.error(response.message);
                }else if(typeof response.responseJSON.message !== 'undefined'){
                    toastr.error(response.responseJSON.message);
                }else{
                    toastr.error('Se presentó un error al obtener los datos');
                }
            }
        })
    });

    /** Guardar el registro de l formulario de troncal strore y update */
    $('#modal-troncal #btn-guardar-troncal').on('click', function(e){
        e.preventDefault();

        $('#mostrar_loading').addClass('loader');
        $('#form-troncal .error_c').remove();

        var data = new FormData($('#form-troncal')[0]);
        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();
        var accion = $('#form-troncal #accion').val();

        switch (accion) {
            case 'registrar':
                $.ajax({
                    url: 'huesped/crear-troncal/'+id_huesped,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){
                        toastr.success(response.message);
                        $("#form-troncal")[0].reset();
                        getTroncales(id_huesped);
                        $('#modal-troncal').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-troncal [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-troncal [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                                toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                        }
                    },
                    complete: function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;

            case 'actualizar':
                data.append('_method', 'PUT');
                var id_troncal = $('#form-troncal #id_troncal').val();

                $.ajax({
                    url: 'huesped/actualizar-troncal/'+id_troncal,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){
                        toastr.success(response.message);
                        getTroncales(id_huesped);
                        $('#modal-troncal').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-troncal [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-troncal [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                               toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                         }
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;
            default:
                break;
        }
    });

    var propieadadesTroncales = [];

    /** Este evento abre la modal y trae el registro de la troncal para poderla editar */
    $('#secTroncales table').on('click', '.btn-editar-troncal', function(){

        $("#form-troncal")[0].reset();
        $('#modal-troncal .modal-title').text("Editar troncal");
        $("#form-troncal #accion").val('actualizar');
        $('#modal-troncal').modal('show');

        $('#form-troncal .error_c').remove();
        $('#mostrar_loading').addClass('loader');

        // Limpio el campo de las propiedades adicionales
        $("#propiedadesAdicionales").html('');
        Sortable.create(propiedadesAdicionales, { /* options */ });

        var id = $(this).data('id');

        $.ajax({
            type: 'GET',
            url: 'huesped/troncal/'+id,
            success: function (response) {

                // llenamos las propieades
                if(response.propiedades){
                    propieadadesTroncales = [];
                    response.propiedades.forEach(element => {
                        propieadadesTroncales.push({'id': element.id, 'nombre':element.propiedad});
                    });
                }

                editarTroncal(response.troncal);

            },
            error: function(response) {
                 toastr.error(response.message);
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })
    });

    /** Muestra el estado de la troncal */
    $('#secTroncales table').on('click', '.btn-consultar-estado', function(){
        var id_huesped = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: 'huesped/estado-troncal/'+id_huesped,
            success: function (response) {

                for (const [key, value] of Object.entries(response.troncalEstado)) {
                    let my_html = '';
                    switch (value) {
                        case 'ok':
                            my_html = '<spam class="label label-success">'+value+'</spam>';
                            break;
                        case 'no_configurada':
                                my_html = '<spam class="label label-primary">'+value+'</spam>';
                            break;
                        case 'error':
                                my_html = '<spam class="label label-danger">'+value+'</spam>';
                            break;
                        case 'no_existe':
                            my_html = '<spam class="label label-danger">'+value+'</spam>';
                            break;
                        case 'no_autorizado':
                            my_html = '<spam class="label label-default">'+value+'</spam>';
                            break;
                        case 'error_api':
                                my_html = '<spam class="label label-danger">'+value+'</spam>';
                            break;
                        default:
                                my_html = '<spam class="label label-default">'+value+'</spam>';
                            break;
                    }
                    $('#estadoTroncal'+key).html(my_html);
                }
                toastr.success('Estado actualizado');
            },
            error: function(response) {
                if (typeof response.message !== 'undefined') {
                    toastr.error(response.message);
                }else if(typeof response.responseJSON.message !== 'undefined'){
                    toastr.error(response.responseJSON.message);
                }else{
                    toastr.error('Se presentó un error al obtener los datos');
                }
            },
            beforeSend: function(){
                $('#mostrar_loading').addClass('loader');
            },
            complete: function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })
    });

    /** Elimina el registro de la troncal */
    $('#secTroncales table').on('click', '.btn-eliminar-troncal', function(){
        var id_troncal = $(this).data('id');
        var id_huesped = $('input#id').val();
        var token = $("input[name=_token]").val();

        Swal.fire({
            title: 'Deseas eliminar esta troncal?',
            text: 'Al eliminar esta troncal también estarás borrando la configuración realizada en las campañas con esta troncal.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                $('#mostrar_loading').addClass('loader');
                $.ajax({
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'DELETE',
                    url: 'huesped/eliminar-troncal/'+id_troncal,
                    data: {
                        "id_troncal": id_troncal
                    },
                    success: function (response) {
                        getTroncales(id_huesped);
                        Swal.fire(
                            'Eliminado!',
                            'Su registro ha sido eliminado.',
                            'success'
                        )
                    },
                    error: function(response) {
                         toastr.error(response.responseJSON.message);
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                })

            }
        })
    });

    /** ----------------------- Usuarios ---------------------------  */
    /** Retorna una fila de usuario */
    function getHtmlUsuario(data){
        let html = `
        <tr>
            <td>${data.USUARI_Nombre____b}</td>
            <td>${data.USUARI_Correo___b}</td>
            <td>
                <button data-id_huesped="${data.id_huesped}" data-id_usuario="${data.id_usuario}" type="button" class="btn btn-danger btn-sm btn-eliminar-usuario" title="Desvincular"><i class="fa fa-chain-broken"></i></button>
             </td>
        </tr>
        `;

        return html;
    }

    /** Esta funcion retorna la tabla con todos los usuarios asignados al huesped */
    function getUsuarios(id){

        $.ajax({
            type: 'GET',
            url: 'huesped/listar-usuarios/'+id,
            success: function (response) {
                let html11 = '';
                if(response.usuarios.length > 0){
                    $.each(response.usuarios, function(key, value){
                        html11 += getHtmlUsuario(value);
                    });
                }else{
                    html11 = `
                    <tr>
                        <td colspan="5"><h4>El huésped aún no tiene usuarios inter-huésped asociados</h4></td>
                    </tr>
                    `;
                }

                 $('#collapseUsuarios table tbody').html(html11);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener las cuentas de correo del huésped");
            }
        })
    };

    /** Abre la modal para hacer la asignacion de usuarios */
    $('#btn-asignar-usuario').on('click', function(){
        $("#form-usuario-asignar")[0].reset();
        $('#modal-usuario-asignar .modal-title').text("Asignar un usuario al huesped");
        $('#modal-usuario-asignar').modal('show');

        var id_huesped = $('input#id').val();

        $.ajax({
            type: 'GET',
            url: 'listar-usuarios-admin/'+id_huesped,
            success: function (response) {
                let html11 = '<option value="">Seleccionar</option>';
                if(response.usuarios.length > 0){
                    $.each(response.usuarios, function(key, value){
                        html11 += '<option value="'+value.USUARI_UsuaCBX___b+'">'+value.USUARI_Correo___b+'</option>';
                    });
                }
                 $('#modal-usuario-asignar #usuarioAsignar').html(html11);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener las cuentas de usuario");
            }
        })
    });

    /** Registra el usuario seleccionado al huesped */
    $('#modal-usuario-asignar #btn-guardar-usuario-asignacion').on('click', function(e){
        e.preventDefault();

        $('#mostrar_loading').addClass('loader');
        $('#form-usuario-asignar .error_c').remove();

        var data = new FormData($('#form-usuario-asignar')[0]);
        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();

        $.ajax({
            url: 'huesped/asignar-usuario/'+id_huesped,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function(response){
                toastr.success(response.message);
                getUsuarios(id_huesped);
                $('#modal-usuario-asignar').modal('hide');
            },
            error: function(response){
                if(response.status === 422){
                    $.each(response.responseJSON.errors, function(key, value){
                        var name = $("#form-usuario-asignar [name='"+key+"']");
                        if(key.indexOf(".") != -1){
                            var arr = key.split(".");
                            name = $("#form-usuario-asignar [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                        }
                        name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                        name.focus();
                    });
                    toastr.info('Hubo un problema al validar tus datos');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al guardar los datos.');
                    }
                }
            },
            complete: function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });
    });

    /** Abre la modal para crear un usuario al huesped seleccionado */
    $('#btn-crear-usuario').on('click', function(){
        $("#form-usuario-crear")[0].reset();//**aqui quede */
        $('#modal-usuario-crear .modal-title').text("Crear usuario");
        $('#form-usuario-crear .error_c').remove();
        $('#modal-usuario-crear').modal('show');
    });

    /**Guarda el usuario */
    $('#btn-store-usuario').on('click', function(e){
        e.preventDefault();

        $('#mostrar_loading').addClass('loader');
        $('#form-usuario-crear .error_c').remove();

        var data = new FormData($('#form-usuario-crear')[0]);
        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();

        $.ajax({
            url: 'huesped/crear-usuario/'+id_huesped,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function(response){
                $("#form-usuario-crear")[0].reset();
                toastr.success(response.message);
                getUsuarios(id_huesped);
                $('#modal-usuario-crear').modal('hide');
            },
            error: function(response){
                if(response.status === 422){
                    $.each(response.responseJSON.errors, function(key, value){
                        var name = $("#form-usuario-crear [name='"+key+"']");
                        if(key.indexOf(".") != -1){
                            var arr = key.split(".");
                            name = $("#form-usuario-crear [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                        }
                        name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                        name.focus();
                    });
                    toastr.info('Hubo un problema al validar tus datos');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al guardar los datos.');
                    }
                }
            },
            complete: function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });
    });

    /** Desvicula el usuario del huesped */
    $('#collapseUsuarios table').on('click', '.btn-eliminar-usuario', function(){
        var id_huesped = $(this).data('id_huesped');
        var id_usuario = $(this).data('id_usuario');
        var token = $("input[name=_token]").val();

        Swal.fire({
            title: 'Desea desvincular este usuario del huésped?',
            text: 'Al realizar esta operación solo se desvinculará el usuario del huésped, el registro del usuario no se eliminará.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, desvincular!'
        }).then((result) => {
            if (result.value) {
                $('#mostrar_loading').addClass('loader');
                $.ajax({
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'DELETE',
                    url: 'huesped/eliminar-usuario/'+id_usuario,
                    data: {
                        "id_huesped": id_huesped, "id_usuario": id_usuario
                    },
                    success: function (response) {
                        getUsuarios(id_huesped);

                        var titulo = 'Desvincular';
                        if(response.status == 'info'){
                            titulo = 'Denegado';
                        }
                        Swal.fire(
                            titulo,
                            response.message,
                            response.status
                        )
                    },
                    error: function(response) {
                         toastr.error(response.responseJSON.message);
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                })

            }
        })
    });


    /**--------------Seccion Patrones------------- */
    function listarPatrones(id_huesped){
        $.ajax({
            type: 'GET',
            url: 'huesped/patron/'+id_huesped,
            success: function (response) {

                let html11 = '';
                if(response.tiposDestino){
                    $.each(response.tiposDestino, function(key, value){
                        html11 += agregarPatrones(value);
                    });
                }else{
                    html11 = `
                    <tr>
                        <td colspan="8"><h4>El huésped no tiene patrones asignados</h4></td>
                    </tr>
                    `;
                }

                $('#secPatron table tbody').html(html11);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener la lista de patrones");
            },
            complete: function(){
                $('#form-patrones table tbody tr').find('input').prop('readonly',true);
            }
        })
    }

    /**Esta funcion retorna las pausas que tiene el huesped */
    function agregarPatrones(data){

        let html = `
        <tr id="${data.id}">
            <input type="hidden" name="patron[]" value="${data.id}">
            <td><input type="text" id="nombre${data.id}" name="nombre${data.id}" class="form-control input-sm" value="${data.nombre}"></td>
            <td><input type="text" id="codigo${data.id}" name="codigo${data.id}" class="form-control input-sm" value="${data.codigo_antepuesto}"></td>
            <td><input type="text" id="patron${data.id}" name="patron${data.id}" class="form-control input-sm" value="${data.patron}"></td>
            <td>
                <button type="button" id="editarPatron${data.id}" data-id="${data.id}" class="btn btn-primary btn-sm editar-patron"><i class="fa fa-edit"></i></button>
                <button type="button" id="guardarPatron${data.id}" data-id="${data.id}" class="btn btn-primary btn-sm guardar-patron" style="display:none"><i class="fa fa-save"></i></button>
                <button type="button" id="eliminarPatron${data.id}" data-id="${data.id}" class="btn btn-danger btn-sm eliminar-patron" title="Eliminar patron"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
        `;
        return html;
    }

    // habilita la edicion del patron
    $('#form-patrones table tbody').on('click', '.editar-patron', function(){
        var id = $(this).data('id');
        $("#guardarPatron"+id).show();
        $("#editarPatron"+id).hide();

        // $("#nombre"+id).prop('readonly',false);
        // $("#patron"+id).prop('readonly',false);
        $("#codigo"+id).prop('readonly',false);
    });

    // Guarda el patron
    $('#form-patrones table tbody').on('click', '.guardar-patron', function(e){
        e.preventDefault();

        var id = $(this).data('id');
        $('#mostrar_loading').addClass('loader');

        var nombrep = $("#nombre"+id).val();
        var patronp = $("#patron"+id).val();
        var codigo = $("#codigo"+id).val();

        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();

        if(id > 0){
            // Actualizar patron
            var ruta = "huesped/actualizar-patron/"+id;
        }else{
            // Crear patron
            var ruta = "huesped/crear-patron/"+id_huesped;
        }

        $.ajax({
            url: ruta,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            data: {"nombre": nombrep, "patron": patronp, "codigo": codigo},
            success: function(response){
                toastr.success(response.message);
                $("#guardarPatron"+id).hide();
                $("#editarPatron"+id).show();
                listarPatrones(id_huesped);
            },
            error: function(response){
                if(response.status === 422){
                    // $.each(response.responseJSON.errors, function(key, value){
                    //     var name = $("#form-pausas [name='"+key+"']");
                    //     if(key.indexOf(".") != -1){
                    //         var arr = key.split(".");
                    //         name = $("#form-pausas [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                    //     }
                    //     name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                    //     name.focus();
                    // });
                    toastr.info('Hubo un problema al validar tus datos');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al guardar los datos.');
                    }
                }
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });
    });

    $('#form-patrones table tbody').on('click', '.eliminar-patron', function(){
        var id = $(this).data('id');
        var id_huesped = $('input#id').val();

        if(id > 0){
            // Ejecuto el ajax para la eliminacion del patron
            var token = $("input[name=_token]").val();

            Swal.fire({
                title: 'Deseas eliminar este patron?',
                text: 'Al eliminar este patrón también estarás borrando la configuración de pasos troncales relacionadas con este patrón',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if (result.value) {
                    $('#mostrar_loading').addClass('loader');
                    $.ajax({
                        headers: {'X-CSRF-TOKEN':token},
                        type: 'post',
                        url: 'huesped/eliminar-patron/'+id,
                        data: {
                            "id": id
                        },
                        success: function (response) {
                            listarPatrones(id_huesped);
                            Swal.fire(
                                'Eliminado!',
                                'Su registro ha sido eliminado.',
                                'success'
                            )
                        },
                        error: function(response) {
                            if(response.responseJSON.message){
                                toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error("Debido a un error el patron no pudo ser eliminado");
                            }
                        },
                        complete:function(){
                            $('#mostrar_loading').removeClass('loader');
                        }
                    })

                }
            })
        }else{
            $(this).closest('tr').remove();
        }

    });

    // var contadorPatrones = 0;

    /** Agregar nuevo patrones a la tabla */
    // $('#btn-agregar-patron').on('click', function(){
    //     let html = `
    //     <tr id="${contadorPatrones}">
    //         <input type="hidden" name="patron[]" value="${contadorPatrones}">
    //         <td><input type="text" id="nombre${contadorPatrones}" name="nombre${contadorPatrones}" class="form-control input-sm"></td>
    //         <td><input type="text" id="patron${contadorPatrones}" name="patron${contadorPatrones}" class="form-control input-sm"></td>
    //         <td>
    //             <button type="button" id="editarPatron${contadorPatrones}" data-id="${contadorPatrones}" class="btn btn-primary btn-sm editar-patron" style="display:none"><i class="fa fa-edit"></i></button>

    //             <button type="button" id="guardarPatron${contadorPatrones}" data-id="${contadorPatrones}" class="btn btn-primary btn-sm guardar-patron"><i class="fa fa-save"></i></button>

    //             <button type="button" id="eliminarPatron${contadorPatrones}" data-id="${contadorPatrones}" class="btn btn-danger btn-sm eliminar-patron"><i class="fa fa-trash"></i></button>
    //         </td>
    //     </tr>
    //     `;
    //     contadorPatrones-= 1;
    //     $('#secPatron table tbody').append(html);
    // });

    $('#btn-agregar-paises').on('click', function(){
        $('#modal-patronesPaises').modal('show');
    });

    $('#detallePatron tbody').on('click', '.agegarCodigo', function(e){
        e.preventDefault();

        var id = $(this).data('id');
        $('#mostrar_loading').addClass('loader');

        var nombrep = '';
        var patronp = '';
        var codigop = '';
        var ejemplop = '';

        if($(this).data('nom') != '-1'){
            nombrep = $(this).data('nom');
        }else{
            nombrep = $('#nombrePer').val();
        }

        if($(this).data('pat') != '-1'){
            patronp = $(this).data('pat');
        }else{
            patronp = $('#patronPer').val();
        }

        if($(this).data('cod') != '-1'){
            codigop = $(this).data('cod');
        }else{
            codigop = $('#codigoPer').val();
        }

        if(id != '-1'){
            ejemplop = $("#ejemplo"+id).val();
        }else{
            ejemplop = $("#ejemploPer").val();
        }

        let agregarCodigoPais = 0;

        if( $('#agregarCodigoPaisCheck'+id).is(':checked') ){
            agregarCodigoPais = 1;
        }

        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();

        var ruta = "huesped/crear-patron/"+id_huesped;

        $.ajax({
            url: ruta,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            data: {"nombre": nombrep, "patron": patronp, "codigo": codigop, "ejemplo": ejemplop, "agregarCodigoPais": agregarCodigoPais},
            success: function(response){
                toastr.success(response.message);
                listarPatrones(id_huesped);
                $('#modal-patronesPaises').modal('hide');
            },
            error: function(response){
                if(response.status === 422){
                    toastr.info('Hubo un problema al validar tus datos');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al guardar los datos.');
                    }
                }
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });
    });

    $('#buscarPatronByPais').on('click', function(){
        var codigo = $("#listaPais").val();
        $('#mostrar_loading').addClass('loader');
        $.ajax({
            type: 'GET',
            url: 'huesped/listar-patrones/'+codigo,
            success: function (response) {

                let html11 = '';
                if(response.patrones){
                    $.each(response.patrones, function(key, value){
                        html11 += `
                        <tr>
                            <td>${value.nombre_patron}</td>
                            <td>${value.patron}</td>
                            <td>${value.codigo_pais}</td>
                            <td>
                                <input type="checkbox" name="agregarCodigoPaisCheck${value.id}" id="agregarCodigoPaisCheck${value.id}" class="agregar-codigo-patron" data-id="${value.id}" data-pat="${value.patron}" data-cod="${value.codigo_pais}">
                            </td>
                            <td><input type="input" id="ejemplo${value.id}" name="ejemplo${value.id}" class="form-control input-sm" style="width:60%" value="${value.patron}" placeholder="XXXXXXXXXX"></td>
                            <td>
                                <button type="button" data-id="${value.id}" data-nom="${value.nombre_patron}" data-pat="${value.patron}" data-cod="${value.codigo_pais}" class="btn btn-primary btn-sm agegarCodigo">Agregar</button>
                            </td>
                        </tr>
                        `;
                    });
                }else{
                    html11 = `
                    <tr>
                        <td colspan="8"><h4>No se encontraron patrones</h4></td>
                    </tr>
                    `;
                }

                $('#detallePatron tbody').html(html11);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener la lista de patrones");
            },
            complete: function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })
    });

    $("#detallePatron tbody").on('click', '.agregar-codigo-patron', function(){
        let id = $(this).data('id');
        let patron = $(this).data('pat');
        let codigoPais = $(this).data('cod');

        let patronEjemplo = $("#ejemplo"+id).val();

        if( $('#agregarCodigoPaisCheck'+id).is(':checked') ) {

            patronEjemplo = codigoPais + patronEjemplo;

        }else{

            patronEjemplo = patron;

        }

        $("#ejemplo"+id).val(patronEjemplo);

    });

    $('#agregarPersonalizado').on('click', function(){
        let html11 = '';
        html11 += `
            <tr>
                <td><input type="text" id="nombrePer" name="nombrePer" class="form-control input-sm"></td>
                <td><input type="text" id="patronPer" name="patronPer" class="form-control input-sm patron-agregar"></td>
                <td><input type="text" id="codigoPer" name="codigoPer" class="form-control input-sm"></td>
                <td>
                    <button type="button" data-id="-1" data-nom="-1" data-pat="-1" data-cod="-1" class="btn btn-primary btn-sm agegarCodigo">Guardar</button>
                </td>
            </tr>
        `;
        $('#detallePatron tbody').html(html11);
    });

    $('#detallePatron tbody, #form-patrones tbody').on('keyup', '.patron-agregar', function(){
        let patronValidar = $(this).val();
        let out = '';
        let filtro = 'XZN1234567890.';

        //Recorrer el texto y verificar si el caracter se encuentra en la lista de validos
        for (var i=0; i<patronValidar.length; i++){
            if (filtro.indexOf(patronValidar.charAt(i)) != -1)
                //Se añaden a la salida los caracteres validos
            out += patronValidar.charAt(i);
        }

        $(this).val(out);
    });

     /**--------------Seccion WEBSERVICE------------- */
    $("#btn-crear-webservice").on('click', function(){
        $("#form-webservice")[0].reset();
        $('#modal-webservice .modal-title').text("WebService");
        $("#form-webservice #wsAccion").val('add');
        $('#form-webservice .error_c').remove();

        $("#tableHeaders tbody").html('');
        $("#tableParametros tbody").html('');
        $("#tableParametrosRetorno tbody").html('');
        $("#form-webservice #wsCantHeaders").val(0);
        $("#form-webservice #wsCantParametros").val(0);
        $("#form-webservice #wsCantParametrosRetorno").val(0);

        $('#modal-webservice').modal('show');

    });

    $("#agregarHeaderWebservice").on('click', function(){
        agregarHeader('add');
    });

    $("#agregarParametrosWebservice").on('click', function(){
        agregarParametro('add');
    });

    $("#agregarParametrosRetornoWebservice").on('click', function(){
        agregarParametroRetorno('add');
    });

    $("#tableHeaders tbody").on('click', '.borrar-header', function(){
        let id = $(this).data('id');
        let accion = $(this).data('accion');
        borrarHeader(accion, id);
    });

    $("#tableParametros tbody").on('click', '.borrar-parametro', function(){
        let id = $(this).data('id');
        let accion = $(this).data('accion');
        borrarParametro(accion, id);
    });

    $("#tableParametrosRetorno tbody").on('click', '.borrar-parametro-retorno', function(){
        let id = $(this).data('id');
        let accion = $(this).data('accion');
        borrarParametroRetorno(accion, id);
    });

    $("#secWebservice table tbody").on('click', '.btn-eliminar-webservice', function(){
        let id = $(this).data('id');

        let token = $("input[name=_token]").val();

        Swal.fire({
            title: 'Deseas eliminar este webservice?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                $('#mostrar_loading').addClass('loader');
                $.ajax({
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    url: 'huesped/webservice-borrar/'+id,
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        $("#webservice_data_"+id).remove();
                        Swal.fire(
                            'Eliminado!',
                            'Su registro ha sido eliminado.',
                            'success'
                        )
                    },
                    error: function(response) {
                         toastr.error(response.responseJSON.message);
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                })

            }
        })
    });

    $("#btn-guardar-webservice").on('click', function(e){

        e.preventDefault();

        $('#mostrar_loading').addClass('loader');
        // $('#form-whatsapp .error_c').remove();

        var data = new FormData($('#form-webservice')[0]);
        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();

        $.ajax({
            url: 'huesped/webservice-save/'+id_huesped,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function(response){
                console.log(response);
                if(response.estado = 'ok'){
                    listarServices(id_huesped);
                    $("#form-webservice")[0].reset();
                    $('#modal-webservice').modal('hide');
                    toastr.success(response.mensaje);
                }else{
                    toastr.error(response.mensaje+' '+response.error);
                }
            },
            error: function(response){
                if(response.status === 422){
                    // La validacion de los datos lo hare a punta de js
                    toastr.info('Hubo un problema al validar tus datos');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al guardar los datos.');
                    }
                }
            },
            complete: function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });
    });

    $('#secWebservice table tbody').on('click', '.btn-editar-webservice', function(){

        let id = $(this).data('id');

        $("#form-webservice")[0].reset();
        $("#tableHeaders tbody").html('');
        $("#tableParametros tbody").html('');
        $("#tableParametrosRetorno tbody").html('');

        $("#form-webservice #wsAccion").val('edit');
        $("#form-webservice #wsCantHeaders").val(0);
        $("#form-webservice #wsCantParametros").val(0);
        $("#form-webservice #wsCantParametrosRetorno").val(0);
        $("#form-webservice #wsId").val(id);

        $('#modal-webservice').modal('show');

        // $('#form-troncal .error_c').remove();
        $('#mostrar_loading').addClass('loader');

        $.ajax({
            type: 'GET',
            url: 'huesped/webservice-data/'+id,
            success: function (response) {
                if(response.webservice){
                    $("#wsNombre").val(response.webservice.nombre);
                    $("#wsMetodo").val(response.webservice.metodo);
                    $("#wsUrl").val(response.webservice.url);
                    if(response.webservice.funcion_requerida != null){
                        $("#funcionRequerida").val(response.webservice.funcion_requerida);
                        $('#requiereInvocaciones').prop('checked',true);
                    }
                }

                if(response.headers){
                    $.each(response.headers, function(i, item){
                        agregarHeader('edit');

                        $("#wsCampo_"+i).val(item.id);
                        $("#wsHeaderNombre_"+i).val(item.nombre);
                        $("#wsHeaderValor_"+i).val(item.valor);
                        $("#wsHeaderDescripcion_"+i).val(item.descripcion);

                    });
                }

                if(response.parametros){
                    $.each(response.parametros, function(i, item){
                        agregarParametro('edit');

                        $("#wsParametro_"+i).val(item.id);
                        $("#wsParametroNombre_"+i).val(item.parametro);
                        $("#wsParametroTipo_"+i).val(item.tipo);

                    });
                }

                if(response.parametrosRetorno){
                    $.each(response.parametrosRetorno, function(i, item){
                        agregarParametroRetorno('edit');

                        $("#wsParametroRetorno_"+i).val(item.id);
                        $("#wsParametroRetornoNombre_"+i).val(item.parametro);
                        $("#wsParametroRetornoTipo_"+i).val(item.tipo);

                    });
                }
            },
            error: function(response) {
                toastr.error('Ha sucedido un error y no se ha podido traer la informacion');
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })
    });

    $('#requiereInvocaciones').on('click',function(){
        if($(this).is(':checked')){
            $('#funcionRequerida').attr('disabled',false);
        }else{
            $('#funcionRequerida').attr('disabled',true);
        }
    });

    function agregarHeader(accion){

        let cont = $("#wsCantHeaders").val();

        let row = `
            <tr id="row_header_${accion}_${cont}">
                <input type="hidden" id="wsCampo_${cont}" name="wsCampo_${accion}_${cont}" value="" num="${cont}">
                <td>
                    <input type="text" name="wsHeaderNombre_${accion}_${cont}" id="wsHeaderNombre_${cont}" class="form-control input-sm" placeholder="Key">
                </td>
                <td>
                    <input type="text" name="wsHeaderValor_${accion}_${cont}" id="wsHeaderValor_${cont}" class="form-control input-sm" placeholder="Value">
                </td>
                <td>
                    <input type="text" name="wsHeaderDescripcion_${accion}_${cont}" id="wsHeaderDescripcion_${cont}" class="form-control input-sm" placeholder="Descripcion">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm borrar-header" data-id="${cont}" data-accion="${accion}"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;

        cont++;

        $("#tableHeaders tbody").append(row);
        $("#wsCantHeaders").val(cont);
    }

    function agregarParametro(accion){

        let cont = $("#wsCantParametros").val();

        let row = `
            <tr id="row_parametro_${accion}_${cont}">
                <input type="hidden" id="wsParametro_${cont}" name="wsParametro_${accion}_${cont}" value="" num="${cont}">
                <td>
                    <input type="text" name="wsParametroNombre_${accion}_${cont}" id="wsParametroNombre_${cont}" class="form-control input-sm" placeholder="Key">
                </td>
                <td>
                    <select name="wsParametroTipo_${accion}_${cont}" id="wsParametroTipo_${cont}" class="form-control input-sm tipo-parametro" num="${cont}">
                        <option value="texto">Texto</option>
                        <option value="numero">Numero</option>
                        <option value="booleano">Booleano</option>
                        <option value="objeto">Objeto</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm borrar-parametro" data-id="${cont}" data-accion="${accion}"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;

        cont++;

        $("#tableParametros tbody").append(row);
        $("#wsCantParametros").val(cont);
    }

    function agregarParametroRetorno(accion){
        let cont = $("#wsCantParametrosRetorno").val();

        let row = `
            <tr id="row_parametro_retorno_${accion}_${cont}">
                <input type="hidden" id="wsParametroRetorno_${cont}" name="wsParametroRetorno_${accion}_${cont}" value="" num="${cont}">
                <td>
                    <input type="text" name="wsParametroRetornoNombre_${accion}_${cont}" id="wsParametroRetornoNombre_${cont}" class="form-control input-sm" placeholder="Key">
                </td>
                <td>
                    <select name="wsParametroRetornoTipo_${accion}_${cont}" id="wsParametroRetornoTipo_${cont}" class="form-control input-sm tipo-parametro" num="${cont}">
                        <option value="texto">Texto</option>
                        <option value="numero">Numero</option>
                        <option value="booleano">Booleano</option>
                        <option value="objeto">Objeto</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm borrar-parametro-retorno" data-id="${cont}" data-accion="${accion}"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;

        cont++;

        $("#tableParametrosRetorno tbody").append(row);
        $("#wsCantParametrosRetorno").val(cont);
    }

    function borrarHeader(accion, id){
        if(accion == 'add'){
            $("#row_header_add_"+id).remove();
        }else{

            let headerId = $("#wsCampo_"+id).val();

            let token = $("input[name=_token]").val();

            Swal.fire({
                title: 'Deseas eliminar este header?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if (result.value) {
                    $('#mostrar_loading').addClass('loader');
                    $.ajax({
                        headers: {'X-CSRF-TOKEN':token},
                        type: 'POST',
                        url: 'huesped/webservice-borrar-header/'+headerId,
                        data: {
                            "id": headerId
                        },
                        success: function (response) {
                            $("#row_header_edit_"+id).remove();
                            Swal.fire(
                                'Eliminado!',
                                'Su registro ha sido eliminado.',
                                'success'
                            )
                        },
                        error: function(response) {
                            toastr.error(response.responseJSON.message);
                        },
                        complete:function(){
                            $('#mostrar_loading').removeClass('loader');
                        }
                    })

                }
            })
        }
    }

    function borrarParametro(accion, id){
        if(accion == 'add'){
            $("#row_parametro_add_"+id).remove();
        }else{

            let parametroId = $("#wsParametro_"+id).val();

            let token = $("input[name=_token]").val();

            Swal.fire({
                title: 'Deseas eliminar este parametro?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if (result.value) {
                    $('#mostrar_loading').addClass('loader');
                    $.ajax({
                        headers: {'X-CSRF-TOKEN':token},
                        type: 'POST',
                        url: 'huesped/webservice-borrar-parametro/'+parametroId,
                        data: {
                            "id": parametroId
                        },
                        success: function (response) {
                            $("#row_parametro_edit_"+id).remove();
                            Swal.fire(
                                'Eliminado!',
                                'Su registro ha sido eliminado.',
                                'success'
                            )
                        },
                        error: function(response) {
                            toastr.error(response.responseJSON.message);
                        },
                        complete:function(){
                            $('#mostrar_loading').removeClass('loader');
                        }
                    })

                }
            })
        }
    }

    function borrarParametroRetorno(accion, id){
        if(accion == 'add'){
            $("#row_parametro_retorno_add_"+id).remove();
        }else{

            let parametroId = $("#wsParametroRetorno_"+id).val();

            let token = $("input[name=_token]").val();

            Swal.fire({
                title: 'Deseas eliminar este parametro?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if (result.value) {
                    $('#mostrar_loading').addClass('loader');
                    $.ajax({
                        headers: {'X-CSRF-TOKEN':token},
                        type: 'POST',
                        url: 'huesped/webservice-borrar-parametro-retorno/'+parametroId,
                        data: {
                            "id": parametroId
                        },
                        success: function (response) {
                            $("#row_parametro_retorno_edit_"+id).remove();
                            Swal.fire(
                                'Eliminado!',
                                'Su registro ha sido eliminado.',
                                'success'
                            )
                        },
                        error: function(response) {
                            toastr.error(response.responseJSON.message);
                        },
                        complete:function(){
                            $('#mostrar_loading').removeClass('loader');
                        }
                    })

                }
            })
        }
    }

    function listarServices(huespedId){

        $("#secWebservice table tbody").html('');

        $.ajax({
            type: 'GET',
            url: 'huesped/webservice/'+huespedId,
            success: function (response) {
                console.log(response);
                let html11 = '';
                if(response.webservices){
                    $.each(response.webservices, function(key, value){
                        html11 += `
                            <tr id="webservice_data_${value.id}">
                                <td>${value.nombre}</td>
                                <td>${value.metodo}</td>
                                <td>${value.url}</td>
                                <td>
                                    <button data-id="${value.id}" class="btn btn-primary btn-sm btn-editar-webservice" title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button data-id="${value.id}" class="btn btn-danger btn-sm btn-eliminar-webservice" title="Eliminar">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }else{
                    html11 = `
                    <tr>
                        <td colspan="8"><h4>El huésped no tiene webservices creados</h4></td>
                    </tr>
                    `;
                }

                $("#secWebservice table tbody").html(html11);
            },
            error: function(response) {
                toastr.error("No se pudo obtener la lista de webservices");
            },
            complete: function(){
                //
            }
        })
    }

    /**---------------Seccion Plantillas whatsapp------------------------------- */

    $("#pPlantillaId").attr('disabled', true);

    // Cambio tipo de registro de plantilla de gupshup
    $("#pAccionPlantilla").on('change', function() {
        if($(this).val() == 'registrarNuevo'){
            $("#pPlantillaId").attr('disabled', true);
        }else{
            $("#pPlantillaId").attr('disabled', false);
        }
    });

    // Reemplaza los espacios por _
    $("#pNombre").on('keyup', function(){
        let contenido = $("#pNombre").val();
        contenido = contenido.replace(" ", "_");
        $("#pNombre").val(contenido.toLowerCase());
    });

    $("#pCuenta").on('change', function(){
        let proveedor = $(this).find("option:selected").attr('data-proveedor');

        let cuerpoOpciones = '<option value="registrarNuevo">Crear plantilla desde cero</option><option value="registrarExistente">Registrar plantilla creada desde la plataforma de gupshup</option>';

        let cuerpoCategorias = `
            <option value="ACCOUNT_UPDATE">Actualizacion de cuenta</option>
            <option value="PAYMENT_UPDATE">Actualizacion de pago</option>
            <option value="PERSONAL_FINANCE_UPDATE">Actualizacion de finanzas personales</option>
            <option value="SHIPPING_UPDATE">Actualizacion de envio</option>
            <option value="RESERVATION_UPDATE">Actualizacion de reservas</option>
            <option value="ISSUE_RESOLUTION">Solucion de problemas</option>
            <option value="APPOINTMENT_UPDATE">Actualizacion de cita</option>
            <option value="TRANSPORTATION_UPDATE">Actualizazion sobre transporte</option>
            <option value="TICKET_UPDATE">Actualizacion sobre entradas</option>
            <option value="ALERT_UPDATE">Actualizacion sobre alertas</option>
            <option value="AUTO_REPLY">Auto-reply</option>
        `;

        if(proveedor == 'infobip'){
            
            cuerpoOpciones = '<option value="registrarExistente">Registrar plantilla creada desde la plataforma de infobip</option>';
            cuerpoCategorias = '<option value="MARKETING">MARKETING</option><option value="TRANSACTIONAL">TRANSACTIONAL</option><option value="OTP">OTP</option>';

            $("#pPlantillaId").attr('disabled', true);
        }

        $("#pAccionPlantilla").html(cuerpoOpciones);
        $("#pCategoria").html(cuerpoCategorias);
        
    });

    // Evento para elegir el tipo de cabecera
    $("#pTipo").on('change', function(){
        let tipo = $(this).val();

        // Apago todo
        $("#cabeceraTexto").hide();
        $("#cabeceraMedia").hide();

        if(tipo == 'TEXT'){
            $("#cabeceraTexto").show();
        }
        if(tipo == 'IMAGE' || tipo == 'VIDEO'|| tipo == 'DOCUMENT'){
            $("#cabeceraMedia").show();
        }
    });

    // Evento para elegir el boton
    $("#pUsarBotones").on('change', function(){
        let tipoBoton = $(this).val();
        $("#contenidoBoton").html('');
        $("#agregarBotonPlantillaW").attr('disabled', false);

        if(tipoBoton == 'ninguno'){
            $("#agregarBotonPlantillaW").hide();
        }
        if(tipoBoton == 'llamada_a_la_accion'){
            $("#agregarBotonPlantillaW").show();
        }
        if(tipoBoton == 'respuesta_rapida'){
            $("#agregarBotonPlantillaW").show();
        }
    });

    var contador = 0;

    // Evento del boton de agregar boton de plantilla
    $("#agregarBotonPlantillaW").on('click', function(){

        let cantBotones = document.getElementsByClassName("boton-plantilla").length;
        let tipoBoton = $("#pUsarBotones").val();

        let row = '';

        if(tipoBoton == 'llamada_a_la_accion'){
            row = `
                <div class="row boton-plantilla" id="row_botonPlantilla_${contador}" num="${contador}">
                    <div class="col-md-3">
                        <label>Tipo de accion</label>
                        <select name="pBotonTipoAccion[]" id="pBotonTipoAccion${contador}" class="form-control input-sm tipo-boton-plantilla" num="${contador}">
                            <option value="telefono">Llamar al número de telefono</option>
                            <option value="sitioWeb">Ir al sitio web</option>
                        </select>
                    </div>
                    <div class="col-md-3" style="padding-left: 0px">
                        <label>Texto del botón</label>
                        <input name="pTextoBoton[]" id="pTextoBoton${contador}" class="form-control input-sm" maxlength="25" placeholder="Max 25 caracteres">
                    </div>
                    <div class="col-md-2 boton-tipo-telefono" style="padding-left: 0px; display:none;">
                        <label>Codigo de país</label>
                        <input name="pCodPais[]" id="pCodPais${contador}" class="form-control input-sm" maxlength="10">
                    </div>
                    <div class="col-md-3 boton-tipo-telefono" style="padding-left: 0px; display:none;">
                        <label>Teléfono</label>
                        <input name="pNumTelefono[]" id="pNumTelefono${contador}" class="form-control input-sm" maxlength="20">
                    </div>
                    <div class="col-md-2 boton-tipo-sitioWeb" style="padding-left: 0px; display:none;">
                        <label>Tipo de URL</label>
                        <select name="pTipoUrl" id="pTipoUrl${contador}" class="form-control input-sm tipo-url-boton" num="${contador}">
                            <option value="static">Estatica</option>
                            <option value="dynamic">Dinamica</option>
                        </select>
                    </div>
                    <div class="col-md-3 boton-tipo-sitioWeb" style="padding-left: 0px; display:none;">
                        <label>URL del sitio web</label>
                        <input name="pUrl[]" id="pUrl${contador}" class="form-control input-sm" maxlength="255">
                        <span id="urlDinamica${contador}" style="display:none">Variables se agregan en este formato {{1}}</span>
                    </div>
                    <div class="col-md-1" style="padding-left: 0px">
                        <label>Accion</label>
                        <button type="button" class="btn btn-danger btn-sm borrar-boton-plantilla" data-id="${contador}"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
        }else{
            row = `
                <div class="row boton-plantilla" id="row_botonPlantilla_${contador}" num="${contador}">
                    <div class="col-md-6">
                        <input name="pBotonRespuestaRapida[]" id="pBotonRespuestaRapida${contador}" style="width: 200px; margin-right: 10px;" class="form-control input-sm" maxlength="25" placeholder="Texto del botón">
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger btn-sm borrar-boton-plantilla" data-id="${contador}"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
        }

        $("#contenidoBoton").append(row);
        let contadorAnterior = contador;
        contador++;
        cantBotones++;

        // Desabilito la el tipo de boton cuando ya hay una seccion agregada
        if(tipoBoton == 'llamada_a_la_accion' && cantBotones == 2){
            // Traigo el valor del primer boton
            let primerBotonId = $(".boton-plantilla").first().attr('num');
            let tipoPrimerBoton = $("#pBotonTipoAccion"+primerBotonId).val();

            if(tipoPrimerBoton == 'telefono'){
                $('#pBotonTipoAccion'+primerBotonId+' option[value="sitioWeb"]').attr('disabled','disabled');
                $('#pBotonTipoAccion'+contadorAnterior+' option[value="telefono"]').attr('disabled','disabled');
                $('#pBotonTipoAccion'+contadorAnterior).val('sitioWeb').change();
            }else{
                $('#pBotonTipoAccion'+primerBotonId+' option[value="telefono"]').first().attr('disabled','disabled');
                $('#pBotonTipoAccion'+contadorAnterior+' option[value="sitioWeb"]').attr('disabled','disabled');
                $('#pBotonTipoAccion'+contadorAnterior).val('telefono').change();
            }
        }else{
            $('#pBotonTipoAccion'+contadorAnterior).change();
        }

        if( (tipoBoton == 'llamada_a_la_accion' && cantBotones >= 2) || (tipoBoton == 'respuesta_rapida' && cantBotones >= 3) ){
            $("#agregarBotonPlantillaW").attr('disabled', true);
        }

    });

    $("#contenidoBoton").on('click', '.borrar-boton-plantilla', function(){
        let id = $(this).data('id');
        borrarBotonPlantilla(id);
    });

    // Evento cuando cambien el tipo de accion
    $("#contenidoBoton").on('change', '.tipo-accion', function(){

        let tipoAccion = $(this).val();

        alert("hola"+tipoAccion);
    });

    // Evento del boton de tipo de accion
    $("#contenidoBoton").on('change', '.tipo-boton-plantilla', function(){
        let tipoBotonPlantilla = $(this).val();
        let rowId = $(this).attr('num');

        if(tipoBotonPlantilla == 'telefono'){
            $("#row_botonPlantilla_"+rowId+" .boton-tipo-telefono").show();
            $("#row_botonPlantilla_"+rowId+" .boton-tipo-sitioWeb").hide();
        }else{
            $("#row_botonPlantilla_"+rowId+" .boton-tipo-sitioWeb").show();
            $("#row_botonPlantilla_"+rowId+" .boton-tipo-telefono").hide();
        }

    });

    // Evento para cambiar el tipo de url de un boton
    $("#contenidoBoton").on('change', '.tipo-url-boton', function(){
        let valor = $(this).val();
        let id = $(this).attr('num');

        if(valor == 'static'){
            $("#urlDinamica"+id).hide();
        }else{
            $("#urlDinamica"+id).show();
        }
    });

    // Borrar el boton de la plantilla
    function borrarBotonPlantilla(id){
        $("#row_botonPlantilla_"+id).remove();
        $("#agregarBotonPlantillaW").attr('disabled', false);

        $('.tipo-boton-plantilla option[value="telefono"]').attr('disabled', false);
        $('.tipo-boton-plantilla option[value="sitioWeb"]').attr('disabled', false);
    }

    // Evento click de agregar nueva plantilla
    $("#btn-crear-plantilla-whatsapp").on('click', function(){
        agregarPlantilla();
    });

    // Agregar parametros a la plantilla
    $("#agregarParametroPlantilla").on('click', function(){
        agregarParametroPlantilla('add');
    });

    // Evento para borrar parametro de la plantilla
    $("#tablePlantillaParametros tbody").on('click', '.borrar-parametro-plantilla', function(){
        let id = $(this).data('id');
        let accion = $(this).data('accion');
        borrarParametroPlantilla(accion, id);
    });

    // Ejecucion del evento de guardado de plantillas
    $("#btn-guardar-plantilla-whatsapp").on('click', function(e){

        e.preventDefault();

        let valido = true;

        // Campos obligatorios
        if($("#pCuenta").val() == ''){
            valido = false;
            toastr.error('No has seleccionado ninguna cuenta de whatsapp');
            $("#pCuenta").focus();
            return;
        }

        if($("#pNombre").val() == ''){
            valido = false;
            toastr.error('El campo nombre no puede estar vacio');
            $("#pNombre").focus();
            return;
        }

        if($("#pEtiqueta").val() == ''){
            valido = false;
            toastr.error('El campo etiquetas no puede estar vacio');
            $("#pEtiqueta").focus();
            return;
        }

        if($("#pContenido").val() == ''){
            valido = false;
            toastr.error('El campo texto no puede estar vacio');
            $("#pContenido").focus();
            return;
        }

        if($("#pContenidoEjemplo").val() == ''){
            valido = false;
            toastr.error('El campo ejemplo de mensaje no puede estar vacio');
            $("#pContenidoEjemplo").focus();
            return;
        }

        $('#mostrar_loading').addClass('loader');
        // $('#form-whatsapp .error_c').remove();

        var data = new FormData($('#formPlantillaWhatsapp')[0]);
        data.append("pProveedor", $("#pCuenta").find("option:selected").attr('data-proveedor'));

        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();

        if(valido){
            $.ajax({
                url: 'huesped/plantilla-w-save/'+id_huesped,
                headers: {'X-CSRF-TOKEN':token},
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: data,
                success: function(response){
                    console.log(response);
                    if(response.estado == 'ok'){
                        $("#formPlantillaWhatsapp")[0].reset();
                        $("#tablePlantillaParametros body").html('');
                        $('#modalPlantillaWhatsapp').modal('hide');
                        toastr.success(response.mensaje);

                        // Agrego la plantilla creada a la ultima fila

                        let row = `<tr id="plantilla_wa_data_${response.plantilla.id}">
                            <td>${response.plantilla.nombre}</td>
                            <td>${response.plantilla.nombreCuenta}</td>
                            <td>${response.plantilla.canal}</td>
                            <td>${response.plantilla.estado}</td>
                            <td>
                                <button data-id="${response.plantilla.id}" data-huesped="${id_huesped}" class="btn btn-info btn-sm btn-reload-plantilla-wa" title="Reload">
                                    <i class="fa fa-repeat"></i>
                                </button>
                                <button data-id="${response.plantilla.id}" class="btn btn-primary btn-sm btn-show-plantilla-wa" title="show">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button data-id="${response.plantilla.id}" data-canal="${response.plantilla.canal}" class="btn btn-danger btn-sm btn-eliminar-plantilla-wa" title="Elimina plantilla unicamente para el operador gupshup">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;

                        $("#secPlantillasWhatsapp table tbody").append(row);

                    }else{
                        toastr.error(response.mensaje+' '+response.error);
                    }
                },
                error: function(response){
                    if(response.status === 422){
                        // La validacion de los datos lo hare a punta de js
                        toastr.info('Hubo un problema al validar tus datos');
                    }else{
                        if(response.responseJSON.message){
                            toastr.error(response.responseJSON.message);
                        }else{
                            toastr.error('Se ha presentado un error al guardar los datos.');
                        }
                    }
                },
                complete: function(){
                    $('#mostrar_loading').removeClass('loader');
                }
            });
        }
    });

    // Esto lo que hace es que abre la modal de plantillas
    function agregarPlantilla(){

        $("#formPlantillaWhatsapp")[0].reset();
        $('#modalPlantillaWhatsapp .modal-title').text("Registrar plantilla");
        $("#formPlantillaWhatsapp #pAccion").val('add');
        $('#formPlantillaWhatsapp .error_c').remove();
        $('#modalPlantillaWhatsapp').modal('show');

        $("#cabeceraTexto").show();
        $("#cabeceraMedia").hide();

        $("#tablePlantillaParametros tbody").html('');

        $("#formPlantillaWhatsapp input").attr('disabled', false);
        $("#formPlantillaWhatsapp select").attr('disabled', false);
        $("#formPlantillaWhatsapp textarea").attr('disabled', false);

        $("#pAccionPlantilla").val("registrarNuevo").change();
        $("#pPlantillaId").attr('disabled', true);

        $("#btn-guardar-plantilla-whatsapp").show();
        $("#agregarParametroPlantilla").show();

        $("#contenidoBoton").html('');

        let id = $("#form-huesped #id").val();

        $.ajax({
            type: 'GET',
            url: 'huesped/whatsapp-all/'+id,
            success: function (response) {
                let htmlw = '<option value="">Seleccione</option>';
                if(response.res.channels.length > 0){
                    $.each(response.res.channels, function(key, value){
                        htmlw += `<option value="${value.id}" data-proveedor="${value.proveedor}">${value.nombre} - ${value.cuenta}, ${value.proveedor.toUpperCase()}</option>`;
                    });
                }

                $('#pCuenta').html(htmlw);
            },
            error: function(response) {
                toastr.error("No se pudo obtener el registro de los canales de whatsapp");
            }
        });
    }

    function agregarParametroPlantilla(accion){
        let cont = $("#contParametrosPlnatilla").val();

        let row = `
            <tr id="row_p_parametro_${accion}_${cont}">
                <input type="hidden" id="pParametro_${cont}" name="pParametro_${accion}_${cont}" value="" num="${cont}">
                <td>
                    <input type="text" name="pParametroNombre_${accion}_${cont}" id="pParametroNombre_${cont}" class="form-control input-sm" placeholder="Nombre">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm borrar-parametro-plantilla" data-id="${cont}" data-accion="${accion}"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;

        cont++;

        $("#tablePlantillaParametros tbody").append(row);
        $("#contParametrosPlnatilla").val(cont);
    }

    function borrarParametroPlantilla(accion, id){
        if(accion == 'add'){
            $("#row_p_parametro_add_"+id).remove();
        }else{

            let parametroId = $("#pParametro_"+id).val();

            let token = $("input[name=_token]").val();

            Swal.fire({
                title: 'Deseas eliminar esta variable?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if (result.value) {
                    $('#mostrar_loading').addClass('loader');
                    $.ajax({
                        headers: {'X-CSRF-TOKEN':token},
                        type: 'POST',
                        url: 'huesped/plantillas-whatsapp-borrar-parametro/'+parametroId,
                        data: {
                            "id": parametroId
                        },
                        success: function (response) {
                            $("#row_p_parametro_edit_"+id).remove();
                            Swal.fire(
                                'Eliminado!',
                                'Su registro ha sido eliminado.',
                                'success'
                            )
                        },
                        error: function(response) {
                             toastr.error(response.responseJSON.message);
                        },
                        complete:function(){
                            $('#mostrar_loading').removeClass('loader');
                        }
                    })

                }
            })
        }
    }

    function cargarListaPlantillas(id, cargadoDesdeBoton = false){

        if(!cargadoDesdeBoton){
            $("#secPlantillasWhatsapp table tbody").html('');
        }

        $.ajax({
            type: 'GET',
            url: 'huesped/plantillas-wa/'+id,
            beforeSend: function(){
                $('#mostrar_loading').addClass('loader');
            },
            success: function (response) {
                if(cargadoDesdeBoton){
                    $("#secPlantillasWhatsapp table tbody").html('');
                }
                let html11 = '';
                if(response.plantillas){
                    $.each(response.plantillas, function(key, value){
                        html11 += `
                            <tr id="plantilla_wa_data_${value.id}">
                                <td>${value.nombre}</td>
                                <td>${value.nombreCuenta}</td>
                                <td>${value.canal}</td>
                                <td>${value.estado}</td>
                                <td>
                                    <button data-id="${value.id}" data-huesped="${id}" class="btn btn-info btn-sm btn-reload-plantilla-wa" title="Reload">
                                        <i class="fa fa-repeat"></i>
                                    </button>
                                    <button data-id="${value.id}" class="btn btn-primary btn-sm btn-show-plantilla-wa" title="Show">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button data-id="${value.id}" data-canal="${value.canal}" class="btn btn-danger btn-sm btn-eliminar-plantilla-wa" title="Elimina plantilla unicamente para el operador gupshup">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }else{
                    html11 = `
                    <tr>
                        <td colspan="8"><h4>El huésped no tiene plantillas creadas</h4></td>
                    </tr>
                    `;
                }

                $("#secPlantillasWhatsapp table tbody").html(html11);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener la lista de plantillas");
            },
            complete: function(){
                if(cargadoDesdeBoton){
                    $('#mostrar_loading').removeClass('loader');
                }
            }
        })
    }

    $('#secPlantillasWhatsapp table tbody').on('click', '.btn-reload-plantilla-wa', function(){
        let huespedId = $(this).data('huesped');
        cargarListaPlantillas(huespedId, true);
    });

    $('#secPlantillasWhatsapp table tbody').on('click', '.btn-show-plantilla-wa', function(){

        let id = $(this).data('id');

        $("#formPlantillaWhatsapp")[0].reset();
        $("#tablePlantillaParametros tbody").html('');

        $("#formPlantillaWhatsapp #pAccion").val('edit');
        $("#formPlantillaWhatsapp #contParametrosPlnatilla").val(0);
        $("#formPlantillaWhatsapp #plantillaId").val(id);

        $('#modalPlantillaWhatsapp').modal('show');

        $("#btn-guardar-plantilla-whatsapp").hide();
        $("#agregarParametroPlantilla").hide();

        $("#contenidoBoton").html('');

        // $('#form-troncal .error_c').remove();
        $('#mostrar_loading').addClass('loader');

        let huespedId = $("#form-huesped #id").val();

        $.ajax({
            type: 'GET',
            url: 'huesped/whatsapp-all/'+huespedId,
            success: function (response) {
                let htmlw = '<option value="">Seleccione</option>';
                if(response.res.channels.length > 0){
                    $.each(response.res.channels, function(key, value){
                        htmlw += `<option value="${value.id}">${value.nombre} - ${value.cuenta}</option>`;
                    });
                }

                $('#pCuenta').html(htmlw);
            },
            error: function(response) {
                toastr.error("No se pudo obtener el registro de los canales de whatsapp");
            }
        });

        $.ajax({
            type: 'GET',
            url: 'huesped/plantilla-wa-data/'+id,
            success: function (response) {
                if(response.plantilla){

                    let plantilla = response.plantilla;

                    $("#pCuenta").val(plantilla.id_canal_whatsapp);
                    $("#pPlantillaId").val(plantilla.id_plantilla_facebook);

                    $("#pNombre").val(plantilla.nombre);
                    $("#pCategoria").val(plantilla.categoria);
                    $("#pEtiqueta").val(plantilla.etiqueta);
                    $("#pIdioma").val(plantilla.idioma);

                    $("#pTipo").val(plantilla.tipo).change();
                    $("#pCabeceraTexto").val(plantilla.cabecera);

                    $("#pContenido").val(plantilla.texto);
                    $("#pFooterTexto").val(plantilla.pie_pagina);
                    $("#pContenidoEjemplo").val(plantilla.texto_ejemplo);

                }

                if(response.parametros){
                    $.each(response.parametros, function(i, item){
                        agregarParametroPlantilla('edit');

                        $("#pParametro_"+i).val(item.id);
                        $("#pParametroNombre_"+i).val(item.nombre);
                        $("#pParametroNombre_"+i).attr('disabled', true);

                        $(".borrar-parametro-plantilla").hide();

                    });
                }
            },
            error: function(response) {
                toastr.error('Ha sucedido un error y no se ha podido traer la informacion');
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })

        $("#formPlantillaWhatsapp input").attr('disabled', true);
        $("#formPlantillaWhatsapp select").attr('disabled', true);
        $("#formPlantillaWhatsapp textarea").attr('disabled', true);
    });

    $('#secPlantillasWhatsapp table tbody').on('click', '.btn-eliminar-plantilla-wa', function(){

        let plantillaId = $(this).attr('data-id');
        let canal = $(this).attr('data-canal');

        let token = $("input[name=_token]").val();

        Swal.fire({
            title: 'Eliminar esta plantilla de whatsapp?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                $('#mostrar_loading').addClass('loader');
                $.ajax({
                    url: 'huesped/plantilla-wa-delete/'+plantillaId,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'DELETE',
                    dataType: 'json',
                    data: {plantillaId: plantillaId, canal: canal},
                    success: function (response) {
                        $("#plantilla_wa_data_"+plantillaId).remove();
                        Swal.fire(
                            'Eliminado!',
                            'Su registro ha sido eliminado.',
                            'success'
                        )
                    },
                    error: function(response) {
                         toastr.error(response.responseJSON.message);
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                })

            }
        })
    });

    /**--------------Seccion Whatsapp------------- */
    $("#modal-whatsapp #wProveedor").change(function(){

        $("#provDefault").css("display", "none");
        $("#provBotmaker").css("display", "none");
        $("#provIatech").css("display", "none");
        $("#provGupshup").css("display", "none");

        if($(this).val() == 'botmaker'){
            $("#provBotmaker").css("display", "block");
        }else if($(this).val() == 'iatech'){
            $("#provIatech").css("display", "block");
        }else if($(this).val() == 'hibot'){
            $("#provDefault").css("display", "block");
        }else if($(this).val() == 'gupshup'){
            $("#provGupshup").css("display", "block");
        }
    });

    $('#btn-crear-whatsapp').click(function(){
        $("#form-whatsapp")[0].reset();
        $('#modal-whatsapp .modal-title').text("Registrar cuenta de whatsapp");
        $("#form-whatsapp #accion").val('registrar');
        $('#form-whatsapp .error_c').remove();
        $('#modal-whatsapp').modal('show');

        $("#modal-whatsapp #wProveedor").change();
    });

    function getChannelsW(id){
        $.ajax({
            type: 'GET',
            url: 'huesped/whatsapp-all/'+id,
            beforeSend: function(){
                $('#secWhatsapp table tbody').html(`
                    <tr>
                        <td colspan="5"><h4>Cargando...</h4></td>
                    </tr>
                `
                );
            },
            success: function (response) {
                let htmlw = '';
                if(response.res.channels.length > 0){
                    $.each(response.res.channels, function(key, value){
                        htmlw += `
                            <tr>
                                <td>${value.nombre}</td>
                                <td>${value.cuenta}</td>
                                <td>${value.proveedor}</td>
                                <td>
                                    ${(value.activo == 1) ? '<span class="label label-success">Activo</span>': '<span class="label label-danger">Desactivado</span>'}
                                    ${(value.id_cfg_chat > 0) ? '<span class="label label-success">Asignado a campaña</span>': '<span class="label label-warning">sin asignar a campaña</span>'}
                                </td>
                                <td>
                                    <button data-id="${value.id}" type="button" class="btn btn-primary btn-sm btn-editar-whatsapp" title="Editar"><i class="fa fa-edit"></i></button>
                                    <button data-id="${value.id}" type="button" class="btn btn-danger btn-sm btn-eliminar-whatsapp" title="Eliminar"><i class="fa fa-trash"></i></button>
                                    <button data-id="${value.id}" data-cuenta="${value.cuenta}" type="button" class="btn btn-info btn-sm btn-test-whatsapp" title="Test">Prueba</button>
                                </td>
                            </tr>
                        `;
                    });
                }else{
                    htmlw = `
                    <tr>
                        <td colspan="5"><h4>El huésped aún no tiene canales registrados</h4></td>
                    </tr>
                    `;
                }

                $('#secWhatsapp table tbody').html(htmlw);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener el registro de los canales de whatsapp");
            }
        })
    }

    $("#wNumero").on('keyup', function(){
        let num = $(this).val();
        let prov = $("#wProveedor").val();

        $("#webhook").val(`https://middleware.dyalogo.cloud:3001/dymdw/api/whatsapp/msgin/${prov}/${num}`);
    });

    $("#wProveedor").on('change', function(){
        let num = $("#wNumero").val();
        let prov = $("#wProveedor").val();

        $("#webhook").val(`https://middleware.dyalogo.cloud:3001/dymdw/api/whatsapp/msgin/${prov}/${num}`);
    });

    /** Este evento abre la modal y trae el registro de la troncal para poderla editar */
    $('#secWhatsapp table').on('click', '.btn-editar-whatsapp', function(){

        $("#form-whatsapp")[0].reset();
        $('#modal-whatsapp .modal-title').text("Editar whatsapp");
        $("#form-whatsapp #accion").val('actualizar');
        $('#modal-whatsapp').modal('show');

        $('#form-whatsapp .error_c').remove();
        $('#mostrar_loading').addClass('loader');

        var id = $(this).data('id');

        $.ajax({
            type: 'GET',
            url: 'huesped/whatsapp/'+id,
            success: function (response) {

                let data = response.res.channel;
                let formatFecha = new Date(data.fecha_vencimiento).toJSON().slice(0,19);

                if(data.activo === 1){
                    $("#wActivo").attr('checked', true);
                }else{
                    $("#wActivo").attr('checked', false);
                }

                $("#id_whatsapp").val(data.idcuenta);
                $("#wNombre").val(data.nombre);
                $("#wNumero").val(data.cuenta).keyup();
                $("#wCanal").val(data.channelid);
                $("#wProveedor").val(data.proveedor_c);
                $("#wAppid").val(data.app_id);
                $("#wAppsecret").val(data.app_secret);
                $("#wToken").val(data.token);
                $("#wVencimiento").val(formatFecha);

                if(data.proveedor_c == 'botmaker'){
                    $("#p1ClienteId").val(data.app_id);
                    $("#p1SecretId").val(data.app_secret);
                    $("#p1Token").val(data.token);
                }else if(data.proveedor_c == 'iatech'){
                    $("#p2Usuario").val(data.app_id);
                    $("#p2Contrasena").val(data.app_secret);
                    $("#p2EscId").val(data.channelid);
                }else if(data.proveedor_c == 'gupshup'){
                    $("#p3AppName").val(data.channelid);
                    $("#p3ApiKey").val(data.token);
                    $("#p3AppId").val(data.app_id);
                    $("#p3Token").val(data.app_secret);
                }

                $("#modal-whatsapp #wProveedor").change();

            },
            error: function(response) {
                 toastr.error(response.message);
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })
    });

     /** Guardar el registro de l formulario de troncal strore y update */
     $('#modal-whatsapp #btn-guardar-whatsapp').on('click', function(e){
        e.preventDefault();

        $('#mostrar_loading').addClass('loader');
        $('#form-whatsapp .error_c').remove();

        var data = new FormData($('#form-whatsapp')[0]);
        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();
        var accion = $('#form-whatsapp #accion').val();

        switch (accion) {
            case 'registrar':
                $.ajax({
                    url: 'huesped/crear-whatsapp/'+id_huesped,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){
                        getChannelsW(id_huesped);
                        toastr.success(response.message);
                        $("#form-whatsapp")[0].reset();
                        $('#modal-whatsapp').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-whatsapp [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-whatsapp [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                                toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                        }
                    },
                    complete: function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;

            case 'actualizar':
                data.append('_method', 'PUT');
                var id_whatsapp = $('#form-whatsapp #id_whatsapp').val();

                $.ajax({
                    url: 'huesped/actualizar-whatsapp/'+id_whatsapp,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){
                        getChannelsW(id_huesped);
                        toastr.success(response.message);
                        $('#modal-whatsapp').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-whatsapp [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-whatsapp [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                               toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                         }
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;
            default:
                break;
        }
    });

    // Este evento abre la modal para realizar la prueba de whatsapp
    $('#secWhatsapp table').on('click', '.btn-test-whatsapp', function(){

        $("#form-test-whatsapp")[0].reset();

        var id = $(this).data('id');
        var numW = $(this).data('cuenta');

        $("#logTestWhatsapp").html('');
        $("#idTestWhatsapp").val(id);
        $("#cuentaTestWhatsapp").val(numW);
        $("#test-texto1").html(`1. Al iniciar la prueba debes enviar un mensaje de whatsapp al numero ${numW} para capturar el mensaje`);
        $('#modal-test-whatsapp').modal('show');

    });

    $("#secWhatsapp table").on('click', '.btn-eliminar-whatsapp', function(){
        let delId = $(this).data('id');
        let id_huesped = $('input#id').val();
        var token = $("input[name=_token]").val();

        Swal.fire({
            title: 'Deseas eliminar este canal de whatsapp?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                $('#mostrar_loading').addClass('loader');
                $.ajax({
                    url: 'huesped/eliminar-whatsapp/'+delId,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'DELETE',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        getChannelsW(id_huesped);
                        Swal.fire(
                            'Eliminado!',
                            'Su registro ha sido eliminado.',
                            'success'
                        )
                    },
                    error: function(response) {
                         toastr.error(response.responseJSON.message);
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                })

            }
        })
    });

    // Inicia la prueba
    var carga = 0;

    $("#modal-test-whatsapp #test-w-iniciar").on('click', function(){
        $(this).prop('disabled', true);
        $("#modal-test-whatsapp #test-w-parar").prop('disabled', false);
        $("#logTestWhatsapp").html('');
        $("#logTestWhatsapp").append("INICIANDO PRUEBA \n");
        test(true);
        let idChannel = $("#idTestWhatsapp").val();
        let cont = 0;

        $("#logTestWhatsapp").append("Buscando.... \n");
        carga = setInterval(function(){
            cont+=1;
            console.log(cont, ' Intentos');

            traerMensajesW(idChannel)

            if(cont > 5){
                pararTestW(carga);
            }
        },10000);
    });

    $("#modal-test-whatsapp #btnTestSalida").on('click', function(){
        let from = $("#cuentaTestWhatsapp").val();
        let to = $("#tesToW").val();
        var token = $("input[name=_token]").val();

        $.ajax({
            url: 'api/channel/test/w/send',
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            data: {'from':from, 'to':to},
            success: function(response){
                console.log(response);
                Swal.fire(
                    'Enviado!',
                    'En pocos minutos deberia recibir un mensaje en el whatsapp '+to,
                    'success'
                )
            },
            error: function(response){
                console.log(response);
            }
        });
    });

    // Detiene la ejecucion de la prueba
    $("#modal-test-whatsapp #test-w-parar").on('click', function(){
        pararTestW(carga);
    });

    // Se ejecuta al cerrar la modal
    $("#modal-test-whatsapp").on('hidden.bs.modal', function () {
        pararTestW(carga);
    });

    function pararTestW(carga){
        clearInterval(carga);
        test(false);
        console.log("finalizado");
        $("#modal-test-whatsapp #test-w-parar").prop('disabled', true);
        $("#modal-test-whatsapp #test-w-iniciar").prop('disabled', false);

        $("#logTestWhatsapp").append("FINALIZADO \n");
    }

    function traerMensajesW(id){
        var token = $("input[name=_token]").val();

        $.ajax({
            url: 'api/channel/test/w/out/'+id,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response){
                console.log(response);
                $("#logTestWhatsapp").append(response.data);
            },
            error: function(response){
                console.log(response);
            },
            complete: function(){
                $("#logTestWhatsapp").append("Buscando.... \n");
            }
        });
    }

    function test(activo){
        var token = $("input[name=_token]").val();
        var miId = $("#idTestWhatsapp").val();
        var ruta = '';
        if(activo){
            ruta = 'api/channel/test/w/activate/'+miId;
        }else{
            ruta = 'api/channel/test/w/deactivate/'+miId;
        }

        $.ajax({
            url: ruta,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response){
                console.log(response);
            },
            error: function(response){
                console.log(response);
            }
        });
    }

    /**--------------Seccion facebook------------- */
    $('#btn-crear-facebook').click(function(){
        $("#form-facebook")[0].reset();
        $('#modal-facebook .modal-title').text("Registrar cuenta de facebook");
        $("#form-facebook #accion").val('registrar');
        $('#form-facebook .error_c').remove();
        $('#modal-facebook').modal('show');
    });

    function getChannelsF(id){
        let htmlf = '';

        $.ajax({
            type: 'GET',
            url: 'huesped/facebook-all/'+id,
            beforeSend: function(){
                $('#secFacebook table tbody').html(`
                    <tr>
                        <td colspan="5"><h4>Cargando...</h4></td>
                    </tr>
                    `
                );
            },
            success: function (response) {
                if(response.res.channels.length > 0){
                    $.each(response.res.channels, function(key, value){
                        htmlf += `
                            <tr>
                                <td>${value.nombre}</td>
                                <td>${value.identificador_aplicacion}</td>
                                <td>${(value.id_cfg_chat > 0) ? '<span class="label label-success">Asignado a campaña</span>': '<span class="label label-warning">sin asignar a campaña</span>'}</td>
                                <td>
                                    <button data-id="${value.id}" type="button" class="btn btn-primary btn-sm btn-editar-facebook" title="Editar"><i class="fa fa-edit"></i></button>
                                    <button data-id="${value.id}" type="button" class="btn btn-danger btn-sm btn-eliminar-facebook" title="Eliminar"><i class="fa fa-trash"></i></button>
                                    <button data-id="${value.id}" data-cuenta="${value.identificador_aplicacion}" type="button" class="btn btn-info btn-sm btn-test-facebook" title="Test">Prueba</button>
                                </td>
                            </tr>
                        `;
                    });
                }else{
                    htmlf = `
                    <tr>
                        <td colspan="5"><h4>El huésped aún no tiene canales registrados</h4></td>
                    </tr>
                    `;
                }

                $('#secFacebook table tbody').html(htmlf);
            },
            error: function(response) {
                 toastr.error("No se pudo obtener el registro de los canales de whatsapp");
            }
        })
    }

    /** Este evento abre la modal y trae el registro para poderla editar */
    $('#secFacebook table').on('click', '.btn-editar-facebook', function(){

        $("#form-facebook")[0].reset();
        $('#modal-facebook .modal-title').text("Editar facebook");
        $("#form-facebook #accion").val('actualizar');
        $('#modal-facebook').modal('show');

        $('#form-facebook .error_c').remove();
        $('#mostrar_loading').addClass('loader');

        var id = $(this).data('id');

        $.ajax({
            type: 'GET',
            url: 'huesped/facebook/'+id,
            success: function (response) {

                let data = response.res.channel;

                $("#id_facebook").val(data.id);
                $("#fNombre").val(data.nombre);
                $("#fIdentificador").val(data.identificador_aplicacion);
                $("#fToken").val(data.token);

            },
            error: function(response) {
                 toastr.error(response.message);
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })
    });

    /** Guardar el registro de l formulario strore y update */
    $('#modal-facebook #btn-guardar-facebook').on('click', function(e){
        e.preventDefault();

        $('#mostrar_loading').addClass('loader');
        $('#form-facebook .error_c').remove();

        var data = new FormData($('#form-facebook')[0]);
        var token = $("input[name=_token]").val();
        var id_huesped = $('input#id').val();
        var accion = $('#form-facebook #accion').val();

        switch (accion) {
            case 'registrar':
                $.ajax({
                    url: 'huesped/crear-facebook/'+id_huesped,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){
                        getChannelsF(id_huesped);
                        toastr.success(response.message);
                        $("#form-facebook")[0].reset();
                        $('#modal-facebook').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-facebook [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-facebook [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                                toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                        }
                    },
                    complete: function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;

            case 'actualizar':
                data.append('_method', 'PUT');
                var id_facebook = $('#form-facebook #id_facebook').val();

                $.ajax({
                    url: 'huesped/actualizar-facebook/'+id_facebook,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response){
                        getChannelsF(id_huesped);
                        toastr.success(response.message);
                        $('#modal-facebook').modal('hide');
                    },
                    error: function(response){
                        if(response.status === 422){
                            $.each(response.responseJSON.errors, function(key, value){
                                var name = $("#form-facebook [name='"+key+"']");
                                if(key.indexOf(".") != -1){
                                    var arr = key.split(".");
                                    name = $("#form-facebook [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                                name.focus();
                            });
                            toastr.info('Hubo un problema al validar tus datos');
                        }else{
                            if(response.responseJSON.message){
                               toastr.error(response.responseJSON.message);
                            }else{
                                toastr.error('Se ha presentado un error al guardar los datos.');
                            }
                         }
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
                break;
            default:
                break;
        }
    });

    /** Eliminamos el canal */
    $("#secFacebook table").on('click', '.btn-eliminar-facebook', function(){
        let delId = $(this).data('id');
        let id_huesped = $('input#id').val();
        var token = $("input[name=_token]").val();

        Swal.fire({
            title: 'Deseas eliminar este canal de facebook?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                $('#mostrar_loading').addClass('loader');
                $.ajax({
                    url: 'huesped/eliminar-facebook/'+delId,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'DELETE',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        getChannelsF(id_huesped);
                        Swal.fire(
                            'Eliminado!',
                            'Su registro ha sido eliminado.',
                            'success'
                        )
                    },
                    error: function(response) {
                         toastr.error(response.responseJSON.message);
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                })

            }
        })
    });

    // Inicia la prueba
    // Este evento abre la modal para realizar la prueba de facebook
    $('#secFacebook table').on('click', '.btn-test-facebook', function(){

        $("#form-test-facebook")[0].reset();

        var id = $(this).data('id');
        var numF = $(this).data('cuenta');

        $("#logTestFacebook").html('');
        $("#idTestFacebook").val(id);
        $("#cuentaTestFacebook").val(numF);
        $("#test-texto1f").html(`1. Al iniciar la prueba debes enviar un mensaje a la pagina de facebook para capturar el mensaje`);
        $('#modal-test-facebook').modal('show');

    });

    var cargaf = 0;

    $("#modal-test-facebook #test-f-iniciar").on('click', function(){
        $(this).prop('disabled', true);
        $("#modal-test-facebook #test-f-parar").prop('disabled', false);
        $("#logTestFacebook").html('');
        $("#logTestFacebook").append("INICIANDO PRUEBA \n");
        testF(true);
        let idChannel = $("#idTestFacebook").val();
        let cont = 0;

        $("#logTestFacebook").append("Buscando.... \n");
        cargaf = setInterval(function(){
            cont+=1;
            console.log(cont, ' Intentos');

            traerMensajesF(idChannel)

            if(cont > 5){
                pararTestF(cargaf);
            }
        },10000);
    });

    $("#modal-test-facebook #btnTestSalidaF").on('click', function(){
        let from = $("#cuentaTestFacebook").val();
        let to = $("#tesToF").val();
        var token = $("input[name=_token]").val();

        $.ajax({
            url: 'api/channel/test/f/send',
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            data: {'from':from, 'to':to},
            success: function(response){
                console.log(response);
                Swal.fire(
                    'Enviado!',
                    'En pocos minutos deberia recibir un mensaje en el facebook messenger '+to,
                    'success'
                )
            },
            error: function(response){
                console.log(response);
            }
        });
    });

    // Detiene la ejecucion de la prueba
    $("#modal-test-facebook #test-f-parar").on('click', function(){
        pararTestF(cargaf);
    });

    // Se ejecuta al cerrar la modal
    $("#modal-test-facebook").on('hidden.bs.modal', function () {
        pararTestF(cargaf);
    });

    function pararTestF(carga){
        clearInterval(carga);
        testF(false);
        console.log("finalizado");
        $("#modal-test-facebook #test-f-parar").prop('disabled', true);
        $("#modal-test-facebook #test-f-iniciar").prop('disabled', false);

        $("#logTestFacebook").append("FINALIZADO \n");
    }

    function traerMensajesF(id){
        var token = $("input[name=_token]").val();

        $.ajax({
            url: 'api/channel/test/f/out/'+id,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response){
                console.log(response);
                $("#logTestFacebook").append(response.data);
            },
            error: function(response){
                console.log(response);
            },
            complete: function(){
                $("#logTestFacebook").append("Buscando.... \n");
            }
        });
    }

    function testF(activo){
        var token = $("input[name=_token]").val();
        var miId = $("#idTestFacebook").val();
        var ruta = '';
        if(activo){
            ruta = 'api/channel/test/f/activate/'+miId;
        }else{
            ruta = 'api/channel/test/f/deactivate/'+miId;
        }

        $.ajax({
            url: ruta,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response){
                console.log(response);
            },
            error: function(response){
                console.log(response);
            }
        });
    }

    /**--------------Seccion instagram------------- */

    $("#btn-crear-instagram").on('click', function(){
        
        $("#form-instagram")[0].reset();

        $("#form-instagram #accion").val('registrar');
        $("#form-instagram #id_instagram").val(0);
        $('#form-instagram .error_c').remove();

        $('#modal-instagram .modal-title').text("Registrar cuenta Instagram");

        $('#modal-instagram').modal('show');
    });

    $("#iIdentificador").on('keyup', function(){
        let num = $(this).val();

        $("#webhookInstagram").val(`https://middleware.dyalogo.cloud:3001/dymdw/api/instagram/${num}/msgin`);
    });

    $("#btn-guardar-instagram").on('click', function(e){
        e.preventDefault();

        $('#mostrar_loading').addClass('loader');
        $('#form-instagram .error_c').remove();

        let data = new FormData($('#form-instagram')[0]);
        let token = $("input[name=_token]").val();
        let id_huesped = $('input#id').val();
        let accion = $('#form-instagram #accion').val();

        let url = 'huesped/crear-instagram/'+id_huesped;

        if(accion == 'actualizar'){

            data.append('_method', 'PUT');
            data.append('huesped_id', id_huesped);

            let id_instagram = $('#form-instagram #id_instagram').val();
            url = 'huesped/actualizar-instagram/'+id_instagram;

        }

        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN':token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function(response){

                if(response.status == 'fallo'){
                    toastr.error(response.message);
                    return;
                }

                obtenerCanalesInstagram(id_huesped);
                toastr.success(response.message);
                $("#form-instagram")[0].reset();
                $('#modal-instagram').modal('hide');
            },
            error: function(response){
                if(response.status === 422){
                    $.each(response.responseJSON.errors, function(key, value){
                        var name = $("#form-instagram [name='"+key+"']");
                        if(key.indexOf(".") != -1){
                            var arr = key.split(".");
                            name = $("#form-instagram [name='"+arr[0]+"[]']:eq("+arr[1]+")");
                        }
                        name.parent().append('<span class="error_c" style="color: red;">'+value[0]+'</span>');
                        name.focus();
                    });
                    toastr.info('Hubo un problema al validar tus datos');
                }else{
                    if(response.responseJSON.message){
                        toastr.error(response.responseJSON.message);
                    }else{
                        toastr.error('Se ha presentado un error al guardar los datos.');
                    }
                }
            },
            complete: function(){
                $('#mostrar_loading').removeClass('loader');
            }
        });
    });

    function obtenerCanalesInstagram(id){
        
        let cuerpo = '';

        $.ajax({
            type: 'GET',
            url: 'huesped/all-instagram/'+id,
            beforeSend: function(){
                $('#secFacebook table tbody').html(`
                    <tr>
                        <td colspan="5"><h4>Cargando...</h4></td>
                    </tr>
                    `
                );
            },
            success: function (response) {
                if(response.canales.length > 0){
                    $.each(response.canales, function(key, value){
                        cuerpo += `
                            <tr>
                                <td>${value.nombre}</td>
                                <td>${value.identificador}</td>
                                <td>
                                    ${(value.activo == 1) ? '<span class="label label-success">Activo</span>': '<span class="label label-danger">Desactivado</span>'}
                                    ${(value.id_cfg_chat > 0) ? '<span class="label label-success">Asignado a un chat</span>': '<span class="label label-warning">sin asignar</span>'}
                                    ${(value.id_dymdw == 0) ? '<span class="label label-danger">Desincronizado</span>': '<span class="label label-primary">Sincronizado</span>'}
                                </td>
                                <td>
                                    <button type="button" data-id="${value.id}" class="btn btn-primary btn-sm btn-editar-instagram" title="Editar"><i class="fa fa-edit"></i></button>
                                    <button type="button" data-id="${value.id}" class="btn btn-danger btn-sm btn-eliminar-instagram" title="Eliminar"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                }else{
                    cuerpo = `
                    <tr>
                        <td colspan="5"><h4>El huésped aún no tiene canales registrados</h4></td>
                    </tr>
                    `;
                }

                $('#secInstagram table tbody').html(cuerpo);
            },
            error: function(response) {
                toastr.error("No se pudo obtener el registro de los canales de instagram");
            }
        })
    }

    $('#secInstagram table').on('click', '.btn-editar-instagram', function(){

        let id = $(this).data('id');

        $("#form-instagram")[0].reset();
        $('#modal-instagram .modal-title').text("Editar canal de instagram");
        $("#form-instagram #accion").val('actualizar');
        $('#modal-instagram').modal('show');

        $('#form-instagram .error_c').remove();
        $('#mostrar_loading').addClass('loader');

        $.ajax({
            type: 'GET',
            url: 'huesped/instagram/'+id,
            success: function (response) {

                let data = response.canal;

                $("#id_instagram").val(data.id);
                $("#iNombre").val(data.nombre);
                $("#iIdentificador").val(data.identificador).keyup();
                $("#iToken").val(data.token);

                if(data.activo === 1){
                    $("#iActivo").attr('checked', true);
                }else{
                    $("#iActivo").attr('checked', false);
                }

            },
            error: function(response) {
                toastr.error(response.message);
            },
            complete:function(){
                $('#mostrar_loading').removeClass('loader');
            }
        })

    });

    $('#secInstagram table').on('click', '.btn-eliminar-instagram', function(){

        let id = $(this).data('id');
        let id_huesped = $('input#id').val();
        let token = $("input[name=_token]").val();

        Swal.fire({
            title: 'Deseas eliminar este canal de Instagram?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {

                $('#mostrar_loading').addClass('loader');

                $.ajax({
                    url: 'huesped/eliminar-instagram/'+id,
                    headers: {'X-CSRF-TOKEN':token},
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        if(response.status == 'fallo'){
                            toastr.error(response.message);
                            return;
                        }

                        obtenerCanalesInstagram(id_huesped);
                        Swal.fire(
                            'Eliminado!',
                            'Su registro ha sido eliminado.',
                            'success'
                        )
                    },
                    error: function(response) {
                        toastr.error(response.responseJSON.message);
                    },
                    complete:function(){
                        $('#mostrar_loading').removeClass('loader');
                    }
                })

            }
        })
        
    });
});

