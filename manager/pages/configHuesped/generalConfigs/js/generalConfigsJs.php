<script>
    $(document).ready(function() {

        ocultarCollapse();

        <?php if($_SESSION['CARGO'] != "owner" && $_SESSION['CARGO'] != "super-administrador" ){ ?>
            $("#collapseButtonHuesped").click((e) => {
                e.preventDefault();
                alertify.error("<?php echo $str_permisos_huesped_info; ?>");
            });
        <?php } ?>

        /**
         * Evento que me trae la informacion del huesped seleccionado 
         * Realmente aqui solo obtengo las troncales
         */

        $('#mostrar_loading').addClass('loader');
        $('.error_c').remove();
        $('.error').remove();

        var id_huesped = <?= $_SESSION['HUESPED'] ?>;
        $("input#id").val(id_huesped);
        const tokenApi = {
            "strUsuario_t": "adminApi",
            "strToken_t": "<?= admin_token_api ?>"
        }

        $.ajax({
            type: 'POST',
            data: JSON.stringify(tokenApi),
            dataType: 'json',
            "headers": {
                "Content-Type": "application/json"
            },
            url: '<?= base_url_admin ?>api/huesped/show/' + id_huesped,
            success: function(response) {

                $('h3.title').text(response.huesped.nombre);

                mostrarCollapse();
                cambiarHuesped(response.huesped, response.troncales);
                listarFestivos(response.huesped.id);

            },
            error: function(response) {
                if (typeof response.message !== 'undefined') {
                    toastr.error(response.message);
                } else if (typeof response.responseJSON.message !== 'undefined') {
                    toastr.error(response.responseJSON.message);
                } else {
                    toastr.error('Se presentó un error al obtener los datos');
                }
            },
            complete: function() {
                $('#mostrar_loading').removeClass('loader');
            }
        });

        $('.text-hora').timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:00',
            'step': '5',
            'showDuration': true
        });

        // $('input[type="checkbox"].minimal').iCheck({
        //     checkboxClass: 'icheckbox_minimal-blue'
        // });

        function ocultarCollapse() {
            $('.e-collapse').hide();
        }

        function mostrarCollapse() {
            // Menu tabs
            $('.e-collapse').show();
        }

        $(".chb").change(function() {
            $(".chb").prop('checked', false);
            $(this).prop('checked', true);
        });

        function validarCampos(accion = 'nuevo') {

            $('.error').remove();
            var mensajeObligatorio = '<label class="error">Este campo es obligatorio.</label>';
            var mensajeSizeMax = '<label class="error">El archivo no debe superar los 3MB.</label>';
            var mensajeExtencion = '<label class="error">El archivo no tiene la extensión adecuada.</label>';
            var valido = true;


            if (accion == "malla") {
                if ($('#horaEntrada').val().length < 1) {
                    $('#collapsePausas').collapse('show');
                    $('#horaEntrada').focus();
                    $('#horaEntrada').after(mensajeObligatorio);
                    valido = false;
                }

                if ($('#horaSalida').val().length < 1) {
                    $('#collapsePausas').collapse('show');
                    $('#horaSalida').focus();
                    $('#horaSalida').after(mensajeObligatorio);
                    valido = false;
                }
            }

            if (accion == "notifications") {

                if ($('#emails_notificar_incumplimientos').val().length < 1) {
                    $('#collapseNotificaciones').collapse('show');
                    $('#emails_notificar_incumplimientos').focus();
                    $('#emails_notificar_incumplimientos').after(mensajeObligatorio);
                    valido = false;
                }

                if ($('#notificacion_usuario').val().length < 1) {
                    $('#collapseNotificaciones').collapse('show');
                    $('#notificacion_usuario').focus();
                    $('#notificacion_usuario').after(mensajeObligatorio);
                    valido = false;
                }

                if ($('#notificacion_password').val().length < 1) {
                    $('#collapseNotificaciones').collapse('show');
                    $('#notificacion_password').focus();
                    $('#notificacion_password').after(mensajeObligatorio);
                    valido = false;
                }
            }

            return valido;
        }


        /**
         * Actualiza el huesped actual
         */
        $('#update').click(function(e) {
            e.preventDefault();

            if (validarCampos('editar')) {

                $('#myTab a[href="#tab_1"]').tab('show');
                $('#mostrar_loading').addClass('loader');

                $('.error_c').remove();
                $('.error').remove();

                var data = new FormData($('#form-huesped')[0]);
                var token = $("input[name=_token]").val();
                var id = $('input#id').val();

                data.append('_method', 'PUT');

                $.ajax({
                    url: 'huesped/' + id,
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response) {
                        toastr.success(response.message);

                        cambiarHuesped(response.huesped);
                    },
                    error: function(response) {
                        console.log('log', response);
                        if (response.status === 422) {
                            $.each(response.responseJSON.errors, function(key, value) {
                                var name = $("[name='" + key + "']");
                                if (key.indexOf(".") != -1) {
                                    var arr = key.split(".");
                                    name = $("[name='" + arr[0] + "[]']:eq(" + arr[1] + ")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">' + value[0] + '</span>');
                                name.focus();
                            });
                            $('#collapseCantidadesAutorizadas').collapse('show');
                            $('#collapseNotificaciones').collapse('show');
                            $('#collapseTwo').collapse('show');

                            toastr.info('Hubo un problema al validar tus datos');

                            var errores = '<ul class="list-unstyled">';
                            $.each(response.responseJSON.errors, function(key, value) {
                                errores += '<li>' + value + '</li>';
                            });
                            errores += '</ul>'

                            Swal.fire({
                                icon: 'info',
                                title: 'Hubo un problema al validar tus datos',
                                html: errores
                            });
                        } else {
                            if (typeof response.message !== 'undefined') {
                                toastr.error(response.message);
                            } else if (typeof response.responseJSON !== 'undefined' && typeof response.responseJSON.message !== 'undefined') {
                                toastr.error(response.responseJSON.message);
                            } else {
                                toastr.error('Se ha presentado un error al guardar los datos');
                            }
                        }

                    },
                    complete: function() {
                        $('#mostrar_loading').removeClass('loader');
                    }
                });

            } else {
                toastr.info('Datos invalidos');
            }

        });



        /**
         * Actualiza unicamente la malla de turnos del huesped
         */
        $('#updateMallaTurnos').click(function(e) {
            e.preventDefault();

            if (validarCampos('malla')) {

                $('#myTab a[href="#tab_1"]').tab('show');
                $('#mostrar_loading').addClass('loader');

                $('.error_c').remove();
                $('.error').remove();

                var data = new FormData($('#form-malla-turnos')[0]);
                data.append("strUsuario_t", tokenApi.strUsuario_t);
                data.append("strToken_t", tokenApi.strToken_t);

                var id = $('input#id').val();

                data.append('_method', 'PUT');

                $.ajax({
                    url: '<?= base_url_admin ?>api/huesped/updateShiftMesh/' + id,
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response) {
                        toastr.success(response.message);

                        cambiarHuesped(response.huesped);
                    },
                    error: function(response) {
                        console.log('log', response);
                        if (response.status === 422) {
                            $.each(response.responseJSON.errors, function(key, value) {
                                var name = $("[name='" + key + "']");
                                if (key.indexOf(".") != -1) {
                                    var arr = key.split(".");
                                    name = $("[name='" + arr[0] + "[]']:eq(" + arr[1] + ")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">' + value[0] + '</span>');
                                name.focus();
                            });
                            $('#collapseCantidadesAutorizadas').collapse('show');
                            $('#collapseNotificaciones').collapse('show');
                            $('#collapseTwo').collapse('show');

                            toastr.info('Hubo un problema al validar tus datos');

                            var errores = '<ul class="list-unstyled">';
                            $.each(response.responseJSON.errors, function(key, value) {
                                errores += '<li>' + value + '</li>';
                            });
                            errores += '</ul>'

                            Swal.fire({
                                icon: 'info',
                                title: 'Hubo un problema al validar tus datos',
                                html: errores
                            });
                        } else {
                            if (typeof response.message !== 'undefined') {
                                toastr.error(response.message);
                            } else if (typeof response.responseJSON !== 'undefined' && typeof response.responseJSON.message !== 'undefined') {
                                toastr.error(response.responseJSON.message);
                            } else {
                                toastr.error('Se ha presentado un error al guardar los datos');
                            }
                        }

                    },
                    complete: function() {
                        $('#mostrar_loading').removeClass('loader');
                    }
                });

            } else {
                toastr.info('Datos invalidos');
            }

        });



         /**
         * Actualiza unicamente la malla de turnos del huesped
         */
        $('#updateNotifications').click(function(e) {
            e.preventDefault();

            if (validarCampos('notifications')) {

                $('#myTab a[href="#tab_1"]').tab('show');
                $('#mostrar_loading').addClass('loader');

                $('.error_c').remove();
                $('.error').remove();

                var data = new FormData($('#form-notifications')[0]);
                data.append("strUsuario_t", tokenApi.strUsuario_t);
                data.append("strToken_t", tokenApi.strToken_t);

                var id = $('input#id').val();

                data.append('_method', 'PUT');

                $.ajax({
                    url: '<?= base_url_admin ?>api/huesped/updateNotifications/' + id,
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(response) {
                        toastr.success(response.message);

                        cambiarHuesped(response.huesped);
                    },
                    error: function(response) {
                        console.log('log', response);
                        if (response.status === 422) {
                            $.each(response.responseJSON.errors, function(key, value) {
                                var name = $("[name='" + key + "']");
                                if (key.indexOf(".") != -1) {
                                    var arr = key.split(".");
                                    name = $("[name='" + arr[0] + "[]']:eq(" + arr[1] + ")");
                                }
                                name.parent().append('<span class="error_c" style="color: red;">' + value[0] + '</span>');
                                name.focus();
                            });
                            $('#collapseCantidadesAutorizadas').collapse('show');
                            $('#collapseNotificaciones').collapse('show');
                            $('#collapseTwo').collapse('show');

                            toastr.info('Hubo un problema al validar tus datos');

                            var errores = '<ul class="list-unstyled">';
                            $.each(response.responseJSON.errors, function(key, value) {
                                errores += '<li>' + value + '</li>';
                            });
                            errores += '</ul>'

                            Swal.fire({
                                icon: 'info',
                                title: 'Hubo un problema al validar tus datos',
                                html: errores
                            });
                        } else {
                            if (typeof response.message !== 'undefined') {
                                toastr.error(response.message);
                            } else if (typeof response.responseJSON !== 'undefined' && typeof response.responseJSON.message !== 'undefined') {
                                toastr.error(response.responseJSON.message);
                            } else {
                                toastr.error('Se ha presentado un error al guardar los datos');
                            }
                        }

                    },
                    complete: function() {
                        $('#mostrar_loading').removeClass('loader');
                    }
                });

            } else {
                toastr.info('Datos invalidos');
            }

        });

        /**
         * Sobrescribe los datos del huésped en #form-huesped
         */
        function cambiarHuesped(huespedData, troncales = null) {
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

            if (h.doble_factor_autenticacion == '1') {
                $('#dobleFactorAuth').prop('checked', true);
            }

            $('#collapseNotificaciones input#notificar_pausas').prop('checked', (h.notificar_pausas == 1) ? true : false);
            $('#collapseNotificaciones input#notificar_sesiones').prop('checked', (h.notificar_sesiones == 1) ? true : false);
            $('#collapseNotificaciones input#notificar_incumplimientos').prop('checked', (h.notificar_incumplimientos == 1) ? true : false);
            $('#collapseNotificaciones input#emails_notificar_pausas').val(h.emails_notificar_pausas);
            $('#collapseNotificaciones input#emails_notificar_sesiones').val(h.emails_notificar_sesiones);
            $('#collapseNotificaciones input#emails_notificar_incumplimientos').val(h.emails_notificar_incumplimientos);

            $('#mallaTurnoRequerida').prop('checked', (h.malla_turno_requerida == 1) ? true : false);
            $('#mallaTurnoHorarioDefecto').prop('checked', (h.malla_turno_horario_por_defecto == 1) ? true : false);
            $('#horaEntrada').val(h.hora_entrada_por_defecto);
            $('#horaSalida').val(h.hora_salida_por_defecto);
            if (h.proyecto) {
                $('#collapseCantidadesAutorizadas input#cantidadMaxAgentesSimultaneos').val(h.proyecto.cantidad_max_agentes_simultaneos);
            }
            $('#collapseCantidadesAutorizadas input#cantidadMaximaSupervisores').val(h.cantidad_max_supervisores);
            $('#collapseCantidadesAutorizadas input#cantidadMaximaBackoffice').val(h.cantidad_max_bo);
            $('#collapseCantidadesAutorizadas input#cantidadMaximaCalidad').val(h.cantidad_max_calidad);



            if (h.mail_notificacion) {
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

            let html_contacto = '';
            $('#panel-contacto table tbody').html(html_contacto);

            let huesped_archivos = $('#panel-archivos');

            huesped_archivos.find('a.camara_comercio').attr('href', 'huesped/file/' + h.id + '/camara_comercio.pdf');
            huesped_archivos.find('a.rut').attr('href', 'huesped/file/' + h.id + '/rut.pdf');
            huesped_archivos.find('a.certificacion_bancaria').attr('href', 'huesped/file/' + h.id + '/certificacion_bancaria.pdf');
            huesped_archivos.find('a.orden_compra').attr('href', 'huesped/file/' + h.id + '/orden_compra.pdf');
            huesped_archivos.find('a.alcances').attr('href', 'huesped/file/' + h.id + '/alcances.pdf');



        }

        // /**
        //  * Agrega nuevo campo para registrar nuevo contacto
        //  */
        // $("#add-contacto").click(function() {
        //     $('#panel-contacto table tbody').append(generateContacto());
        // });

        // $("#panel-contacto").on('click', '.eliminarContacto', function() {
        //     $(this).closest('tr').remove();
        // });

        // /**
        //  * Crear por defecto objeto contacto vacio
        //  */
        // function Contacto() {
        //     this.id = -1;
        //     this.nombre = '';
        //     this.email = '';
        //     this.tipo = '';
        //     this.telefono1 = '';
        //     this.telefono2 = '';
        // }

        //     /**
        //      * Genera Codigo html para añadir nuevo contacto
        //      */
        //     function generateContacto(contacto = new Contacto) {
        //         let html = `
        // <tr class="linea-contacto">
        //     <input type="hidden" name="contacto_id[]" value="${contacto.id}" form="form-huesped">
        //     <td>
        //         <input type="text" class="form-control contac-nombre" name="contacto_nombre[]" placeholder="Ingresa el nombre" value="${contacto.nombre}" form="form-huesped">
        //     </td>
        //     <td>
        //         <input type="email" class="form-control contac-email" name="contacto_email[]" placeholder="Ingresa el email" value="${contacto.email}" form="form-huesped">
        //     </td>
        //     <td>
        //         <select name="contacto_tipo[]" class="form-control" form="form-huesped">
        //             <option value="T" ${contacto.tipo == "T" ? 'selected' : ''}>Tecnico</option>
        //             <option value="P" ${contacto.tipo == "P" ? 'selected' : ''}>Pagos</option>
        //             <option value="F" ${contacto.tipo == "F" ? 'selected' : ''}>Funcional</option>
        //         </select>
        //     </td>
        //     <td>
        //         <input type="tel" class="form-control contac-telefono" name="contacto_telefono1[]" placeholder="Ingresa el numero de teléfono" value="${contacto.telefono1}" form="form-huesped">
        //     </td>
        //     <td>
        //         <input type="tel" class="form-control" name="contacto_telefono2[]" placeholder="Ingresa el numero de teléfono" value="${contacto.telefono2}" form="form-huesped">
        //     </td>
        //     <td>
        //         <button type="button" class="btn btn-danger eliminarContacto"><i class="fa fa-trash"></i></button>
        //     </td>
        // </tr>
        // `;

        //         return html;
        //     }

        /**
         * Devuelve todas las ciudades del pais seleccionado
         */

        /**
         * Busca todos las ciudades de un pais
         */

        /** Ejecuta el test de cuenta de notificaciones smtp */
        $('#btn-test-notificacion-cuenta').on('click', function() {
            var idCuentaNotificacion = $(this).data('id');
            $('#modal-prueba-notificacionCuenta #notificacionLog').html('');

            $('#modal-prueba-notificacionCuenta .estado-test-notificacion').hide();

            if (idCuentaNotificacion == -1) {
                alert('No se puede realizar la prueba hasta no haber registrado el huésped');
            } else {

                $('#modal-prueba-notificacionCuenta').modal('show');

                $('#mostrar_loading').addClass('loader');

                var token = $("input[name=_token]").val();
                var id_huesped = $('input#id').val();
                var f = new Date();
                var dia = f.getDate() + '-' + (f.getMonth() + 1) + '-' + f.getFullYear();
                var hora = f.getHours() + ':' + f.getMinutes() + ':' + f.getSeconds();

                $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia + ' ' + hora + ' iniciado &#13;&#10;');

                $.ajax({
                    url: '<?=base_url_admin?>api/huesped/prueba-email-notificacion-smtp/' + id_huesped,
                    data: JSON.stringify(tokenApi),
                    dataType: 'json',
                    "headers": {
                        "Content-Type": "application/json"
                    },
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        $('#modal-prueba-notificacionCuenta .estado-test-notificacion').text('Prueba fallida').removeClass('text-success').addClass('text-danger').show();
                        if (response.json) {

                            if (response.json.strEstado_t == 'ok') {
                                $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia + ' ' + hora + ' Estado: ' + response.json.strEstado_t + ' &#13;&#10;');
                                $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia + ' ' + hora + ' Se ha ejecutado la prueba exitosamente &#13;&#10;');
                                $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia + ' ' + hora + ' Prueba finalizada &#13;&#10;');

                                $('#modal-prueba-notificacionCuenta .estado-test-notificacion').text('Prueba exitosa').removeClass('text-danger').addClass('text-success');
                            } else {
                                hora = f.getHours() + ':' + f.getMinutes() + ':' + f.getSeconds();
                                $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia + ' ' + hora + ' Estado: ' + response.json.strEstado_t + ' &#13;&#10;');
                                $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia + ' ' + hora + ' Hubo un error al ejecutar la prueba de entrada de correo &#13;&#10;');
                            }

                        } else {
                            $('#modal-prueba-notificacionCuenta #notificacionLog').append(dia + ' ' + hora + ' Ha habido un problema al comunicarse con la api &#13;&#10;');
                        }

                    },
                    error: function(response) {
                        if (response.responseJSON.message) {
                            toastr.error(response.responseJSON.message);
                        } else {
                            toastr.error('Se ha presentado un error al consumir la api.');
                        }
                    },
                    complete: function() {
                        $('#mostrar_loading').removeClass('loader');
                    }
                });
            }
        });

        /**--------------Seccion pausas------------- */
        function listarPausas(id_huesped, pausa1, pausa2, pausa3) {
            $.ajax({
                type: 'GET',
                url: 'huesped/listar-pausas/' + id_huesped,
                success: function(response) {

                    let html11 = '';
                    if (response.listaPausas) {
                        $.each(response.listaPausas, function(key, value) {
                            html11 += agregarPausas(value);
                        });
                    } else {
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
                complete: function() {
                    $('#form-pausas table tbody tr#' + pausa1).find('input.nombre_pausa').prop('readonly', 'true');
                    $('#form-pausas table tbody tr#' + pausa1).find('select option:not(:selected)').prop('disabled', 'true');
                    $('#form-pausas table tbody tr#' + pausa2).find('input.nombre_pausa').prop('readonly', 'true');
                    $('#form-pausas table tbody tr#' + pausa2).find('select option:not(:selected)').prop('disabled', 'true');
                    $('#form-pausas table tbody tr#' + pausa3).find('input.nombre_pausa').prop('readonly', 'true');
                    $('#form-pausas table tbody tr#' + pausa3).find('select option:not(:selected)').prop('disabled', 'true');
                }
            })
        }

        /**Esta funcion retorna las pausas que tiene el huesped */
        function agregarPausas(data) {
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
        $('#btn-agregar-pausa').on('click', function() {
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
        $('#secPausas table').on('change', '.tipo-pausa', function() {
            var idTipoPausa = $(this).val();
            var trPadre = $(this).closest('tr');

            if (idTipoPausa == 0) {
                trPadre.find("[name='hora_inicial[]']").prop('readonly', 'true');
                trPadre.find("[name='hora_final[]']").prop('readonly', 'true');
                trPadre.find("[name='duracion_maxima[]']").prop('readonly', '');
                trPadre.find("[name='cantidad_maxima[]']").prop('readonly', '');
            } else if (idTipoPausa == 1) {
                trPadre.find("[name='hora_inicial[]']").prop('readonly', '');
                trPadre.find("[name='hora_final[]']").prop('readonly', '');
                trPadre.find("[name='duracion_maxima[]']").prop('readonly', 'true');
                trPadre.find("[name='cantidad_maxima[]']").prop('readonly', 'true');
            }

        });

        /** Eliminar pausa */
        $('#secPausas table').on('click', '.btn-eliminar-pausa', function() {
            $(this).closest('tr').remove();
        });

        /** Guardar Cambios en la tabla de pausas */
        $('#btn-guardar-cambios-pausas').on('click', function(e) {
            e.preventDefault();

            $('#mostrar_loading').addClass('loader');
            $('#form-pausas .error_c').remove();

            var data = new FormData($('#form-pausas')[0]);
            var token = $("input[name=_token]").val();
            var id_huesped = $('input#id').val();

            $.ajax({
                url: 'huesped/guardar-pausas/' + id_huesped,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: data,
                success: function(response) {
                    toastr.success(response.message);
                    listarPausas(id_huesped, 0, 0, 0);
                },
                error: function(response) {
                    if (response.status === 422) {
                        $.each(response.responseJSON.errors, function(key, value) {
                            var name = $("#form-pausas [name='" + key + "']");
                            if (key.indexOf(".") != -1) {
                                var arr = key.split(".");
                                name = $("#form-pausas [name='" + arr[0] + "[]']:eq(" + arr[1] + ")");
                            }
                            name.parent().append('<span class="error_c" style="color: red;">' + value[0] + '</span>');
                            name.focus();
                        });
                        toastr.info('Hubo un problema al validar tus datos');
                    } else {
                        if (response.responseJSON.message) {
                            toastr.error(response.responseJSON.message);
                        } else {
                            toastr.error('Se ha presentado un error al guardar los datos.');
                        }
                    }
                },
                complete: function() {
                    $('#mostrar_loading').removeClass('loader');
                }
            });

        });


        /*------------- Seccion Festivos -------------*/

        /** Listar fechas */
        function listarFestivos(id_huesped) {

            $.ajax({
                type: 'POST',
                data: JSON.stringify(tokenApi),
                dataType: 'json',
                "headers": {
                    "Content-Type": "application/json"
                },
                url: '<?=base_url_admin?>api/huesped/listar-festivos/' + id_huesped,
                success: function(response) {

                    let html11 = '';
                    if (response.listaFestivos && response.listaFestivos.festivos.length > 0) {
                        $('#collapseFestivos .text-festivo').text(response.listaFestivos.nombre);
                        $.each(response.listaFestivos.festivos, function(key, value) {
                            html11 += agregarFestivos(value, key);
                        });
                    } else {
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

        function agregarFestivos(data, key) {
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
        $('#btn-agregar-fecha').on('click', function() {
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
        $('#collapseFestivos table').on('click', '.btn-eliminar-fecha', function() {

            // let id_festivo = $(this).data('id');

            // if(id_festivo < 0){
            //     $(this).closest('tr').remove();
            // }
            $(this).closest('tr').remove();

        });

        /** Guardar Cambios fecha */
        $('#btn-guardar-cambios-fecha').on('click', function(e) {
            e.preventDefault();

            $('#mostrar_loading').addClass('loader');
            $('#form-festivos .error_c').remove();

            var data = new FormData($('#form-festivos')[0]);
            data.append("strUsuario_t", tokenApi.strUsuario_t);
            data.append("strToken_t", tokenApi.strToken_t);
            var id_huesped = $('input#id').val();

            $.ajax({
                url: '<?=base_url_admin?>api/huesped/guardar-festivos/' + id_huesped,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: data,
                success: function(response) {
                    toastr.success(response.message);
                    listarFestivos(id_huesped);
                },
                error: function(response) {
                    if (response.status === 422) {
                        $.each(response.responseJSON.errors, function(key, value) {
                            var name = $("#form-festivos [name='" + key + "']");
                            if (key.indexOf(".") != -1) {
                                var arr = key.split(".");
                                name = $("#form-festivos [name='" + arr[0] + "[]']:eq(" + arr[1] + ")");
                            }
                            name.parent().append('<span class="error_c" style="color: red;">' + value[0] + '</span>');
                            name.focus();
                        });
                        toastr.info('Hubo un problema al validar tus datos');
                    } else {
                        if (response.responseJSON.message) {
                            toastr.error(response.responseJSON.message);
                        } else {
                            toastr.error('Se ha presentado un error al guardar los datos.');
                        }
                    }
                },
                complete: function() {
                    $('#mostrar_loading').removeClass('loader');
                }
            });

        });

        $('#btn-generar-festivo').on('click', function(e) {
            e.preventDefault();

            $('#mostrar_loading').addClass('loader');
            var id_huesped = $('input#id').val();

            $.ajax({
                url: '<?=base_url_admin?>api/huesped/generar-festivos/' + id_huesped,
                headers: {
                    "Content-Type": "application/json"
                },
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: JSON.stringify({
                    id_huesped: id_huesped,
                    ...tokenApi
                }),
                success: function(response) {
                    toastr.success(response.message);
                    listarFestivos(id_huesped);
                },
                error: function(response) {

                    if (typeof response.message !== 'undefined') {
                        toastr.error(response.message);
                    } else if (typeof response.responseJSON.message !== 'undefined') {
                        toastr.error(response.responseJSON.message);
                    } else {
                        toastr.error('Se presentó un error al obtener los datos');
                    }

                },
                complete: function() {
                    $('#mostrar_loading').removeClass('loader');
                }
            });
        });

        function inicializarDatePicker() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        }

        /** ----------------------- Usuarios ---------------------------  */
        /** Retorna una fila de usuario */
        function getHtmlUsuario(data) {
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

    });
</script>