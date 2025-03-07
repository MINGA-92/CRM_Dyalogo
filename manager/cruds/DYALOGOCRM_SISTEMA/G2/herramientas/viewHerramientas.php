<!-- Herramientas  -->
<?php //var_dump($_SESSION['CARGO'])  ?>

<div class="panel box box-primary box-solid" <?php echo $_SESSION['CARGO'] === "super-administrador" ? "" : "hidden" ?>>
    <div class="box-header with-border">
        <h3 class="box-title" id="h3herramientas">
            <a data-toggle="collapse" data-parent="#accordion" href="#herramientas">
                HERRAMIENTAS
            </a>
        </h3>
    </div>

    <div id="herramientas" class="panel-collapse collapse">
        <!-- Herramientas  -->
        <div class="box-body" id="bodyHerramientas">
            <div class="row">
                <div class="col-md-6 col-xs-6 col-sm-6 " id="divCampollave">
                    <div class="form-group">
                        <label>CAMPO LLAVE</label>
                        <select class="form-control" id="selectCampoLlave" placeholder="Your query" name="selectCampoLlave">
                            <option value="0">Seleccione</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-xs-6 col-sm-3" id="divBtnConsultar">
                    <label for="btn-consultar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <button id="btn-consultar" type="button" class="form-control btn btn-info">Buscar
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="col-md-3 col-xs-6 col-sm-3" id="divBtnDepurar">
                    <label for="btnDepurar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <button id="btnDepurar" type="button" class="form-control btn btn-danger">Depurar
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="col-md-7 col-xs-12 col-sm-7">
                    <div class="form-group">
                        <label for="">REGISTROS DUPLICADOS EN LA BASE DE DATOS</label>
                        <textarea class="form-control" name="text-area-duplicados" id="text-area-duplicados" cols="30" rows="15" wrap="hard" readonly>
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Termina Herramientas  -->
<!-- table -->
<template id="template-duplicados">
    <div class="container" id="containerDuplicados">
        <div class="row">
            <textarea id="json" style="display:none;"></textarea>
            <div class="col-xs-12">
                <table id="tableDuplicados" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="font-size:12px;">Día</th>
                            <th style="font-size:12px;">Hora Entrada</th>
                            <th style="font-size:12px;">Hora Inicio</th>
                            <th style="font-size:12px;">Hora Fin</th>
                            <th style="font-size:12px;">Hora Inicio</th>
                            <th style="font-size:12px;">Hora Fin</th>
                            <th style="font-size:12px;">Hora Inicio</th>
                            <th style="font-size:12px;">Hora Fin</th>
                            <th style="font-size:12px;">Hora salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lunes</td>
                            <td>Lunes</td>
                            <td>Lunes</td>
                            <td>Lunes</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<!-- Termina table  -->




<!-- modal  -->
<div class="modal fade-in" id="modalDuplicados" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_cargue">Cargar datos desde archivo</h4>
            </div>
            <div class="modal-body" id="body-modal-carga">
                <div class="container" id="divContenedor">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">

                            <div class="container contienebarra">
                                <center>
                                    <h2 id="titulo_cargue">CARGANDO REGISTROS</h2>
                                    <p id="mensaje_cargue">Por favor espere hasta que termine el cargue.</p>
                                </center>
                                <div class="form-group">
                                    <strong>Importando Datos</strong>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped active animacion progress-bar1" role="progressbar" aria-valuenow="" aria-valuemin="70" aria-valuemax="100">0%

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div>
                                                <strong class="estado1">Pendiente</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <strong>Validando Datos</strong>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped active animacion progress-bar2" role="progressbar" aria-valuenow="" aria-valuemin="70" aria-valuemax="100">0%

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div>
                                                <strong class="estado2">Pendiente</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <strong>Insertando y/o Actualizando Registros</strong>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped active animacion progress-bar3" role="progressbar" aria-valuenow="" aria-valuemin="70" aria-valuemax="100">0%

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div>
                                                <strong class="estado3">Pendiente</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div></div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" name="cierraimportacion" id="cierraimportacion" class="btn btn-primary btn-block">Finalizar</button>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- termina modal -->


