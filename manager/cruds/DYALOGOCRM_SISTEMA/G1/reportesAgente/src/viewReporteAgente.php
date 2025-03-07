<!-- Reportes por agente  -->




<div class="panel box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="h3ReportesAgente">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#reportesAgente" aria-expanded="true" aria-controls="collapseOne">
                Reportes por agente
            </a>
        </h3>
    </div>

    <div id="reportesAgente" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <div class="container-fluid" id="bodyReportesAgente">
            <div class="row">

                <!-- <div class="col-md-10" id="divSelGraficas" >
                    <div class="form-group">
                        <label>GRAFICAS</label>
                        <select class="form-control" id="selGraficas" placeholder="Your query" name="selGraficas">
                        </select>
                    </div>
                </div> -->

                <div class="col-md-7 col-sm-7 col-lg-8" id="divSelReportes">
                    <div class="form-group">
                        <label><?php echo $str_title_reportes_campan; ?></label>
                        <select class="form-control" id="tipoReporte" placeholder="Your query" name="tipoReporte">
                            <!-- tipos reportes -->
                            <option value="0">Seleccione</option>

                            <optgroup label="GESTIONES POR AGENTE" id="gestionesAgente">
                                <option value="condia">GESTIONES</option>

                            </optgroup>

                            <optgroup label="CALIDAD POR GANTE" id="calidadAgente">
                                <option value="calidad">CALIDAD</option>

                            </optgroup>

                            <optgroup label="ADHERENCIA AL TURNO" id="adherenciasAgente">

                            </optgroup>


                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-lg-2 col-xs-6 col-sm-3">
                    <div class="form-group">
                        <label for="generarReporte">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <button id="generarReporte" type="button" class="form-control btn btn-primary btn-sm" onclick="generarReportesAgente()">
                            <i class="fa fa-line-chart" aria-hidden="true">&nbsp;&nbsp;&nbsp;Generar reporte&nbsp;&nbsp;&nbsp;
                            </i>
                        </button>
                    </div>
                </div>

                <div class="col-md-2 col-xs-6 col-sm-2" id="divBtnExport">
                    <label for="btnExport">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <button id="btnExport" type="button" onclick="exportarReporte()" class="form-control btn btn-info">
                        <li class="fa fa-file-excel-o">&nbsp;&nbsp;&nbsp;&nbsp;Exportar&nbsp;&nbsp;&nbsp;&nbsp;</li>
                    </button>
                </div>

            </div>

            <div class="row" id="div-row-filtros">

                <!-- <div class="" id="secDivFiltros"> -->
                <!-- <div class="row"> -->
                <!-- <div class="col-md-6 col-xs-6">
                    <input type="hidden" name="inpCantFiltros" id="inpCantFiltros" value="1">
                    <input type="hidden" name="inpFiltroAvanAct" id="inpFiltroAvanAct" value="0">
                    <textarea hidden id="jsonCampos" name="jsonCampos"></textarea>
                </div> -->
                <!-- </div> -->

                <div class="row margin-bottom">
                    <div class="col-md-12 col-sm-12">
                        <!-- [button] Condicion -->
                        <div class="col-md-2 col-xs-6 col-sm-3">
                            <button class="form-control btn btn-success btn-sm" id="btnNuevoFiltro" type="button" onclick="nuevaCondicion()">
                                <i class="fa fa-plus">&nbsp;&nbsp;&nbsp;&nbsp;Condición&nbsp;&nbsp;&nbsp;&nbsp;</i>
                            </button>
                        </div>
                        <!-- [button] Reiniciar condicion -->
                        <div class="col-md-2 col-xs-6 col-sm-3">
                            <button class="form-control btn btn-warning btn-sm" id="resetFiltradorAvanzado" type="button" onclick="clearFiltros(true)">
                                <i class="fa fa-refresh">&nbsp;&nbsp;&nbsp;&nbsp;Reiniciar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- <div id="busquedaAvanzada" class="panel-collapse collapse in"> -->
                <div class="container-fluid">
                    <div id="divFiltros">


                        <div class="row">
                            <div class="col-md-4 col-xs-5 col-sm-4">
                                <div class="form-group">
                                    <label>CAMPO</label>
                                </div>
                            </div>

                            <div class="col-md-4 col-xs-4 col-sm-4">
                                <div class="form-group">
                                    <label>OPERADOR</label>
                                </div>
                            </div>

                            <div class="col-md-4 col-xs-3 col-sm-4">
                                <div class="form-group">
                                    <label>VALOR</label>
                                </div>
                            </div>
                        </div>

                        <div class="row rows" numero="1" id="rowsFiltros">
                            <!-- <div class="col-md-4 col-xs-5 col-sm-4">
                                <div class="form-group">
                                    <select class="form-control input-sm campoFiltro" name="selCampo_1" id="selCampo_1" numero="1">
                                        <option value="0">Seleccione</option>
                                    </select>
                                </div>
                            </div> -->

                            <!-- CAMPO -->

                            <div id="div-filtros-container" data-index-number="1">

                                <div class="col-md-4 col-xs-5 col-sm-4">
                                    <div class="form-group">
                                        <select class="form-control input-sm campoFiltro" name="selCampo_1" id="selCampo_1" numero="1" data-index-number="1">
                                            <option value="0" tipo="">Seleccione</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4 col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <select class="form-control input-sm" name="selOperador_1" id="selOperador_1">
                                            <option value="0">Seleccione</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-xs-3 col-sm-4" id="div-col-valor_1">
                                    <div class="form-group" id="divValor_1">
                                        <input type="text" class="form-control input-sm campoValor" id="valor_1" name="valor_1" placeholder="Text">
                                    </div>
                                </div>
                                <input type="hidden" id="tipo_1" name="tipo_1" value="0">

                            </div>

                        </div>






                    </div>
                </div>
                <!-- </div> -->
                <!-- </div> -->


            </div>


            <ul class="nav nav-tabs">

                <input type="hidden" id="secTipoReporte" value="0">
                <li class="active">
                    <a href="#resultados" data-toggle="tab" id="reporte_tabla" class="reportesPantalla REPORTES" tipo="1">TABLA</a>
                </li>
                <!-- 
                <li class="">
                    <a href="#tabs_1" data-toggle="tab" id="reporte_pivot" class="reportesPantalla REPORTES" tipo="2">TABLA DINAMICA</a>
                </li> -->

                <!-- <li class="">
                    <a href="#tabs_2" data-toggle="tab" id="reporte_grafico" class="reportesPantalla REPORTES" tipo="3">GRAFICAS</a>
                </li> -->

            </ul>

            <div class="tab-content" id="content-result">
                <div class="tab-pane active" style="overflow-x: scroll;" id="resultados">
                    <!--  -->
                </div>

                <div class="tab-pane " style="overflow-x: scroll;" id="tabs_1">
                    <div id="pivotTable">
                        <!--  -->
                    </div>
                </div>

                <div class="tab-pane " style="overflow-x: scroll;" id="tabs_2">
                    <div class="box-body" id="lienzoGeneral">
                        <div class="row">
                            <div class="col-md-6">
                                <figure class="highcharts-figure">
                                    <div id="graficaBD_1"></div>
                                    <p class="highcharts-description">
                                    </p>
                                </figure>
                            </div>

                            <div class="col-md-6">
                                <figure class="highcharts-figure">
                                    <div id="graficaBD_2"></div>
                                    <p class="highcharts-description">
                                    </p>
                                </figure>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <figure class="highcharts-figure">
                                    <div id="graficaBD_3"></div>
                                    <p class="highcharts-description">
                                    </p>
                                </figure>
                            </div>

                            <div class="col-md-6">
                                <figure class="highcharts-figure">
                                    <div id="graficaBD_4"></div>
                                    <p class="highcharts-description">
                                    </p>
                                </figure>
                            </div>
                        </div>
                    </div>

                    <div class="box-body" id="lienzoCampanas">

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box-body">
                                    <table id="tabInOut" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Gestiones</th>
                                                <th>No contactable</th>
                                                <th>Devoluciones</th>
                                                <th>Sin gestion</th>
                                                <th>No contactado</th>
                                                <th>Contactado</th>
                                                <th>No efectivo</th>
                                                <th>Efectivo</th>
                                                <th>Duracion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="td_gestiones" class="tdInOut">0</td>
                                                <td id="td_no_contactable" class="tdInOut">0</td>
                                                <td id="td_devoluciones" class="tdInOut">0</td>
                                                <td id="td_sin_gestion" class="tdInOut">0</td>
                                                <td id="td_no_contactado" class="tdInOut">0</td>
                                                <td id="td_contactado" class="tdInOut">0</td>
                                                <td id="td_no_efectivo" class="tdInOut">0</td>
                                                <td id="td_efectivo" class="tdInOut">0</td>
                                                <td id="td_duracion" class="tdInOut">0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabInIn" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Ofrecidas</th>
                                                <th>ASA</th>
                                                <th>Aban</th>
                                                <th>Aban_despues_tsf</th>
                                                <th>Aban_porcentaje</th>
                                                <th>Contestadas</th>
                                                <th>Cont_despues_tsf</th>
                                                <th>Cont_porcentaje</th>
                                                <th>TSF</th>
                                                <th>AHT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="td_ofrecidas" class="tdInIn">0</td>
                                                <td id="td_asa" class="tdInIn">0</td>
                                                <td id="td_aban" class="tdInIn">0</td>
                                                <td id="td_aban_despues_tsf" class="tdInIn">0</td>
                                                <td id="td_aban_porcentaje" class="tdInIn">0</td>
                                                <td id="td_contestadas" class="tdInIn">0</td>
                                                <td id="td_cont_despues_tsf" class="tdInIn">0</td>
                                                <td id="td_cont_porcentaje" class="tdInIn">0</td>
                                                <td id="td_tsf" class="tdInIn">0</td>
                                                <td id="td_aht" class="tdInIn">0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="graficaSC_1"></div>
                                    <p class="highcharts-description">
                                    </p>
                                </figure>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="graficaSC_2"></div>
                                    <p class="highcharts-description">
                                    </p>
                                </figure>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="graficaSC_3"></div>
                                    <p class="highcharts-description">
                                    </p>
                                </figure>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="graficaSC_4"></div>
                                    <p class="highcharts-description">
                                    </p>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- Termina Herramientas  -->

<!-- type="text/javascript"  -->
<script type="text/javascript">
    //JDBD - Llenamos las lista de reportes
    const URL = "<?= $url_crudG2 ?>";
    const intIdHuesped_t = <?= $_SESSION["HUESPED"] ?>;
    const base_url = "<?= base_url ?>"

    let jsonCamposReporte = [];
    let contadorFiltros = new Number(2);

    const condicionalesFiltros = [{
            text: 'IGUAL A',
            value: '='
        },
        {
            text: 'DIFERENTE DE',
            value: '!'
        }
    ]
    // $(function() {
    //     $('.fecha').datepicker({
    //         language: "es",
    //         autoclose: true,
    //         todayHighlight: true,
    //         format: 'yyyy-mm-dd'
    //     }).datepicker("setDate", new Date());

    // })


    const rowsFiltros = document.getElementById('rowsFiltros')
    const fragment = document.createDocumentFragment()


    window.onload = () => {
        // const inputDate = document.querySelectorAll('input[type="date"]');
        // console.log('inputDae :>> ', inputDate);
        // obtenerTiposReportes()
        pintarTiposReportes()
        // changeCampoFiltro()
        // addDateInput(inputDate)
    }

    const pintarAdherencias = (elementSelect) => {
        const selectTiposReportes = document.getElementById('adherenciasAgente')
        const adharencias = [{
                nombre: 'SESIONES',
                tipo: 1
            }, {
                nombre: 'PAUSAS',
                tipo: 2
            }, {
                nombre: 'PAUSAS - DURACION POR AGENTE',
                tipo: 'pausas'
            }, {
                nombre: 'NO SE REGISTRARON',
                tipo: 3
            },
            {
                nombre: 'LLEGARON TARDE',
                tipo: 4
            },
            {
                nombre: 'LLEGARON A TIEMPO',
                tipo: 5
            },
            {
                nombre: 'SE FUERON ANTES',
                tipo: 6
            },
            {
                nombre: 'SE FUERON A TIEMPO',
                tipo: 7
            },
            {
                nombre: 'SESIONES CORTAS',
                tipo: 8
            },
            {
                nombre: 'SESIONES DURACION OK',
                tipo: 9
            },
            {
                nombre: 'PAUSAS CON HORARIO MUY LARGAS',
                tipo: 10
            },
            {
                nombre: 'PAUSAS CON HORARIO DURACION OK',
                tipo: 11
            },
            {
                nombre: 'PAUSAS CON HORARIO INCUMPLIDAS',
                tipo: 12
            },
            {
                nombre: 'PAUSAS CON HORARIOS CUMPLIDAS',
                tipo: 13
            },
            {
                nombre: 'PAUSAS SIN HORARIO MUY LARGA',
                tipo: 14
            },
            {
                nombre: 'PAUSAS SIN HORARIO MUCHAS VECES',
                tipo: 15
            },
            {
                nombre: 'PAUSAS SIN HORARIO OK',
                tipo: 16
            },
            {
                nombre: 'AGENTES SIN MALLA DEFINIDA',
                tipo: 17
            },
        ]

        adharencias.forEach((item, index) => {
            const option = document.createElement('option')

            let value = typeof item === 'object' ? item.tipo : index++
            let text = typeof item === 'object' ? item.nombre : item

            option.text = text
            option.value = value
            elementSelect.appendChild(option)

        });



    }

    const limpiarReporte = () => {
        console.log('Se ejecuta la funcion limpiarReporte :>> ', );
        return new Promise(resolve => {
            const div_result = document.getElementById('resultados')

            resolve(div_result.textContent = "")
        })
        // const content_result = document.getElementById('content-result')

        // console.log(div_result)
        //console.log(content_result.parentNode.removeChild(div_result))
    }

    const validarFiltros = (value) => {

        value > 2 ? document.getElementById('div-row-filtros').style.display = "none" : document.getElementById('div-row-filtros').style.display = "block"
    }


    const pintarTiposReportes = async () => {
        const event = new Event('change')
        const selectTiposReportes = document.getElementById('tipoReporte')
        // const URLAgente = "http://localhost/manager/cruds/DYALOGOCRM_SISTEMA/G1/reportesAgente/index.php"



        const tiposReportes = [
            'GESTIONES',
            'ADHERENCIA AL TURNO',
            'CALIDAD'
        ]

        pintarAdherencias(selectTiposReportes)


        selectTiposReportes.value = "condia"

        selectTiposReportes.onchange = async (e) => {
            console.log('cambio :>> ', );
            // const value = e.target.value === 'condia' || e.target.value === 'calidad' ? obtenerCamposTiposReportes(URLAgente, e.target.value) : obtenerCamposTiposReportes(URLAdherencias, e.target.value)

            obtenerCamposTiposReportes(e.target.value)

            filtrosPorDefecto(e.target.value)
            validarFiltros(e.target.value)
            await limpiarReporte()

        }

        selectTiposReportes.dispatchEvent(event)




        // tiposReportes.forEach((item, index) => {
        //     const optgroup = document.createElement('optgroup')
        //     let value = index
        //     let text = item

        //     optgroup.value = value
        //     optgroup.text = index

        // });




    }

    const obtenerCamposTiposReportes = (value) => {
        console.log(' event change option:>> ', value);

        $.ajax({
            url: `${URL}?traerCamposDelReporte=true`,
            type: 'POST',
            data: {
                tipoReport_t: value
            },
            dataType: 'JSON',
            context: document.body,
            global: false,
            async: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: `<img src="${base_url}assets/img/clock.gif"/> PROCESANDO PETICION`

                })
            },
            success: function(data) {

                console.log('data :>> ', data);
                jsonCamposReporte = data
                clearFiltros(true);
                pintarOptionsSelCampo_1(data)
            },
            complete: function() {
                $.unblockUI();
            },
            error: function() {
                alertify.error(`Ocurrio un error al procesar la solicitud ${data}`)
                // $.unblockUI();
            }
        });


    }


    const limpiarSelect = (select) => {
        for (let i = select.options.length; i >= 1; i--) {
            console.log('i :>> ', i);
            select.remove(i);
        }
    };

    const pintarOptionsSelCampo_1 = async (data) => {
        // const selCampo_1 = document.getElementById('selCampo_1')

        // const rowsFiltros = document.getElementById('rowsFiltros')

        try {
            // rowsFiltros.innerHTML = ""
            // const clone = templateCampo.cloneNode(true)
            const selCampo_1 = document.querySelectorAll('#selCampo_1');

            // console.log('selCampo_1 :>> ', selCampo_1);

            selCampo_1.forEach(element => {
                // console.log(element)
                limpiarSelect(element)

                data.forEach((item, index) => {


                    const option = document.createElement('option')

                    // console.log('selcampo_1 :>> ', selCampo_1);
                    // console.log('clone :>> ', clone);
                    let value = item.campoId
                    let text = item.nombre

                    option.value = value
                    option.text = text
                    option.setAttribute('tipo', `${item.tipo}`)
                    element.appendChild(option)
                    // fragment.appendChild(clone)
                });

            })


            await changeCampoFiltro()
            console.log('se termina de cargar pintarOptionsSelCampo_1[data] :>> ', data);
            // agregarOptionsSelOperador()
            // rowsFiltros.appendChild(fragment)

        } catch (error) {
            alertify.error(`Algo salio mal ${error}`)
        }
    }



    const filtrosPorDefecto = async (strTipoReporte_p) => {

        console.log('filtrosPorDefecto->strTipoReporte_p :>> ', strTipoReporte_p);
        // let intOpcion_t = 3;
        const intOpcion_t = strTipoReporte_p === "pausas" ? 2 : 3
        const eventChange = new Event('change')
        // const eventClick = new Event('click')
        const selCampo_1 = document.getElementById('selCampo_1')
        const selOperador_1 = document.getElementById('selOperador_1')
        const valor_1 = document.getElementById('valor_1')
        // const btnNuevoFiltro = document.getElementById('btnNuevoFiltro')
        const filtroDefault_t = document.querySelector(`#selCampo_1 option:nth-child(${intOpcion_t})`);
        let today = moment().format('YYYY-MM-DD');


        // if (strTipoReporte_p == "pausas") {
        //     intOpcion_t = 2;
        // }



        if (intOpcion_t != 0 && filtroDefault_t != null) {

            console.log('intOpcion_t :>> ', intOpcion_t);

            selCampo_1.value = filtroDefault_t.value
            // selCampo_1.value = filtroDefault_t
            selCampo_1.dispatchEvent(eventChange)

            selOperador_1.value = ">="
            selOperador_1.dispatchEvent(eventChange)

            // $("#selCampo_1").val(filtroDefault_t).trigger("change");

            // $("#selOperador_1").val(">=").trigger("change");

            // $("#valor_1").val(today);
            valor_1.value = today

            await nuevaCondicion();

            // btnNuevoFiltro.dispatchEvent(eventClick)

            const selCampo_2 = document.getElementById('selCampo_2')
            const selOperador_2 = document.getElementById('selOperador_2')
            const valor_2 = document.getElementById('valor_2')

            selCampo_2.value = filtroDefault_t.value
            selCampo_2.dispatchEvent(eventChange)

            selOperador_2.value = "<="
            selOperador_2.dispatchEvent(eventChange)

            // $("#selCampo_2").val(filtroDefault_t).trigger("change");
            // $("#selOperador_2").val("<=").trigger("change");

            valor_2.value = today

        }

    }

    const clearFiltros = async (bool) => {
        console.log('clearFiltros -> bool :>> ', bool);
        const event = new Event('change')
        const D = new DOM()

        const div_container = document.querySelectorAll('#div-filtros-container')
        const tipo_1 = document.getElementById('tipo_1')
        const selCampo_1 = document.getElementById('selCampo_1')
        const selOperador_1 = document.getElementById('selOperador_1')
        const div_col_valor_1 = document.getElementById('div-col-valor_1')





        div_container.forEach(element => {
            // console.log('element :>> ', element);
            let index = element.getAttribute('data-index-number')
            console.log('index :>> ', index);
            if (index === '1') {
                tipo_1.value = "0"

                contadorFiltros = new Number(2)
                selCampo_1.value = '0'
                selOperador_1.value = '='
                selOperador_1.dispatchEvent(event)

                div_col_valor_1.remove()
                crearValor({
                    D: D,
                    div_principal_row: document.getElementById('div-filtros-container'),
                    contador: 1
                })

            } else {

                element.remove()
            }
        });
        await limpiarReporte()

    }




    const crearSelCondicion = (objecto) => {


        // const D = new DOM();


        /** selCondicion_ */
        //crear el div que contiene los 2 sub-divs

        // const div_principal_row = objecto.D.create('div', {
        //     id: 'div-filtros-container'
        // });



        const div_principal_select_condicion = objecto.D.create('div', {
            id: 'nombrexx'
        });
        div_principal_select_condicion.classList.add("col-md-2", "col-xs-4", "col-sm-2");


        // console.log(' div_principal_select_condicion:>> ', div_principal_select_condicion);
        //crear el div para el span e input del nombre
        const div_select_condicion_form_group = objecto.D.create('div');
        div_select_condicion_form_group.classList.add("form-group");


        //crear los inputs de nombre y apellido
        // 
        const select_condicion = objecto.D.create('select', {
            name: `selCondicion_${objecto.contador}`,
            id: `selCondicion_${objecto.contador}`,
        });
        select_condicion.classList.add("form-control", "input-sm")

        const select_condicion_option_AND = objecto.D.create('option', {
            value: 'AND',
            text: 'Y'
        })
        // strHTML_t += '<option value="AND">Y</option>';
        // strHTML_t += '<option value="OR">O</option>';

        const select_condicion_option_OR = objecto.D.create('option', {
            value: 'OR',
            text: 'O'
        })

        select_condicion.appendChild(select_condicion_option_AND)
        select_condicion.appendChild(select_condicion_option_OR)





        //crear el div para el span e input del apellido
        const div_apellido = objecto.D.create('div');

        //crear los span de nombre y apellido
        const span_nombre = objecto.D.create('span', {
            innerHTML: 'Nombre'
        });
        const span_apellido = objecto.D.create('span', {
            innerHTML: 'Apellido'
        });





        const input_apellido = objecto.D.create('input', {
            type: 'text',
            name: 'apellidos[]',
            autocomplete: 'off',
            placeholder: 'Apellido del usuario'
        });

        //crear un botoncito de eliminar este div 
        const borrar = objecto.D.create('a', {
            href: 'javascript:void(0)',
            innerHTML: 'Eliminar',
            onclick: function() {
                objecto.D.remove(objecto.div_principal_row);
            }
        });

        //agregar cada etiqueta a su nodo padre
        objecto.D.append([div_principal_select_condicion], objecto.div_principal_row);
        objecto.D.append([div_select_condicion_form_group], div_principal_select_condicion);
        objecto.D.append([select_condicion], div_select_condicion_form_group);


        //agregar el div del primer comentario al contenedor con id #container
        objecto.D.append(objecto.div_principal_row, objecto.D.id('rowsFiltros'));


    }


    const crearSelCampo = (objecto) => {
        /** selCampo_*/

        const data = jsonCamposReporte

        const div_principal_select_campo = objecto.D.create('div', {
            id: `select_campo_${objecto.contador}`
        });
        div_principal_select_campo.classList.add("col-md-3", "col-xs-7", "col-sm-3");


        const div_select_campo_form_group = objecto.D.create('div');
        div_select_campo_form_group.classList.add("form-group");


        //crear los inputs de nombre y apellido
        // 

        const select_campo = objecto.D.create('select', {
            name: `selCampo_${objecto.contador}`,
            id: `selCampo_${objecto.contador}`,
        });
        // select_campo.setAttribute('numero', `${objecto.contador}`)
        select_campo.setAttribute('data-index-number', `${objecto.contador}`)
        select_campo.classList.add("form-control", "input-sm", "campoFiltro")


        const select_condicion_option = objecto.D.create('option', {
            value: `0`,
            text: `Seleccione`
        })

        select_campo.appendChild(select_condicion_option)

        data.forEach(item => {

            const select_condicion_option_1 = objecto.D.create('option', {
                value: `${item.campoId}`,
                text: `${item.nombre}`
            })

            select_campo.appendChild(select_condicion_option_1)
            select_condicion_option_1.setAttribute('tipo', `${item.tipo}`)
        });




        // const json = jsonCamposReporte



        // console.log('selcampo_1 :>> ', selCampo_1);

        // console.log('selcampo_1 :>> ', selCampo_1);




        // select_condicion.appendChild(select_campo_option)




        //agregar cada etiqueta a su nodo padre
        // D.append(div_select_campo_form_group);
        // D.append(select_campo, div_select_campo_form_group);
        objecto.D.append([div_principal_select_campo], objecto.div_principal_row);
        objecto.D.append([div_select_campo_form_group], div_principal_select_campo);

        objecto.D.append([select_campo], div_select_campo_form_group);

        //agregar el div del primer comentario al contenedor con id #container
        objecto.D.append(objecto.div_principal_row, objecto.D.id('rowsFiltros'));

    }

    const crearSelOperador = (objecto) => {

        /** selOperador_ */

        const div_principal_select_operador = objecto.D.create('div', {
            id: `select_operador${objecto.contador}`
        });
        div_principal_select_operador.classList.add("col-md-3", "col-xs-7", "col-sm-3");


        const div_select_operador_form_group = objecto.D.create('div');
        div_select_operador_form_group.classList.add("form-group");


        //crear los inputs de nombre y apellido
        // 
        const select_operador = objecto.D.create('select', {
            name: `selOperador_${objecto.contador}`,
            id: `selOperador_${objecto.contador}`,
        });

        const select_operador_option = objecto.D.create('option', {
            value: '0',
            text: 'Seleccione'
        })
        select_operador.appendChild(select_operador_option)
        select_operador.classList.add("form-control", "input-sm")





        //agregar cada etiqueta a su nodo padre
        // objecto.D.append(div_select_operador_form_group);
        // objecto.D.append(select_operador, div_select_operador_form_group);
        objecto.D.append([div_principal_select_operador], objecto.div_principal_row);
        objecto.D.append([div_select_operador_form_group], div_principal_select_operador);

        objecto.D.append([select_operador], div_select_operador_form_group);

        //agregar el div del primer comentario al contenedor con id #container
        objecto.D.append(objecto.div_principal_row, objecto.D.id('rowsFiltros'));

    }

    const crearValor = (objecto) => {

        /** valor_ */


        const div_principal_input_valor = objecto.D.create('div', {
            id: `div-col-valor_${objecto.contador}`
        });
        div_principal_input_valor.classList.add("col-md-3", "col-xs-3", "col-sm-3");


        const div_input_valor_form_group = objecto.D.create('div');
        div_input_valor_form_group.classList.add("form-group");
        div_input_valor_form_group.setAttribute('id', `divValor_${objecto.contador}`)


        //crear los inputs de nombre y apellido
        // 
        const input_valor = objecto.D.create('input', {
            type: 'text',
            name: `valor_${objecto.contador}`,
            autocomplete: 'off',
            placeholder: 'Text',
            id: `valor_${objecto.contador}`,
        });
        input_valor.classList.add("form-control", "input-sm", "campoValor")





        //agregar cada etiqueta a su nodo padre
        // objecto.D.append(div_input_valor_form_group);
        // objecto.D.append(input_valor, div_input_valor_form_group);

        objecto.D.append([div_principal_input_valor], objecto.div_principal_row);
        objecto.D.append([div_input_valor_form_group], div_principal_input_valor);

        objecto.D.append([input_valor], div_input_valor_form_group);

        //agregar el div del primer comentario al contenedor con id #container
        objecto.D.append(objecto.div_principal_row, objecto.D.id('rowsFiltros'));

    }

    const crearBtnQuitarFiltro = (objecto) => {

        /** btnQuitarFiltro_ */


        const div_principal_btn_eliminar = objecto.D.create('div', {
            id: `btnQuitarFiltro_${objecto.contador}`
        });
        div_principal_btn_eliminar.classList.add("col-md-1", "col-xs-2", "col-sm-1");


        const btn_eliminar = objecto.D.create('button', {
            type: "button",
            id: `btnQuitarFiltro_${objecto.contador}`,
            href: 'javascript:void(0)',
            onclick: function() {
                objecto.D.remove(objecto.div_principal_row);
            }
        });
        btn_eliminar.classList.add("form-control", "btn", "btn-danger", "btn-sm", "EliminarFiltro");



        //crear los inputs de nombre y apellido
        // 
        const i_eliminar = objecto.D.create('i');
        i_eliminar.classList.add("fa", "fa-trash-o")







        //agregar cada etiqueta a su nodo padre
        // objecto.D.append(btn_eliminar);
        // objecto.D.append(i_eliminar, btn_eliminar);
        objecto.D.append([div_principal_btn_eliminar], objecto.div_principal_row);
        objecto.D.append([btn_eliminar], div_principal_btn_eliminar);

        objecto.D.append([i_eliminar], btn_eliminar);

        //agregar el div del primer comentario al contenedor con id #container
        objecto.D.append(objecto.div_principal_row, objecto.D.id('rowsFiltros'));



    }

    const crearHiddenTipoInput = (objecto) => {
        const input_hidden_tipo = objecto.D.create('input', {
            type: 'hidden',
            id: `tipo_${objecto.contador}`,
            name: `tipo_${objecto.contador}`
        })

        objecto.D.append([input_hidden_tipo], objecto.div_principal_row);
        //agregar el div del primer comentario al contenedor con id #container
        objecto.D.append(objecto.div_principal_row, objecto.D.id('rowsFiltros'));


    }

    const nuevaCondicion = async () => {

        // const intCantFiltros = 2

        const intCantFiltros = contadorFiltros++

        const D = new DOM();

        // var intIdFormulario_t = $("#inpIdFormulario").val();


        const div_principal_row = D.create('div', {
            id: 'div-filtros-container'
        });
        div_principal_row.setAttribute('data-index-number', `${intCantFiltros}`)

        const objecto = {
            D: D,
            div_principal_row: div_principal_row,
            contador: intCantFiltros
        }

        // selCondicion_
        crearSelCondicion(objecto)
        // selCampo_
        crearSelCampo(objecto)
        // selOperador_
        crearSelOperador(objecto)
        // valor_
        crearValor(objecto)
        // btnQuitarFiltro_
        crearBtnQuitarFiltro(objecto)
        // tipo hidden input
        crearHiddenTipoInput(objecto)

        await changeCampoFiltro();

    }


    const generarReportesAgente = async () => {

        // const arrDataFil_t = new Array();

        const id_usuari = document.getElementById('id_usuari').value
        const tipoReport_t = document.getElementById('tipoReporte').value;
        const id_agente_telefonia = document.getElementById('id_agente').value
        const huesped_id = intIdHuesped_t

        const {
            items
        } = {
            items: await obtenerElements()
        }
        const intErrores_t = tipoReport_t > 2 ? 0 : await validarErrors() ?? 0
        const intFilas_t = 0;
        const intLimite_t = 50;
        const intPaginaActual_t = 1;

        const arrNumerosFiltros_t = await obtenerFiltros();




        // var intIdEstrat_t = $("#sql_query option:selected").attr("idEstrat");
        // var intIdBd_t = $("#sql_query option:selected").attr("idbd");
        // var intIdPaso_t = $("#sql_query option:selected").attr("idPaso");
        // var intIdTipo_t = $("#sql_query option:selected").attr("idTipo");
        // var intIdGuion_t = $("#sql_query option:selected").attr("idguion");
        // var intIdCBX_t = $("#sql_query option:selected").attr("idcampancbx");
        // var intIdPeriodo_t = $("#sql_query option:selected").attr("periodo");
        // var intIdMuestra_t = $("#sql_query option:selected").attr("idmuestra");

        const objDataReport_t = {
            tipoReport_t: tipoReport_t,
            intIdHuesped_t: intIdHuesped_t,
            // intIdEstrat_t: "709b56147c6aa5a629864b5cf946c65f",
            // intIdBd_t: intIdBd_t,
            // intIdTipo_t: intIdTipo_t,
            // intIdGuion_t: intIdGuion_t,
            // intIdCBX_t: intIdCBX_t,
            // intIdPeriodo_t: intIdPeriodo_t,
            // intIdMuestra_t: intIdMuestra_t,
            // intIdPaso_t: intIdPaso_t,
            strLimit_t: "si",
            intFilas_t: intFilas_t,
            intLimite_t: intLimite_t,
            intPaginaActual_t: intPaginaActual_t,
            id_agente_telefonia: id_agente_telefonia,
            id_usuari: id_usuari
        };

        // const form = $("#divFiltros #rowsFiltros > input, #divFiltros #rowsFiltros .form-group > input, #divFiltros #rowsFiltros .form-group > select")






        // console.log("arrDataFil_t", arrDataFil_t);





        // div_filtro_filas.forEach(row => {
        //     arrNumerosFiltros_t.push(row.dataset.indexNumber)
        // });

        console.log('intErrores_t :>> ', intErrores_t);

        console.log('items :>> ', items);



        const data = Object.assign(objDataReport_t, items, {
            totalFiltros: arrNumerosFiltros_t,
            // intErrores_t: intErrores_t
        });

        intErrores_t === 0 ? postDatosReporte(data, 'report.php?reporteAgente=true') : NaN

    }


    // obtener todos los elemtos de los filtros.
    const obtenerElements = () => {
        return new Promise(resolve => {

            const elements = document.querySelectorAll('#divFiltros #rowsFiltros > input, #divFiltros #rowsFiltros .form-group > input, #divFiltros #rowsFiltros .form-group > select, #divFiltros #rowsFiltros #div-filtros-container > input');


            let items = {}
            elements.forEach(element => {
                let key = element.id
                let value = element.value
                items[key] = value

                // console.log('element->value :>> ', key);
                // console.log('element->id :>> ', value);
                // console.log('items :>> ', items);

            });

            // console.log('items :>> ', items);

            resolve(items)
        })

    }


    const obtenerFiltros = () => {

        return new Promise(resolve => {
            const div_filtro_filas = document.querySelectorAll('#div-filtros-container');
            const arrNumerosFiltros_t = []

            div_filtro_filas.forEach(row => {
                arrNumerosFiltros_t.push(row.dataset.indexNumber)
            });

            resolve(arrNumerosFiltros_t)
        })

    }

    const exportarReporte = async () => {
        const id_agente_telefonia = document.getElementById('id_agente').value
        const id_usuari = document.getElementById('id_usuari').value
        const tipoReport_t = document.getElementById('tipoReporte').value;
        const arrNumerosFiltros_t = await obtenerFiltros();
        const huesped_id = intIdHuesped_t
        const intErrores_t = tipoReport_t > 2 ? 0 : await validarErrors() ?? 0
        const {
            items
        } = {
            items: await obtenerElements()
        }
        // data = data.exportAgente = 'si'
        console.log('items :>> ', items);

        // const data = JSON.stringify({
        //     tipoReport_t: tipoReport_t,
        //     intIdHuesped_t: huesped_id,
        //     exportAgente: 'si',
        //     jsonCondiciones: items,
        //     jsonTotalFil: arrNumerosFiltros_t,
        // })

        console.log(':>> ', JSON.stringify(items));

        console.log(' :>> ', JSON.stringify(arrNumerosFiltros_t));

        const data = {
            tipoReport_t: tipoReport_t,
            intIdHuesped_t: huesped_id,
            exportAgente: 'si',
            jsonCondiciones: JSON.stringify(items),
            // jsonCondiciones: items,
            // jsonTotalFil: arrNumerosFiltros_t,
            jsonTotalFil: JSON.stringify(arrNumerosFiltros_t),
            id_agente_telefonia: id_agente_telefonia,
            id_usuari: id_usuari,
        }



        console.log('data exportar report :>> ', data);
        // postDatosReporte()

        intErrores_t === 0 ? await postEnviarDatosForm(await data) : NaN

    }

    const postEnviarDatosForm = async (data) => {
        let form = document.createElement('form');
        form.action = `${base_url}pages/charts/report.php`;
        form.method = 'POST';
        form.id = "export-form"

        console.log('data :>> ', data);
        for (const key in data) {
            if (Object.hasOwnProperty.call(data, key)) {
                const element = data[key];
                document.getElementsByName(`${key}`).value = `${element}`
                // console.log('element :>> ', element);
                // console.log('key :>> ', key);
                form.innerHTML += `<input type="hidden" name="${key}" id="${key}" value='${element}'>`;
            }
        }

        // el formulario debe estar en el document para poder enviarlo
        await document.body.append(form);

        await form.submit();

    }

    const postDatosReporte = (data, params) => {

        console.log('data :>> ', data);
        // console.log('intLimite_p :>> ', intLimite_p);
        // const urlReporte = `reporteAgente=true`
        // const urlPaginar = `Paginado=true`
        const URL = `${base_url}pages/charts/${params}`

        console.log('URL :>> ', URL);

        $.ajax({
            url: `${URL}`,
            type: 'post',
            data: data,
            success: function(data) {

                pintarResultaReport(data)
                // $("#selIntLimite_t").val(intLimite_p).trigger("change");
            },
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_g; ?>'
                });
            },
            complete: function() {
                $.unblockUI();
            }
        });


    }


    const pintarResultaReport = (data) => {

        console.log('data -> pintarResultaReport :>> ', data);

        const resultados = document.getElementById('resultados').innerHTML = data

    }

    // paginar table 
    const paginar = (intTipoPaginado_p, intPagina_p = null) => {


        console.log('intTipoPaginado_p :>> ', intTipoPaginado_p);
        console.log('intPagina_p :>> ', intPagina_p);

        const intLimite_t = document.getElementById('intLimite_t').value
        const intCantidadPaginas_t = document.getElementById('intCantidadPaginas_t').value
        const intRegistrosTotal_t = document.getElementById('intRegistrosTotal_t').value
        const strtxtConsulta_t = document.getElementById('strtxtConsulta_t').value
        let intFilas_t = document.getElementById('intFilas_t').value
        let intPaginaActual_t = document.getElementById('intPaginaActual_t').value


        switch (intTipoPaginado_p) {
            case "A":
                // paginado con el boton anterior
                if (intTipoPaginado_p === "A") {
                    intFilas_t = parseInt(intFilas_t, 0) - parseInt(intLimite_t, 0);
                    intPaginaActual_t = parseInt(intPaginaActual_t, 0) - 1;
                }
                break;
            case "B":
                // paginado con el boton siguiente
                if (intTipoPaginado_p === "B") {
                    intFilas_t = parseInt(intFilas_t, 0) + parseInt(intLimite_t, 0);
                    intPaginaActual_t = parseInt(intPaginaActual_t, 0) + 1;
                }
                break;

            default:
                // paginado haciendo click en las paginas
                if (intPagina_p) {
                    if (parseInt(intPagina_p, 0) > 1) {
                        intFilas_t = (parseInt(intPagina_p, 0) - 1) * parseInt(intLimite_t, 0);
                    } else {
                        intFilas_t = 0;
                    }

                    intPaginaActual_t = parseInt(intPagina_p, 0);
                }
                break;
        }


        const objDataReport_t = {
            consulta: strtxtConsulta_t,
            intFilas_t: intFilas_t,
            intRegistrosTotal_t: intRegistrosTotal_t,
            intCantidadPaginas_t: intCantidadPaginas_t,
            intLimite_t: 50,
            intPaginaActual_t: intPaginaActual_t
        };


        postDatosReporte(objDataReport_t, 'report.php?Paginado=true')
    }


    const validarErrors = () => {



        return new Promise(resolve => {


            // const campoFiltro = document.querySelectorAll('.campoFiltro')
            const campoFiltroAll = document.getElementsByClassName("campoFiltro");

            let intErrores_t = 0

            // campoFiltro.forEach(element => {

            for (const key in campoFiltroAll) {
                if (Object.hasOwnProperty.call(campoFiltroAll, key)) {
                    const element = campoFiltroAll[key];
                    console.log('se ejecuta la function validarErrors :>> ', );
                    if (element.value === "0" && element.value !== "") {
                        intErrores_t++;
                        alertify.error("Debe seleccionar el campo a filtrar.");
                        element.closest('.form-group').classList.add("has-error")
                        console.log('element.closest() :>> ', );
                    } else {
                        element.closest('.form-group').classList.remove('has-error')
                    }

                }
            }


            // });

            // console.log('validarCampoValor :>> ', validarCampoValor(campoValor, intErrores_t));


            resolve(validarCampoValor(intErrores_t) ?? 0)

        });



    }

    const validarCampoValor = (intErrores_t) => {

        console.log('element.type se llama validarCampoValor :>> ');

        return new Promise(resolve => {

            const campoValor = document.querySelectorAll('.campoValor')

            campoValor.forEach(element => {
                console.log('element.type se llama validarCampoValor :>> ', element.type);
                switch (element.type) {
                    case "text":
                        if (element.type === "text" && element.value === "") {
                            intErrores_t++;
                            alertify.error("Debe diligenciar el campo.");
                            element.closest('.form-group').classList.add('has-error')
                        } else {
                            element.closest('.form-group').classList.remove('has-error')
                        }
                        console.log('element.type dentro del case :>> ', element.type);
                        break;
                    case "select-one":

                        if (!element.value === "" || element.value === "") {
                            intErrores_t++;
                            alertify.error("Debe diligenciar el campo.");
                            element.closest('.form-group').classList.add("has-error")
                        } else {
                            element.closest('.form-group').classList.remove('has-error')
                        }

                        console.log('element.type :>> ', element.type);
                        break;

                    default:
                        console.log('element.type :>> ', element.type);
                        break;
                }

                // if (element.type === "text" && element.value === "") {
                //     intErrores_t++;
                //     alertify.error("Debe diligenciar el campo.");
                //     element.closest('.form-group').classList.add('has-error')
                // } else if (element.type === "select-one") {
                //     if (element.value == -1 || element.value == null) {
                //         alertify.error("Debe diligenciar el campo.");
                //         element.closest('.form-group').classList.add("has-error")
                //     }
                // }

            });

            resolve(intErrores_t)
        })


    }




    class DOM {

        constructor() {}

        id = str => document.getElementById(str);

        query = (regla_css, one = false) =>
            one === true ?
            document.querySelector(regla_css) :
            document.querySelectorAll(regla_css);

        create = (str, props = {}) => {
            // console.log('props :>> ', props);
            // console.log('str :>> ', str);
            return Object.assign(document.createElement(str), props);
        }

        append = (hijos, padre = document.body) => {

            // console.log('padre :>> ', padre);
            // console.log('hijos :>> ', hijos);

            hijos.length ? hijos.map(hijo => padre.appendChild(hijo)) : padre.appendChild(hijos);
        }

        remove = e => e.remove();
    }

    // const DOM = function() {
    //     this.id = str => document.getElementById(str);
    //     this.query = (regla_css, one = false) =>
    //         one === true ?
    //         document.querySelector(regla_css) :
    //         document.querySelectorAll(regla_css);


    //     this.create = (str, props = {}) => {
    //         // console.log('props :>> ', props);
    //         // console.log('str :>> ', str);
    //         return Object.assign(document.createElement(str), props);
    //     }

    //     this.append = (hijos, padre = document.body) => {

    //         // console.log('padre :>> ', padre);
    //         // console.log('hijos :>> ', hijos);

    //         hijos.length ? hijos.map(hijo => padre.appendChild(hijo)) : padre.appendChild(hijos);
    //     }

    //     this.remove = e => e.remove();
    // }






    // const btn_agregar = document.getElementById('agregar');
    // btn_agregar.addEventListener("click", function() {

    // });




    const changeCampoFiltro = () => {

        // console.log('intCampo -> changeCampoFiltro :>> ', intCampo);

        const campoFiltroAll = document.getElementsByClassName("campoFiltro");

        console.log('campoFiltroAll changeCampoFiltro :>> ', campoFiltroAll);
        // console.log('campoFiltroAll -> selCampo_1 :>> ', campoFiltroAll.selCampo_1);
        const event = new Event('change')


        for (const key in campoFiltroAll) {
            if (Object.hasOwnProperty.call(campoFiltroAll, key)) {
                const element = campoFiltroAll[key];

                // console.log('element->key :>> ', key);
                console.log('changeCampoFiltro->element :>> ', element);
                element.onchange = async (e) => {
                    await changeValorCampos(e)
                    await quitarClaseError();
                }
            }
        }

        // campoFiltroAll.forEach(element => {

        // });



        // element.dispatchEvent(event)
        // });


    }

    const changeValorCampos = (value) => {

        console.log('changeCampoFiltro->change :>> ', );

        // var value = $(this).val();
        // var numero = $(this).attr("numero");

        const numero = value.target.dataset.indexNumber

        // parseInt(, 0)

        console.log('numero :>> ', numero);
        // console.log('e.target :>> ', e.target);
        // console.log('e.srcElement :>> ', e.srcElement);
        // console.log('numero :>> ', e);
        // let numero = 1
        const tipoReporte = document.getElementById('tipoReporte').value
        const selectCampo = document.getElementById(`selCampo_${numero}`)
        const optionCampo = selectCampo.options[selectCampo.selectedIndex]
        const tipo = optionCampo.getAttribute('tipo')
        const tipo_input_hidden = document.getElementById(`tipo_${numero}`).value = tipo
        const id_usuari = document.getElementById('id_usuari').value
        let divValor = document.getElementById(`divValor_${numero}`).innerHTML


        // const tipo = $(`#selCampo_${numero} option:selected`).attr("tipo");
        // const idbg = $(`#selCampo_${numero} option:selected`).attr("idbg");
        // const idpregun = $(`#selCampo_${numero} option:selected`).attr("idpregun");

        // $(`#tipo_${numero}`).val(tipo);

        let strHTMLValor_t = '';

        let strHTMLOperador_t = '<option value="=" selected>IGUAL A</option>';
        strHTMLOperador_t += '<option value="<>">DIFERENTE DE</option>';

        console.log('tipo->campo antes del switch :>> ', tipo);
        // const booValue = removeElementChild({
        //     // contador: contador,
        //     elementChild: '*',
        //     elementFatherId: `divValor_${numero}`
        // })

        switch (tipo) {
            case 'estado':
                if (tipo == "estado") {

                    strHTMLValor_t += `<select class="form-control input-sm campoValor" name="valor_${numero}" id="valor_${numero}">';`
                    // strHTMLValor_t += traerOpcionesLista(idbg, "estado");
                    strHTMLValor_t += '</select>';

                    $("#divValor_" + numero).html(strHTMLValor_t);

                }
                break;
            case 'ultimaGs':

                if (tipo === "ultimaGs") {

                    // strHTMLValor_t += `<select class="form-control input-sm campoValor" name="valor_${numero}" id="valor_${numero}">';`
                    // strHTMLValor_t += traerOpcionesLista(idbg, "ultimaGs");
                    // strHTMLValor_t += '</select>';

                    // $("#divValor_" + numero).html(strHTMLValor_t);

                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getultimagestion',
                            strTipo: tipoReporte
                        })
                    })

                }
                break;
            case 'clasi':

                if (tipo === "clasi") {

                    console.log('tipo clasi :>> ', tipo);
                    // strHTMLValor_t += `<select class="form-control input-sm campoValor" name="valor_${numero}" id="valor_${numero}">`
                    // strHTMLValor_t += '<option value="1">Devoluciones</option>';
                    // strHTMLValor_t += '<option value="2">No contactable</option>';
                    // strHTMLValor_t += '<option value="3">Sin gestion</option>';
                    // strHTMLValor_t += '<option value="4">No contactado</option>';
                    // strHTMLValor_t += '<option value="5">Contactado</option>';
                    // strHTMLValor_t += '<option value="6">No efectivo</option>';
                    // strHTMLValor_t += '<option value="7">Efectivo</option>';
                    // strHTMLValor_t += '</select>';

                    // $("#divValor_" + numero).html(strHTMLValor_t);



                    // strHTMLValor_t += `<select class="form-control input-sm campoValor" name="valor_${numero}" id="valor_${numero}">`;

                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getclasificacion',
                            strTipo: tipoReporte
                        })
                    })

                }
                break;
            case 'datoprincipal':

                if (tipo === "datoprincipal") {

                    // strHTMLValor_t += `<select class="form-control input-sm campoValor" name="valor_${numero}" id="valor_${numero}">`
                    // strHTMLValor_t += traerOpcionesLista(idbg, "usu");
                    // strHTMLValor_t += '</select>';

                    // $("#divValor_" + numero).html(strHTMLValor_t);
                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getdatoprincipal',
                            strTipo: tipoReporte
                        })
                    })

                }
                break;

            case 'datosecundario':

                if (tipo === "datosecundario") {

                    // strHTMLValor_t += `<select class="form-control input-sm campoValor" name="valor_${numero}" id="valor_${numero}">`
                    // strHTMLValor_t += traerOpcionesLista(idbg, "usu");
                    // strHTMLValor_t += '</select>';

                    // $("#divValor_" + numero).html(strHTMLValor_t);

                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getdatosecundario',
                            strTipo: tipoReporte
                        })
                    })

                }
                break;

            case 'canal':
                if (tipo === "canal") {
                    console.log('tipo canal :>> ', tipo);

                    // strHTMLValor_t += `<select class="form-control input-sm campoValor" name="valor_${numero}" id="valor_${numero}">`;

                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getcanal',
                            strTipo: tipoReporte
                        })
                    })

                }
                break;

            case 'campan':
                if (tipo === "campan") {
                    console.log('tipo campan :>> ', tipo);

                    // strHTMLValor_t += `<select class="form-control input-sm campoValor" name="valor_${numero}" id="valor_${numero}">`;

                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getcampanas',
                            strTipo: tipoReporte
                        })
                    })

                }
                break;

            case 'estra':
                if (tipo === "estra") {
                    console.log('tipo estra :>> ', tipo);

                    // strHTMLValor_t += `<select class="form-control input-sm campoValor" name="valor_${numero}" id="valor_${numero}">`;

                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getestrategia',
                            strTipo: tipoReporte
                        })
                    })

                }
                break;

            case 'sentido':
                if (tipo === "sentido") {

                    console.log('case[sentido] :>> ', tipo);

                    // strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_' + numero + '" id="valor_' + numero + '">';
                    // strHTMLValor_t += '<option value="ENTRANTE">Entrante</option>';
                    // strHTMLValor_t += '<option value="SALIENTE">Saliente</option>';
                    // strHTMLValor_t += '</select>';
                    const data = {
                        data: [{
                                sentido: 'ENTRANTE'
                            },
                            {
                                sentido: 'SALIENTE'
                            }
                        ]
                    }


                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: data
                    })


                    // $("#divValor_" + numero).html(strHTMLValor_t);

                }


                break;

            case 'reintento':

                if (tipo === "reintento") {
                    console.log('case[reintento] :>> ', tipo);

                    // Se adiciona el filtro por tipo de reintento
                    // strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_' + numero + '" id="valor_' + numero + '">';
                    // strHTMLValor_t += '<option value="NO GESTIONADO">NO GESTIONADO</option>';
                    // strHTMLValor_t += '<option value="REINTENTO AUTOMATICO">REINTENTO AUTOMATICO</option>';
                    // strHTMLValor_t += '<option value="AGENDA">AGENDA</option>';
                    // strHTMLValor_t += '<option value="NO REINTENTAR">NO REINTENTAR</option>';
                    // strHTMLValor_t += '<option value="VACIO">VACIO</option>';
                    // strHTMLValor_t += '</select>';

                    const booValue = removeElementChild({
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getreintento',
                            strTipo: tipoReporte

                        })
                    })




                    // $("#divValor_" + numero).html(strHTMLValor_t);

                }
                break;

            case 'formulario':

                if (tipo === "formulario") {
                    console.log('case[formulario] :>> ', tipo);

                    // Se adiciona el filtro por tipo de formulario
                    // strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_' + numero + '" id="valor_' + numero + '">';
                    // strHTMLValor_t += '<option value="NO GESTIONADO">NO GESTIONADO</option>';
                    // strHTMLValor_t += '<option value="FORMULARIO AUTOMATICO">FORMULARIO AUTOMATICO</option>';
                    // strHTMLValor_t += '<option value="AGENDA">AGENDA</option>';
                    // strHTMLValor_t += '<option value="NO REINTENTAR">NO REINTENTAR</option>';
                    // strHTMLValor_t += '<option value="VACIO">VACIO</option>';
                    // strHTMLValor_t += '</select>';

                    const booValue = removeElementChild({
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`,

                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getformulario',
                            strTipo: tipoReporte
                        })
                    })




                    // $("#divValor_" + numero).html(strHTMLValor_t);

                }
                break;
            case 'basedatos':

                if (tipo === "basedatos") {
                    console.log('case[basedatos] :>> ', tipo);

                    // Se adiciona el filtro por tipo de basedatos
                    // strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_' + numero + '" id="valor_' + numero + '">';
                    // strHTMLValor_t += '<option value="NO GESTIONADO">NO GESTIONADO</option>';
                    // strHTMLValor_t += '<option value="BASEDATOS AUTOMATICO">BASEDATOS AUTOMATICO</option>';
                    // strHTMLValor_t += '<option value="AGENDA">AGENDA</option>';
                    // strHTMLValor_t += '<option value="NO REINTENTAR">NO REINTENTAR</option>';
                    // strHTMLValor_t += '<option value="VACIO">VACIO</option>';
                    // strHTMLValor_t += '</select>';

                    const booValue = removeElementChild({
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getbasedatos',
                            strTipo: tipoReporte
                        })
                    })




                    // $("#divValor_" + numero).html(strHTMLValor_t);

                }
                break;

            case '3':
                if (tipo === "3") {
                    console.log('case -> number 3 :>> ', tipo);
                    strHTMLOperador_t += '<option value=">">MAYOR A</option>';
                    strHTMLOperador_t += '<option value="<">MENOR QUE</option>';

                    // strHTMLValor_t += `<input type="text" class="form-control input-sm Decimal campoValor" name="valor_${numero}" id="valor_${numero}" placeholder="NUMERIC">`

                    // $("#divValor_" + numero).html(strHTMLValor_t);

                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })
                    booValue === null ? null : inputCreateType(numero, 'number')

                    // $(`#valor_${numero}`).numeric({
                    //     decimal: ".",
                    //     negative: false,
                    //     scale: 4
                    // });
                }
                break;

            case '5':
                if (tipo == "5") {
                    console.log('tipo -> case 5 :>> ', tipo);
                    strHTMLOperador_t += '<option value=">">MAYOR A</option>';
                    strHTMLOperador_t += '<option value=">=">MAYOR O IGUAL A</option>';
                    strHTMLOperador_t += '<option value="<">MENOR QUE</option>';
                    strHTMLOperador_t += '<option value="<=">MENOR O IGUAL QUE</option>';

                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })
                    booValue === null ? null : inputCreateType(numero, 'date')

                }
                break;
            case 'usu':
                if (tipo === "usu") {

                    // strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_' + numero + '" id="valor_' + numero + '">';
                    // strHTMLValor_t += traerOpcionesLista(idpregun);
                    // strHTMLValor_t += '</select>';

                    // $("#divValor_" + numero).html(strHTMLValor_t);

                    const booValue = removeElementChild({
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : crearSelects({
                        divValor: `divValor_${numero}`,
                        contador: numero,
                        data: obtenerValoresSelect({
                            id_usuari: id_usuari,
                            param: 'getusuariocalifica',
                            strTipo: tipoReporte
                        })
                    })

                }
                break;
            case '8':
                // Decimales
                if (tipo == "8") {

                    strHTMLOperador_t += '<option value=">">MAYOR A</option>';
                    strHTMLOperador_t += '<option value="<">MENOR QUE</option>';

                    // strHTMLValor_t += '<input type="text" class="form-control input-sm Decimal campoValor" name="valor_' + numero + '" id="valor_' + numero + '" placeholder="NUMERIC">';

                    // $("#divValor_" + numero).html(strHTMLValor_t);
                    const booValue = removeElementChild({
                        // contador: contador,
                        elementChild: '*',
                        elementFatherId: `divValor_${numero}`
                    })

                    booValue === null ? null : inputCreateType(numero, 'number')

                }
                break;
                // case value:

                //     break;
                // case value:

                //     break;
            default:
                console.log('case-> default numero :>> ', numero);
                // strHTMLOperador_t += '<option value="LIKE_1">INICIE POR</option>';
                // strHTMLOperador_t += '<option value="LIKE_2">CONTIENE</option>';
                // strHTMLOperador_t += '<option value="LIKE_3">TERMINE EN</option>';

                // strHTMLValor_t += '<input type="text" class="form-control input-sm" id="valor_' + numero + '" name="valor_' + numero + '" placeholder="TEXT">';

                // $("#divValor_" + numero).html(strHTMLValor_t);

                const booValue = removeElementChild({
                    // contador: contador,
                    elementChild: '*',
                    elementFatherId: `divValor_${numero}`
                })
                booValue === null ? null : inputCreateType(numero, 'text')

                break;
        }


        $("#selOperador_" + numero).html(strHTMLOperador_t);
        // agregarOptionsSelOperador()
    }

    const inputCreateType = async (contador, strType) => {

        const D = new DOM();
        const properties = await inputTypeAddValuesDefault(strType)


        // const input_valor_date = document.getElementById(`valor_${contador}`)

        // input_valor_date.type = strType
        // console.log('input_valor_date :>> ', input_valor_date);

        // await inputTypeAddValuesDefault(strType)
        console.log('inputTypeAddValuesDefault(input_valor) :>> ', properties.value);

        const input_valor = D.create(`input`, {
            type: `${strType}`,
            name: `valor_${contador}`,
            autocomplete: 'off',
            placeholder: `${properties.placeholder}`,
            value: `${properties.value}`,
            id: `valor_${contador}`,

        });

        input_valor.classList.add("form-control", "input-sm", "campoValor")

        // input_valor = await inputTypeAddValuesDefault(strType)

        // strHTMLValor_t += '<input readonly type="text" class="form-control input-sm campoValor" name="valor_' + numero + '" id="valor_' + numero + '" placeholder="YYYY-MM-DD">';

        // $("#divValor_" + numero).html(strHTMLValor_t);

        // $(`#valor_${contador}`).datepicker({
        //     language: "es",
        //     autoclose: true,
        //     todayHighlight: true,
        //     format: 'yyyy-mm-dd'
        // }).datepicker("setDate", new Date());




        // D.append([div_principal_input_valor], div_principal_row);
        // D.append([div_input_valor_form_group], div_principal_input_valor);

        // D.append([input_valor], div_input_valor_form_group);

        //agregar el div del primer comentario al contenedor con id #container
        D.append(input_valor, D.id(`divValor_${contador}`));


        // document.getElementById('divValor_1')
    }

    const inputTypeAddValuesDefault = (strType) => {

        return new Promise(resolve => {
            console.log('input_valor ->inputTypeAddValuesDefault :>> ', strType);
            let items = {
                value: '',
                placeholder: ''
            }
            switch (strType) {
                case 'date':
                    items.placeholder = 'YYYY-MM-DD'
                    items.value = getDate()
                    console.log('strType ->date :>> ', strType);
                    break;
                case 'text':
                    items.placeholder = 'Text'
                    console.log('strType ->text :>> ', strType);
                    break;
                case 'number':
                    items.placeholder = 'Number'
                    console.log('strType ->number :>> ', strType);
                    break;

                default:
                    console.log('strType ->default :>> ', strType);
                    break;
            }

            resolve(items)
        })
    }


    const getDate = () => {
        // const inputDate = document.querySelectorAll('input[type="date"]');
        let fecha = new Date(); //Fecha actual
        let mes = fecha.getMonth() + 1; //obteniendo mes
        let dia = fecha.getDate(); //obteniendo dia
        let ano = fecha.getFullYear(); //obteniendo año
        if (dia < 10)
            dia = '0' + dia; //agrega cero si el menor de 10
        if (mes < 10)
            mes = '0' + mes //agrega cero si el menor de 10

        console.log('`${ano}-${mes}-${dia}` :>> ', `${ano}-${mes}-${dia}`);

        // document.getElementById('fechaActual').value = ano + "-" + mes + "-" + dia;


        return `${ano}-${mes}-${dia}`


    }

    const addDateInput = (elements) => {
        for (const key in elements) {
            if (Object.hasOwnProperty.call(elements, key)) {
                const element = elements[key];
                element.value = getDate()

            }
        }
    }

    const removeElementChild = (objecto) => {
        console.log('objecto removeElementChild :>> ', objecto);

        const padre = document.getElementById(`${objecto.elementFatherId}`)
        const elementsChilds = document.querySelectorAll(`#${padre.id} > ${objecto.elementChild}`)

        console.log('elementsChilds child :>> ', elementsChilds);

        let booValue = false
        for (const key in elementsChilds) {
            if (Object.hasOwnProperty.call(elementsChilds, key)) {
                const element = elementsChilds[key];
                booValue = element === null ? false : padre.removeChild(element)
                console.log('element removeElementChild :>> ', element);
            }
        }

        console.log("removeElementChild -> booValue", booValue)

        return booValue

    }

    const crearSelects = (objecto) => {

        const D = new DOM();

        console.log('objecto :>> ', objecto);





        // console.log('Se elimino :>> ', booValue);
        // const div_principal_input_valor = D.create('div', {
        //     id: `div-col-valor_${contador}`
        // });
        // div_principal_input_valor.classList.add("col-md-3", "col-xs-3", "col-sm-3");


        // const div_input_valor_form_group = D.create('div');
        // div_input_valor_form_group.classList.add("form-group");
        // div_input_valor_form_group.setAttribute('id', `divValor_${contador}`)


        //crear los inputs de nombre y apellido
        // 
        const select_valor = D.create('select', {
            // type: 'text',
            name: `valor_${objecto.contador}`,
            // autocomplete: 'off',
            // placeholder: 'Text',
            id: `valor_${objecto.contador}`,
        });
        select_valor.classList.add("form-control", "input-sm", "campoValor")

        const { data } = objecto.data

        console.debug('data :>> ', data);

        for (const key in data) {
            if (Object.hasOwnProperty.call(data, key)) {
                const item = data[key];
                const itemKey = Object.keys(item)[0]

                // console.log('itemKey :>> ', itemKey);
                console.debug('item->crearSelects :>> ', item[itemKey]);
                let value = item[itemKey]
                let text = item[itemKey]

                const select_option = D.create('option', {
                    text: text,
                    value: value
                })


                // option.text = text
                // option.value = value
                // select_valor.appendChild(option)
                select_valor.appendChild(select_option)

            }
        }

        console.log('select_valor :>> ', select_valor);

        // forEach((item, index) => {
        //     const option = document.createElement('option')

        //     let value = item.sentido
        //     let text = item.sentido

        //     option.text = text
        //     option.value = value
        //     select_valor.appendChild(option)

        // });




        // strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_' + numero + '" id="valor_' + numero + '">';
        // strHTMLValor_t += '<option value="ENTRANTE">Entrante</option>';
        // strHTMLValor_t += '<option value="SALIENTE">Saliente</option>';
        // strHTMLValor_t += '</select>';



        // $("#divValor_" + numero).html(strHTMLValor_t);



        // D.append([div_principal_input_valor], div_principal_row);
        // D.append([div_input_valor_form_group], div_principal_input_valor);

        // D.append([input_valor], div_input_valor_form_group);

        //agregar el div del primer comentario al contenedor con id #container
        D.append([select_valor], D.id(`${objecto.divValor}`));


        // document.getElementById('divValor_1')



    }



    const quitarClaseError = () => {
        const campoValor = document.querySelectorAll('.campoValor')

        campoValor.forEach(element => {
            element.onchange = () => {
                console.log('cambio en quitarClaseError change :>> ', );
                element.closest('.form-group').classList.remove('has-error')
            }

            element.addEventListener("input", (e) => {
                console.log('cambio en quitarClaseError input :>> ', );
                element.closest('.form-group').classList.remove('has-error')
            })
        });

        // $(".campoValor").change(function() {

        //     $(this).closest(".form-group").removeClass("has-error");

        // });

    }

    // var strHTMLOperador_t = '<option value="=" selected>IGUAL A</option>';
    // strHTMLOperador_t += '<option value="!=">DIFERENTE DE</option>';


    // const agregarOptionsSelOperador = () => {
    //     const selOperador_1 = document.querySelectorAll('#selOperador_1');

    //     const condicionales = condicionalesFiltros



    //     selOperador_1.forEach(element => {
    //         console.log('element :>> ', element);
    //         limpiarSelect(element)
    //         condicionales.forEach(item => {
    //             const option = document.createElement('option')

    //             console.log('selOperador_1 :>> ', selOperador_1);
    //             // console.log('clone :>> ', clone);

    //             option.value = item.value
    //             option.text = item.text
    //             item.text === 'IGUAL A' ? option.setAttribute('selected', ``) : null
    //             option.setAttribute('tipo', `${item.tipo}`)
    //             element.appendChild(option)
    //             // fragment.appendChild(clone)
    //         });

    //     });

    // }



    const obtenerValoresSelect = (objData) => {

        const url = `${base_url}cruds/DYALOGOCRM_SISTEMA/G1/reportesAgente/index.php/${objData.param}`
        console.log('base_url :>> ', base_url);
        console.log('objData :>> ', objData);
        let arrData = []

        arrData = $.ajax({
            url: `${url}`,
            type: 'GET',
            data: {
                intIdUsuario: objData.id_usuari,
                strTipo: objData.strTipo
            },
            dataType: 'JSON',
            async: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: `<img src="${base_url}assets/img/clock.gif"/> PROCESANDO PETICION`

                })
            },
            success: function(data) {

                console.log('data :>> ', data);
                // console.log('objecto :>> ', objecto);

                if (data.estado === 'fallo' && !data.data.length > 0) {
                    data.data = []
                    alertify.warning(data.message)
                } else {
                    return data
                }


                // $('#sql_query').html(data);
                // $('#sql_query').val("bd").trigger("change");
            },
            complete: function() {
                $.unblockUI();
            },
            error: function() {
                console.log('data :>> ', data);
                alertify.error(`Ocurrio un error al procesar la solicitud ${data}`)
                $.unblockUI();
            }
        }).responseJSON

        return arrData

    }
</script>