<script>
    const selectCampoLlave = document.getElementById("selectCampoLlave");
    const btnConsultar = document.getElementById('btn-consultar')
    const btnDepurar = document.getElementById('btnDepurar')
    const btnCierraimportacion = document.getElementById('cierraimportacion')
    const divContenedor = document.getElementById('divContenedor')
    const templateDuplicados = document.getElementById('template-duplicados').content
    const bodyModalCarga = document.getElementById('body-modal-carga')
    const containerDuplicados = document.getElementById('containerDuplicados')
    const textArea = document.getElementById('text-area-duplicados')


    const callDatosPregun = async (intIdBD) => {

        // console.trace(intIdBD);

        $.ajax({
            url: '<?= $url_crud_herramientas ?>/getPregun',
            type: 'GET',
            data: {
                intIdBD: `${intIdBD}`
            },
            dataType: 'json',
            async: true,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?= base_url ?>assets/img/clock.gif"/> PROCESANDO PETICION'
                })
            },
            success: function(response) {

                const {
                    data
                } = response

                pintarPregun(data)

            },
            complete: function() {
                $.unblockUI()
            },
            error: function() {
                alertify.error(`Ocurrio un error al procesar la solicitud ${data}`)
                $.unblockUI()
            }
        });

    }

    const limpiarSelect = (select) => {
        new Promise((resolve, reject) => {
            for (let i = select.options.length; i >= 1; i--) {
                // console.log('i :>> ', i);
                resolve(select.remove(i));
            }

        })
    };


    const filterData = (data) => {
        return data.filter((item) => item.id_tipo == 3 || item.id_tipo == 4 || item.id_tipo == 14 || item.id_tipo == 1)
    }


    const createOption = (data, select) => {

        new Promise((resolve, reject) => {
            data.forEach(item => {

                const option = document.createElement("option");
                option.value = item.id_pregun
                option.text = item.nombre;

                console.log('option :>> ', option);
                resolve(select.appendChild(option))

            });
        })

    }


    const pintarPregun = async (data) => {
        const datos = filterData(data)

        await limpiarSelect(selectCampoLlave)
        await createOption(datos, selectCampoLlave)

        buscarRegistrosRepetidos()
    }

    const obtenerIdBasedatos = () => {
        return new Promise((resolve, reject) => {
            const select = document.getElementById('sql_query')
            const option = select.options[select.selectedIndex]
            resolve(option.getAttribute("idbd"))
        })

    }


    const onchange = (e) => {
        console.log('selectCampoLlave.value :>> ', selectCampoLlave.value);
        // data.campoLlave = selectCampoLlave.value
        selectCampoLlave.value === "0" ? btnConsultar.disabled = true : btnConsultar.removeAttribute("disabled")
        selectCampoLlave.value === "0" ? btnDepurar.disabled = true : btnDepurar.removeAttribute("disabled")

        console.log('e.targe.value :>> ', e.target.value);
    }


    const buscarRegistrosRepetidos = async () => {

        // const selectCampoLlave = document.getElementById("selectCampoLlave");


        // btnConsultarDuplicados(data)
        // deleteDuplicados(data)

        // clearInterval()


    }

    const btnCierreImportacionOnclick = () => {

        const fragment = document.createDocumentFragment()
        bodyModalCarga.innerHTML = ""

        // $('.contienebarra').css('visibility','hidden');
        // $('#cierraimportacion').css('visibility','hidden');

        const clone = templateDuplicados.cloneNode(true)
        fragment.appendChild(clone)
        bodyModalCarga.appendChild(fragment)
    }



    const btnConsultarOnclick = async () => {
        const intIdBasedatos = await obtenerIdBasedatos()
        const strEstrategia = document.getElementById('IdEstrat').value;

        const data = {
            strCampoLlave: `G${intIdBasedatos}_C${selectCampoLlave.value}`,
            strEstrategia: strEstrategia
        }

        // console.log('se ejecuta depurar[data] :>> ', data);
        if (selectCampoLlave.value === "0") {
            alertify.warning("Debe seleccionar algo en el campo llave")

        } else {

            $.ajax({
                url: '<?= $url_crud_herramientas ?>/getRepetidos',
                type: 'GET',
                data: data,
                dataType: 'json',
                async: true,

                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?= base_url ?>assets/img/clock.gif" /> PROCESANDO PETICION'
                    });
                },
                success: function(response) {

                    const {
                        data
                    } = response


                    pintarTextArea(data)
                    // pintarPregun(data)

                    console.log("response", response)
                },

                complete: function() {
                    $.unblockUI()
                },
                error: function() {
                    alertify.error(`Ocurrio un error al procesar la solicitud ${data}`)
                    $.unblockUI()
                }
            });

        }
    }

    const btnDepurarOnclick = async () => {
        const intIdBasedatos = await obtenerIdBasedatos()
        const strEstrategia = document.getElementById('IdEstrat').value;

        const data = {
            strCampoLlave: `G${intIdBasedatos}_C${selectCampoLlave.value}`,
            strEstrategia: strEstrategia
        }

        // console.log('se ejecuta depurar[data] :>> ', data);
        if (selectCampoLlave.value === "0") {
            alertify.warning("Debe seleccionar algo en el campo llave")

        } else {

            // hacemos la peticion
        }

    }

    const pintarTextArea = (data) => {

        data.forEach(item => {
            console.log('item :>> ', item);
            textArea.value += `\n id_registro_eliminar: ${item.grupo_repetidos}; id_registro_conservar: ${item.id_registroAConservar}; llave: ${item.llave}; veces: ${item.veces}; \n`
            //  += item + "\n"
        });
    }



    btnConsultar.addEventListener('click', btnConsultarOnclick)
    btnDepurar.addEventListener('click', btnDepurarOnclick)

    btnCierraimportacion.addEventListener('click', btnCierreImportacionOnclick)

    selectCampoLlave.addEventListener('change', onchange)
</script>