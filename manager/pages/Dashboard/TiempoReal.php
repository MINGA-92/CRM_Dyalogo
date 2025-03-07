
<style>
    #editarDatos #contenedorIntrusion div #cuerpoModal #chatIntruso .chat {
        padding: 10px;
        width: 60%;
        border-radius: 25px;
    }
    
    #editarDatos #contenedorIntrusion div #cuerpoModal #chatIntruso .agente {
        border: 1px solid #009FE3;
    }
    
    #editarDatos #contenedorIntrusion div #cuerpoModal #chatIntruso .cliente {
        border: 1px solid #808080;
        float: right;
    }
    
    #editarDatos #contenedorIntrusion div #cuerpoModal #chatIntruso .chat .textmensaje {
        margin: auto;
        color: #808080;
        font-size: 11pt;
        font-family: "verdana";
        margin: 0px 15px;
    }
    #editarDatos #contenedorIntrusion div #cuerpoModal #chatIntruso .chat .textmensajeFecha {
        font-weight: bold;
    }
    
    #editarDatos #contenedorIntrusion div #cuerpoModal .imagen {
        display: flex;
        justify-content: center;
    }
    
    #editarDatos #contenedorIntrusion div #cuerpoModal .imagen img{
        position: fixed;
        width: 90%;
        max-width: 500px;
        margin-top: 100px;
    } 
    
</style>


<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row" id="divTiempoReal">
        </div>
    </section>
</div>
<div class="modal fade-in" id="modVisorColaMArcador" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0;">
               <button type="button" class="close closeVisorColaMArcador" data-dismiss="modal" id="refrescarGrillas">&times;</button>
            </div>
            <div class="modal-body" style="padding-top: 0;">
                <iframe id="ifrVisorColaMArcador" src="" style="width: 100%; height: 85vh;">
                </iframe>
            </div>
        </div>
    </div>
</div>

<!--MODAL PARA LA INTRUSION Y LA CAPTURA DEL AGENTE-->
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg" id="contenedorIntrusion" style="min-width:655px">
        <div class="modal-content">
            <div class="modal-header" id="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="cerrarChatIntrusion">&times;</button>
                <h4 class="modal-title" id="titulo_modal"></h4>
            </div>
            <div class="modal-body embed-container" id="bodyModal">
                <div class="panel box box-primary" id="intrusion">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#s_intrusion" id="AcordIntrusion">INTRUSION</a>
                        </h4>
                    </div>
                    <div class="panel-collapse" id="s_intrusion">
                        <div class="box-body">
                            <div id="cuerpoModal">
                                <iframe id="frameContenedor" src="" marginheight="0" marginwidth="0" noresize frameborder="0" style="width:767px"></iframe>
                                <div class="row" id="chatIntruso" style="padding:20px"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel box box-primary" id="calidad">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#s_calidad">CALIDAD</a>
                        </h4>
                    </div>
                    <div class="panel-collapse" id="s_calidad">
                        <div class="box-body">
                            <?php $IdHuesped= $_SESSION['HUESPED']; ?>
                            <input type="text" name="IdCampana" id="IdCampana" hidden>
                            <input type="text" name="TxtIdAgente" id="TxtIdAgente" hidden/>
                            <input type="text" name="TxtIdGestion" id="TxtIdGestion" hidden/>
                            <input type="hidden" name="IdProyecto" id="IdProyecto" value="<?php echo $IdHuesped; ?>">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" id="BtnReporteIntrusion" style="margin-left: 82%;">📋 Reporte Calificaciones</button>
                            <iframe id="frameContenedorForm" src="" marginheight="0" marginwidth="0" noresize frameborder="0" style="width:100%;height:700px; margin-top: 2%;"></iframe>
                        </div>
                    </div>
                </div>
                
                <div class="imagen">
                    <img src="" alt="" id="fotoActual">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Para Ver Calificaciones -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="ModalReporte">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" onclick="LimpiarTabla()">&times;</button>
            <h2 id="TituloReporte">Reporte Calificaciones</h2>
        </div>

        <div class="modal-body">
            <div class="row col-12 mb-12">

                <div class="form-check col-md-3">
                    <label class="form-label">Filtrar Por: </label>
                    <div class="form-group">
                        <select class="form-select form-control" id="SelectFiltrarPor" name="SelectFiltrarPor">
                            <option disabled selected>Elige Una Opción</option>
                            <option value="Proyecto">Campaña</option>
                            <option value="Fecha Gestion">Fecha De Gestión</option>
                            <option value="Fecha Evaluacion">Fecha De Evaluación</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" id="divFechaInicial" hidden>
                    <div class="form-group has-feedback">
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                        <label id="lblFechaInicial"> Fecha Inicial: </label>
                        <input type="date" id="FechaInicial" class="form-control transparencia" required="">
                    </div>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" id="divFechaFinal" hidden>
                    <div class="form-group has-feedback">
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                        <label id="lblFechaFinal"> Fecha Final: </label>
                        <input type="date" id="FechaFinal" class="form-control transparencia" required="" disabled>
                    </div>
                </div>

                <div class="form-check col-md-3" id="divSelectProyectos" hidden>
                    <label class="form-label">Lista De Campañas: </label>
                    <div class="form-group">
                        <select class="form-select form-control" id="SelectProyectos" name="SelectProyectos">
                            <option disabled selected>Elige Una Opción</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 float-end" style="margin-top: 2%;" id="divBtnAplicarFiltros" hidden>
                    <button type="button" class="btn btn-sm btn-info" id="BtnAplicarFiltros">🔍 Aplicar Filtros </button>
                </div>

            </div>
        </div>
        
        <div class="modal-body">
            <div class="panel box box-primary">
                <div class="card-body">
                    <table id="TablaReporte" class="table table-border table-hover">
                        <thead>
                            <tr id="ContTitulos" hidden>
                                <th style='text-align: center;' hidden>Id Calificacion</th>
                                <th style='text-align: center;'>Proyecto o Campaña</th>
                                <th style='text-align: center;'>Guion</th>
                                <th style='text-align: center;'>Numero Gestion</th>
                                <th style='text-align: center;'>Fecha Gestion</th>
                                <th style='text-align: center;' hidden>Id Usuario</th>
                                <th style='text-align: center;'>Dato Principal</th>
                                <th style='text-align: center;' hidden>Dato Secundario</th>
                                <th style='text-align: center;'>Fecha Evaluacion</th>
                                <th style='text-align: center;' hidden>USUARI_Cal</th>
                                <th style='text-align: center;'>Calificacion</th>
                                <th style='text-align: center;'>Comentario Calidad</th>
                                <th style='text-align: center;'>Comentario Agente</th>
                                <th style='text-align: center;'>Link Calificacion</th>
                            </tr>
                        </thead>
                        <tbody id="BodyTabla">
                            <tr id="ContFilas"> </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
  </div>
</div>

<!-- Modal Loading -->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="ModalLoading">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><strong>Reporte De Calificación</strong></h5>
        </div>
        <div class="modal-body">
            <!-- loading -->
            <div id="Loading" class="container-loader">
                <div class="loader">
                    <img src="<?=base_url?>assets/img/loader.gif" style="margin-top: -20%; margin-left: 5%; color: #11D2FD;">
                    <p class="form-label text-black" style="margin-top: -20%; margin-left: 32%;"><strong> GENERANDO ... </strong></p>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<div id="secJava">
</div>
<input type="hidden" name="conteoChat" id="conteoChat" value='0'>
<script type="text/javascript">

    //JDBD Esta es la funcion que muestra el tiempo real.
    function tiempoReal(){

        //JDBD Consumimos los servicios del tiempo real.    
        $.ajax({
            async:  true,
            type: "POST",
            url: "pages/Dashboard/TiempoRealServicios.php?TiempoReal=si",
            dataType : "JSON",
            success: function(data){
                

                //JDBD Recorremos las estrategias del JSON.
                    $.each(data,function(kEstrat,vEstrat){

                        $.each(vEstrat.arrEstpas_t,function(kEstpas,vEstpas){

                            var strHTML_t = '';
                            var arrTiempoDuracion_t = new Array();

                            var strHTMLAgen_t = '';

                            var arrScriptAgente_t = new Array();
                            
                            //JDBD Validamos si la campaña ya esta pintada.
                                if ($("#divEstpas_"+vEstpas.intIdCampan_t).length) {

                                    //console.log("Existe : #divEstpas_"+vEstpas.intIdCampan_t);
                                    //$("#IdCampana").val(vEstpas.intIdCampan_t);
                                    //console.log("IdCampana", vEstpas.intIdCampan_t);

                                    $("#h5Nombre_"+vEstpas.intIdCampan_t).html('<strong>'+vEstrat.strNombreEstrat_t.substr(0,20)+' | '+vEstpas.strNombreCampan_t.substr(0,20)+' | '+vEstpas.strSentido_t+'</strong>');

                                    $.each(vEstpas.arrMetas_t,function(kMetas,vMetas){
                                    
                                        if (vEstpas.strSentido_t == "Entrante") {

                                            $("#spanMeta1_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[6].strValor_t);
                                            $("#spanMetaChat_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[7].strValor_t);
                                            $("#spanMetaWhat_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[8].strValor_t);
                                            $("#spanMetaFB_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[9].strValor_t);
                                            $("#spanMetaEMA_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[10].strValor_t);
                                            $("#spanMetaWeb_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[11].strValor_t);

                                            $("#spanMetaPor1_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[0].strValor_t);
                                            $("#spanMetaPor2_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[1].strValor_t);
                                            $("#spanMetaPor3_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[2].strValor_t);
                                            $("#spanMetaPor4_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[3].strValor_t);
                                            $("#spanMetaPor5_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[4].strValor_t);
                                            $("#spanMetaPor6_"+kMetas+"_"+vEstpas.intIdCampan_t).html(vMetas.arrMetas_t[5].strValor_t);


                                        }else if(vEstpas.strSentido_t == "Saliente"){

                                            if (vMetas.arrMetas_t[1].strMeta_t.indexOf("%") == 0) {
                                                var strOperador_t = '%';
                                            }else{
                                                var strOperador_t = 'Seg';
                                            }

                                            $("#spanMeta1_"+kMetas+"_"+vEstpas.intIdCampan_t).html('<i class="fa fa-phone"></i>&nbsp;&nbsp;'+vMetas.arrMetas_t[0].strValor_t);

                                            $("#divProgres_"+kMetas+"_"+vEstpas.intIdCampan_t).width(vMetas.arrMetas_t[1].strValor_t+'%');

                                            // Si el valor viene con -1 remplazarlo por un 0
                                            vMetas.arrMetas_t[1].strValor_t = (vMetas.arrMetas_t[1].strValor_t == -1) ? 0 : vMetas.arrMetas_t[1].strValor_t;
                                            
                                            // Se valida si la campaña tiene marcador descriptivo en caso se adiciona las opciones del marcador
                                            if(vEstpas.strTipoCamp_t == 7 && vMetas.arrMetas_t[1].strMeta_t == "Predictivo"){

                                                if($("#spanMeta2_"+kMetas+"_"+vEstpas.intIdCampan_t+"_cola").length >= 1 ){
                                                    $("#spanMeta2_"+kMetas+"_"+vEstpas.intIdCampan_t+"_cola").html(vMetas.arrMetas_t[1].strValor_t.intMetColaMarc_t);
                                                    $("#spanMeta2_"+kMetas+"_"+vEstpas.intIdCampan_t+"_marcando").html(vMetas.arrMetas_t[1].strValor_t.intMetLlamadaCurso_t);
                                                    $("#spanMeta2_"+kMetas+"_"+vEstpas.intIdCampan_t+"_aceleracion").html("&nbsp;&nbsp;"+vMetas.arrMetas_t[1].strValor_t.intAceleracion_t+"&nbsp;&nbsp;");
                                                    $("#spanMeta2_"+kMetas+"_"+vEstpas.intIdCampan_t+"_aceleracion").attr('value', vMetas.arrMetas_t[1].strValor_t.intAceleracion_t);
                                                }else{
                                                    $("#spanMeta2_"+kMetas+"_"+vEstpas.intIdCampan_t).html(`
                                                    <table width="100%" style="table-layout: fixed;">
                                                        <tr>
                                                        <td><div class="tooltipPredictivo" title="Llamadas en cola" style="width: max-content;"><i class="fas fa-clock"></i> &nbsp;&nbsp; <strong id="spanMeta2_${intItMeta_p}_${intIdPaso_p}_cola" >${vMetas.arrMetas_t[1].strValor_t.intMetColaMarc_t}</strong></div></td>
                                                        <td><div class="tooltipPredictivo" title="Llamadas en curso" style="width: max-content;"><i class="fas fa-phone-volume"></i> &nbsp;&nbsp;<strong id="spanMeta2_${kMetas}_${vEstpas.intIdCampan_t}_marcando">${vMetas.arrMetas_t[1].strValor_t.intMetLlamadaCurso_t}</strong></div></td>
                                                        <td><div class="tooltipPredictivo" title="Nivel de aceleracion" style="width: max-content;"><i class="fas fa-tachometer-alt"></i> &nbsp;&nbsp; <i class="fas fa-caret-down btnChangeAceleracion" actionBtn="down" style="cursor: pointer;"></i>  <strong id="spanMeta2_${kMetas}_${vEstpas.intIdCampan_t}_aceleracion" idCampan="${vEstpas.intIdCampan_t}" value"${vMetas.arrMetas_t[1].strValor_t.intAceleracion_t}">&nbsp;&nbsp;${vMetas.arrMetas_t[1].strValor_t.intAceleracion_t}&nbsp;&nbsp;</strong> <i class="fas fa-caret-up btnChangeAceleracion" actionBtn="up" style="cursor: pointer;"></i></div></td>
                                                        </tr>
                                                    </table>
                                                    `)
                                                }

                                            }else if(vEstpas.strTipoCamp_t != 7 && vMetas.arrMetas_t[1].strMeta_t == "%Sin gestion") {
                                                $("#spanMeta2_"+kMetas+"_"+vEstpas.intIdCampan_t).html('');
                                            }
                                            else{
                                                $("#spanMeta2_"+kMetas+"_"+vEstpas.intIdCampan_t).html('&nbsp;&nbsp;'+vMetas.arrMetas_t[1].strMeta_t.replace('%', '')+'&nbsp;&nbsp;<strong>'+vMetas.arrMetas_t[1].strValor_t.toFixed(2)+'&nbsp;'+strOperador_t+'</strong>');
                                            }
                                            // Se cambnia el tipo de campaña en caso de este se actualice
                                            $("#spanVisor_"+vEstpas.intIdCampan_t).attr("tipocampan", vEstpas.strTipoCamp_t);
                                        }


                                    });


                                    // En caso de que la campaña tenga marcador descriptivo se adiciona el evento click a los botones para cambiar la aceleracion
                                    if(vEstpas.strTipoCamp_t == 7){
                                        const strScriptChangeAceleration = `
                                        <script>
                                            $(".btnChangeAceleracion").off('click');
                                            $(".btnChangeAceleracion").on('click',(e) => {
                                                const btn = $(e.target);
                                                const action = btn.attr('actionbtn');

                                                const acelerationSpan = btn.parent().children('[id^="spanMeta2_0"] [id$="aceleracion"]');
                                                const spanId = acelerationSpan.attr("id");
                                                let acelerationValue = acelerationSpan.attr("value");
                                                const idCampan = acelerationSpan.attr("idcampan");

                                                if(action == 'up'){
                                                    acelerationValue++;
                                                }else if(action == 'down' ){
                                                    acelerationValue--;
                                                }


                                                if(acelerationValue <= 0 && action == 'down'){
                                                    acelerationValue = 0;
                                                    btn.hide();
                                                }else{
                                                    btn.parent().children('.btnChangeAceleracion[actionbtn="down"]').show();
                                                }
                                                changeAceleration(idCampan , acelerationValue, spanId);

                                            });

                                            $(".tooltipPredictivo").tooltip();

                                        <\/script>
                                        `;

                                        $("#secJava").append(strScriptChangeAceleration);
                                    }

                                    var strFunctionVisor_t= `
                                    <script>
                                        $(".visorLlamadas").off('click');
                                        $(".visorLlamadas").on('click',function(){
                                            $("#ifrVisorColaMArcador").attr("src","pages/Dashboard/reportMarcador.php?Campan="+$(this).attr("idcampan")+"&Estpas="+$(this).attr("idpaso")+"&tipoMarc="+$(this).attr("tipoCampan"));
                                            $("#modVisorColaMArcador").modal();
                                        })
                                    <\/script>`;


                                    $("#secJava").append(strFunctionVisor_t);

                                }else{
                                    //console.log("NO Existe : #divEstpas_"+vEstpas.intIdCampan_t);
                                    //$("#IdCampana").val(vEstpas.intIdCampan_t);
                                    //console.log("IdCampana: ", vEstpas.intIdCampan_t);

                                    strHTML_t += '<div id="divEstpas_'+vEstpas.intIdCampan_t+'" strSentido="'+vEstpas.strSentido_t+'" class="col-md-12 col-lg-12 col-xs-12" style="padding: 0px;">';
                                        strHTML_t += '<div class="box box" id="ColorAc'+vEstpas.intIdCampan_t+'" style="border-top-color: '+vEstrat.strColorEstrat_t+';">';
                                            strHTML_t += '<div class="box-header with-border">';
                                                //JDBD : Aca añadimos el titulo del acordeon.
                                                strHTML_t += '<div class="box-tool pull-left col-md-4" style="text-align:left;">';
                                                    strHTML_t += '<h5 id="h5Nombre_'+vEstpas.intIdCampan_t+'" class="box-title" style = "font-size:10pt"><strong>'+vEstrat.strNombreEstrat_t.substr(0,20)+' | '+vEstpas.strNombreCampan_t.substr(0,20)+' | '+vEstpas.strSentido_t+'</strong></h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                strHTML_t += '</div>';

                                                //JDBD : Aca añadimos el boton "Detalle y actividad del marcador".    
                                                if (vEstpas.strSentido_t == "Saliente") {

                                                    strHTML_t += '<div class="box-tool pull-left col-md-3">';
                                                        strHTML_t += '<span id="spanVisor_'+vEstpas.intIdCampan_t+'" idCampan="'+vEstpas.intIdCampan_t+'" idpaso="'+vEstpas.intIdEstpas_t+'" tipoCampan="'+vEstpas.strTipoCamp_t+'" class="badge visorLlamadas" style="background-color : #009FE3; cursor: pointer; border-radius: 2px; box-shadow: -2pt 2pt 0 rgba(0,0,0,.4);"><i class="fa fa-bar-chart-o"></i> <u>Detalle y actividad del marcador</u></span>';
                                                    strHTML_t += '</div>';
                                                    
                                                }

                                                //JDBD : Aca añadimos los labels de los conteos por estado.
                                                strHTML_t += '<div id="divEstado_'+vEstpas.intIdCampan_t+'" class="box-tool pull-right col-md-5" style="text-align:right;">';   
                                                    strHTML_t += '<button type="button" class="btn btn-box-tool minimizar" acordeon="Ac'+vEstpas.intIdCampan_t+'" data-widget="collapse"><i class="fa fa-minus" id="Ac'+vEstpas.intIdCampan_t+'"></i></button>';

                                                strHTML_t += '</div>';
                                                
                                            strHTML_t += '</div>';
                                            strHTML_t += '<div class="box-body no-padding" style="display: block;" id="divAc'+vEstpas.intIdCampan_t+'">';

                                            //JDBD - Seccion metas.
                                            strHTML_t += '<div class="row">';

                                            var arrColores_t = ["yellow","green","red","aqua"];

                                            var arrGrupo_t = new Array();

                                            if (vEstpas.strSentido_t == "Entrante") {
                                                var arrGrupo_t = ["EN COLA / NIVEL DE SERVICIO","RECIBIDAS / TMO","CONTESTADAS DEL DIA","EFECTIVOS DEL DIA"];
                                            }
                                            if (vEstpas.strSentido_t == "Saliente") {
                                                var arrGrupo_t = ["TOTAL REGISTROS SIN GESTION","GESTIONES DEL DIA","CONTACTADOS DEL DIA","EFECTIVOS DEL DIA"];
                                            }

                                            $.each(vEstpas.arrMetas_t,function(kMetas,vMetas){

                                                if (vMetas.arrMetas_t[1].strMeta_t.indexOf("%") == 0) {
                                                    var strOperador_t = '%';
                                                }else{
                                                    var strOperador_t = 'seg.';
                                                }

                                                strHTML_t += grupoMetas(strOperador_t,vMetas.strColor_t,vMetas.strIcono_t,arrGrupo_t[kMetas],kMetas,vEstpas.intIdEstpas_t,vMetas.arrMetas_t,vEstpas.strSentido_t,vEstpas.strTipoCamp_t, vEstpas.intIdCampan_t );


                                            });

                                            strHTML_t += '</div>';

                                            strHTML_t += '<div class="row" id="divAgente_'+vEstpas.intIdCampan_t+'">';

                                            strHTML_t += '</div>';
                                           
                                            
                                        strHTML_t += '</div>';
                                    strHTML_t += '</div>';
                                    
                                    $("#divTiempoReal").append(strHTML_t);

                                    // En caso de que la campaña tenga marcador descriptivo se adiciona el evento click a los botones para cambiar la aceleracion
                                    if(vEstpas.strTipoCamp_t == 7){
                                        const strScriptChangeAceleration = `
                                        <script>
                                            $(".btnChangeAceleracion").off('click');
                                            $(".btnChangeAceleracion").on('click',(e) => {
                                                const btn = $(e.target);
                                                const action = btn.attr('actionbtn');

                                                const acelerationSpan = btn.parent().children('[id^="spanMeta2_0"] [id$="aceleracion"]');
                                                const spanId = acelerationSpan.attr("id");
                                                let acelerationValue = acelerationSpan.attr("value");
                                                const idCampan = acelerationSpan.attr("idcampan");

                                                if(action == 'up'){
                                                    acelerationValue++;
                                                }else if(action == 'down' ){
                                                    acelerationValue--;
                                                }


                                                if(acelerationValue <= 0 && action == 'down' ){
                                                    acelerationValue = 0;
                                                    btn.hide();
                                                }else{
                                                    btn.parent().children('.btnChangeAceleracion[actionbtn="down"]').show();
                                                }
                                                changeAceleration(idCampan , acelerationValue, spanId);

                                            });

                                            $(".tooltipPredictivo").tooltip();

                                        <\/script>
                                        `;

                                        $("#secJava").append(strScriptChangeAceleration);

                                    }


                                    var strFunctionVisor_t= `
                                    <script>
                                        $(".visorLlamadas").off('click');
                                        $(".visorLlamadas").on('click',function(){
                                            $("#ifrVisorColaMArcador").attr("src","pages/Dashboard/reportMarcador.php?Campan="+$(this).attr("idcampan")+"&Estpas="+$(this).attr("idpaso")+"&tipoMarc="+$(this).attr("tipoCampan"));
                                            $("#modVisorColaMArcador").modal();
                                        })
                                    <\/script>
                                    `;

                                    $("#secJava").append(strFunctionVisor_t);

                                }
                            
                        });


                    });


                // divAcordeon();
            }
        });

    }

    function statusAgent(){
        $.ajax({
            async:  true,
            type: "POST",
            url: "pages/Dashboard/TiempoRealServicios.php?estadosAgentes=si",
            dataType : "JSON",
            success: function(data){
                
                printAgents(data);
                intrusion();
                
        }});
    }


    function printAgents(data) {
        // Se valida primero las campañas pintadas 

        if ($(" div [id^='divEstpas_']").length) {

            const campans = $(" div[id^='divEstpas_']");

            // Se recorren las campañas ya pintadas

            for (let i = 0; i < campans.length; i++) {

                let strHTMLAgen_t = "";
                let strStatesHtml = "";


                const campanId = $(campans[i]).attr('id').split('divEstpas_')[1];
                const sentidoCampan = $(campans[i]).attr('strsentido');


                // Se les asigna el color a los agentes, se ordenan y se realiza el conteo por campaña

                const agentsOrganized = asingStatesColorAgent(data.arrAgentes_t, parseInt(campanId));


                    // por cada campaña se recorren los agentes

                        $.each(agentsOrganized[0], function (kAgente, vAgente) {

                        const matchCaman = $.inArray(parseInt(campanId), vAgente.lstCampanasAsignadas_t);


                        if (matchCaman !== -1) {

                            strHTMLAgen_t += agente(campanId, vAgente.strNombreEstpasActaual_t, vAgente.strEstado_t, vAgente.strFechaHoraCambioEstado_t, vAgente.intIdAgente_t, vAgente.intIdCampanActual_t, vAgente.strNombreAgente_t, vAgente.strPausa_t, vAgente.strColorEstado_t, vAgente.strFechaHoraCambioEstadoFormat_t, sentidoCampan, vAgente.strCanalActual_t, vAgente.strFoto_t, vAgente.booEnConversacion,  vAgente.strDuracionEstadoTiempo_t);

                        }


                    })

                    $("#divAgente_" + campanId).html(strHTMLAgen_t);



                    // Se valida si los contadores de los estados se encuentran pintados

                    if($("#spanEstado_0_"+campanId).length){

                        $.each(agentsOrganized[1], function (kState, vState) {
                            $("#spanEstado_"+kState+"_"+campanId).html(vState.strEstado_t+'  '+vState.intEstado_t);
                        });

                    }else{
                        $.each(agentsOrganized[1], function (kState, vState) {
                            strStatesHtml += '<span id="spanEstado_'+kState+'_'+campanId+'" class="badge" style="background-color : '+vState.strColor_t+';">'+vState.strEstado_t+'  '+vState.intEstado_t+'</span>&nbsp;';
                        });

                        $("#divEstado_"+campanId).prepend(strStatesHtml);
                    }
                
            }
        };
    }

    function asingStatesColorAgent(arrAgents, campanId) {

        let intNoConnection = 0;
        let intPaused = 0;
        let intBusy = 0;
        let intNoAvalible = 0;
        let intBusyNoComunication = 0;
        let intNoAvalibleNoComunication = 0;
        let intAvalible = 0;


            // Se crea un deep copy para no modificar el array original
            
            const arrColorAgent = JSON.parse(JSON.stringify(arrAgents));

        // Se le asigna un color a cada agente dependiendo el estado


        arrColorAgent.forEach(objAgent => {

            // Se valida si el agente esta dentro de la campaña que se esta recorriendo

            const matchCaman = $.inArray(campanId, objAgent.lstCampanasAsignadas_t);


            if (matchCaman !== -1) {


                const strEstado_t = objAgent.strEstado_t.toLowerCase();

                const booEnConversacion = objAgent.booEnConversacion;

                if (strEstado_t == "disponible") {

                    objAgent.strColorEstado_t = "#009603";
                    intAvalible++;

                } else if (strEstado_t == "pausado") {

                    objAgent.strColorEstado_t = "#f39d00";
                    intPaused++

                } else if (strEstado_t.search('ocupado') != -1) {

                    if (objAgent.intIdCampanActual_t == campanId) {

                        

                        if (!booEnConversacion) {

                            objAgent.strColorEstado_t = "#7cc0d8";
                            objAgent.strEstado_t = "Ocupado sin comunicaciones";
                            intBusyNoComunication++;

                        }else{
                            objAgent.strColorEstado_t = "#ff1c00";
                            intBusy++
                        }

                    } else {

                        if (!booEnConversacion) {

                            objAgent.strColorEstado_t = "#2596be";
                            objAgent.strEstado_t = "No disponible sin comunicaciones";
                            intNoAvalibleNoComunication++
                        }else{
                            objAgent.strColorEstado_t = "#941200";
                            objAgent.strEstado_t = "No Disponible";
                            intNoAvalible++;
                        }
                    }

                } else {

                    objAgent.strColorEstado_t = "#9c9c9c";
                    intNoConnection++;
                }
            }
        });


        // Se ordenan segun el estado


        const arrOrderAgents_t = ["#9c9c9c", "#f39d00", "#ff1c00", "#941200", "#7cc0d8", "#2596be", "#009603"];

        let arrAgentOrganized_t = [];

        let intIter_t = 0;


        arrOrderAgents_t.forEach(color => {

            arrColorAgent.forEach(agent => {

                if (color == agent.strColorEstado_t) {
                    arrAgentOrganized_t[intIter_t] = agent;
                    intIter_t++;
                }
            });
        });



        const arrStates = [{
                strEstado_t: "<i class=\"fa fa-warning\"></i> Sin conexion",
                strColor_t: "#9c9c9c",
                intEstado_t: intNoConnection
            },
            {
                strEstado_t: "<i class=\"fa fa-warning\"></i> Pausados",
                strColor_t: "#f39d00",
                intEstado_t: intPaused
            },
            {
                strEstado_t: "<i class=\"fa fa-warning\"></i> Ocupados",
                strColor_t: "#ff1c00",
                intEstado_t: intBusy
            },
            {
                strEstado_t: "<i class=\"fa fa-warning\"></i> No Disponibles",
                strColor_t: "#941200",
                intEstado_t: intNoAvalible
            },
            {
                strEstado_t: "<i class=\"fa fa-warning\"></i> Ocupados sin comunicaciones",
                strColor_t: "#7cc0d8",
                intEstado_t: intBusyNoComunication
            },
            {
                strEstado_t: "<i class=\"fa fa-warning\"></i> No Disponibles sin comunicaciones",
                strColor_t: "#2596be",
                intEstado_t: intNoAvalibleNoComunication
            },
            {
                strEstado_t: "<i class=\"fa fa-warning\"></i> Disponibles",
                strColor_t: "#009603",
                intEstado_t: intAvalible
            }
        ]



        return [arrAgentOrganized_t, arrStates];


    }


    function intrusion(){
        $('[data-toggle="popover"]').popover({
            html: true,
            placement: 'right'
        });

        $('.imagenuUsuarios').mouseover(function() {
            var id = $(this).parent().attr('id');
            $(this).parent().css('z-index', '-1');
            $(this).parent().popover('show');
        });

        $('.menuEventos').mouseleave(function() {
            $('.intrusion').css('z-index', '100');
            $('[data-toggle="popover"]').popover('hide');
        });

        $(".intruso").click(function() {
            let id = $(this).attr('key');
            let intruso = <?=$_SESSION['USUARICBX']?>;
            let estado = $(this).attr('estado');
            let nombreAgente = $(this).attr('data-original-title');
            var Contador= 0;
            var NuevoArrayNombre= [];
            var ArrayNombreAgente= nombreAgente.split(" ");
            ArrayNombreAgente.forEach(Nombre => {
                Contador= Contador+1;
                for (let i = 0; i < Nombre.length; i++) {
                    const Letra = Nombre[i];
                    if(Letra == "0"){
                        var Nombre= Nombre.replace("u00f3", "ó");
                        //console.log("Nombre: ", Nombre);
                    }
                }
                NuevoArrayNombre.push(Nombre);
            });

            if(Contador => 4){
                nombreAgente= NuevoArrayNombre[0]+" "+NuevoArrayNombre[1]+" "+NuevoArrayNombre[2]+" "+NuevoArrayNombre[3];
            }else if(Contador == 3){
                nombreAgente= NuevoArrayNombre[0]+" "+NuevoArrayNombre[1]+" "+NuevoArrayNombre[2];
            }else if(Contador == 2){
                nombreAgente= NuevoArrayNombre[0]+" "+NuevoArrayNombre[1];
            }
            console.log("Nombre Agente: ", nombreAgente);
            let campana = $(this).attr('campan');
            console.log("Estado: ", estado);
            if ((estado.toUpperCase().includes('OCUPADO'))||(estado.toUpperCase().includes('NO DISPONIBLE SIN COMUNICACIONES')||(estado.toUpperCase().includes('OCUPADO SIN COMUNICACIONES')))) {
            //if (estado.toUpperCase().includes('OCUPADO')) {
                let arrData= getIdComunicacion(id,campana);
                console.log(id, intruso, campana);
                $("#cuerpoModal").css('padding', '15px');
                $("#modal-header").css({position:'inherit', right:'0%'});
                $("#cerrarChatIntrusion").attr('style','font-size:25px;color:black;opacity: 0.5;');
                if(arrData.guion && arrData.idRegistro){
                    //$("#frameContenedorForm").attr('src', '../crm_php/index.php?formulario='+arrData.guion+'&view=si&registroId='+arrData.idRegistro+'&quality=1&formaDetalle=si&intrusionTR=si');
                    $("#frameContenedorForm").attr('src', '../crm_php/index.php?formulario='+arrData.guion+'&view=si&registroId='+arrData.idRegistro+'&campanaId='+campana+'&quality=1&formaDetalle=si&intrusionTR=si');
                }else{
                    $("#frameContenedorForm").attr("srcdoc", '<h2>Mensaje: ¡La Gestion No Existe! </h2><p>La Llamada Actual, Aun No Tiene Una Gestión Para Calificar...</p>');
                }
                let canal = $(this).attr('canal');
                if (canal == 'voip' || canal == 'voz') {
                    if($(this).hasClass('conversando') == false){
                    //if($(this).hasClass('conversando')){
                        $("#frameContenedor").attr('src', 'pages/Dashboard/intrusion.php?agente=' + id + '&intruso=' + intruso);
                        $('#fotoActual').hide();
                        $('#chatIntruso').hide();
                        $("#contenedorIntrusion").css('width', '80%');
                        $("#cuerpoModal").css('height', 'auto');
                        $("#cuerpoModal").css('overflow', 'hidden');
                        $("#titulo_modal").html('Monitoreando Al Agente:<strong>'+' '+nombreAgente+'</strong>');
                        $("#TituloReporte").html('<strong>Calificaciones -'+' '+nombreAgente+'</strong>');
                        $("#TxtIdAgente").val(id);
                        $("#TxtIdGestion").val(arrData.idRegistro);
                        $("#IdCampana").val(campana);
                        $("#frameContenedor").show();
                        $("#editarDatos").modal('show');
                        $("#AcordIntrusion").prop('class', "collapsed");
                        $('#AcordIntrusion').click();
                    }else{
                        alertify.warning('¡El Agente No Tiene Una Llamada Activa!');
                    }
                } else if (canal == 'chat') {
                    let html = intrusionChat(id);
                    if (html) {
                        $("#contenedorIntrusion").css('width', '80%');
                        $("#cuerpoModal").css('height', '450px');
                        $("#cuerpoModal").css('overflow-y', 'overlay');
                        $("#titulo_modal").html('Monitoreando Al Agente:<strong>'+' '+nombreAgente+'</strong>');
                        $("#TituloReporte").html('<strong>Calificaciones -'+' '+nombreAgente+'</strong>');
                        $("#TxtIdAgente").val(id);
                        $("#TxtIdGestion").val(arrData.idRegistro);
                        $("#IdCampana").val(campana);
                        $('#fotoActual').hide();
                        $("#frameContenedor").hide();
                        $("#chatIntruso").show();
                        $("#editarDatos").modal('show');
                        $("#AcordIntrusion").prop('class', "collapsed");
                        $('#AcordIntrusion').click();
                        RecargarIntrusionChat(id);
                    }
                } else {
                    alertify.warning('No Se Puede Realizar Intrusión En El Canal Actual');
                }
            } else {
                alertify.warning('El Agente No Esta En Una Comunicación');
            }
        });

        $(".capturofoto").click(function() {
            let id = $(this).attr('key');
            $.ajax({
                url: "pages/Dashboard/TiempoRealServicios.php",
                type: "POST",
                data: {
                    capturafoto: 'si',
                    id: id
                },
                success: function(data) {
                        setTimeout(function() {
                        $('#fotoActual').show();
                        $("#frameContenedor").hide();
                        $("#chatIntruso").hide();
                        $.ajax({
                            url: "pages/Dashboard/TiempoRealServicios.php",
                            type: "POST",
                            data: {
                                consultafoto: 'si',
                                id: id
                            },
                            success: function(data) {
                                $('#frameContenedor').attr('src', '');
                                $.unblockUI();
                                if (data.length > 7) {
                                    $("#cuerpoModal").css('padding', '0px');
                                    $("#cuerpoModal").css('height', '650px');
                                    $("#modal-header").css({position:'fixed',right:'10%',border:'none'});
                                    $("#cerrarChatIntrusion").css('color', 'white',);
                                    $("#cerrarChatIntrusion").attr('style','font-size:80px;color:white;border:5px solid;line-height: 0.7;opacity: 0.8;');
                                    $("#fotoActual").attr('src', data);
                                    $("#editarDatos").modal('show');
                                }else{
                                    alertify.error('El agente no habilito los permisos de camara o bien no hay una camara conectada');
                                }
                            }
                        });
                    }, 4000);
                },
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src=\'assets/img/clock.gif\' /> Por favor espere mientras se captura la foto'
                    });
                }
            });
        });

        // funcion que invoca el servicio para desconectar agente 
        $('.disconnect-agent').on('click',  (e) => {
            try {
                console.log('click');
                swal({
                    title: '¿Esta seguro que desea desconectar el agente?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-succes",
                    confirmButtonText: "Continuar!",
                    closeOnConfirm: true,
                    closeOnCancel: true   
                },function(isConfirm) {
                    if (isConfirm) {
                        btnT = $(e.currentTarget);
                        agentOf(btnT); 
                    }
                }
                
                )          
            } catch (error) {
                alertify.error('ocurrio un error')
            }
        })

        const agentOf = (btnT) => {
            const url = `pages/Dashboard/TiempoRealServicios.php?agentOf=true&id_agent=${btnT.data('id-agent')}`

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json', 
                beforeSend: function() {
                    $.blockUI({ message: 'Desconectando Agente' });
                },
                success: function(response) {
                    $.unblockUI();
                    console.log(response);
                    if (response.strEstado_t === 'ok') {
                        alertify.success(response.strMensaje_t);
                    } else {
                        alertify.error(response.strMensaje_t);
                    }
                },
                error: function(error) {
                    $.unblockUI();
                    alertify.error(`Error en la solicitud:${error}`);
            
                }
            });
            
        }

        $("#cerrarChatIntrusion").click(function() {
            stopIntrusionChat();
        });

        function stopIntrusionChat() {
            clearInterval(stopIntrusion);
        }
    }

    // function agente(intIdEstpas_p,strNombreEstpasActaual_p,strEstado_p,strFechaHoraCambioEstado_p,intIdAgente_p,intIdCampanActual_p,strNombreAgente_p,strPausa_p,strColorEstado_p,strFechaHoraCambioEstadoFormat_p,strSentido_p,strCanalActual_p,strFoto_p,booEnConversacion_p){

    //     var strHTMLAgente_t = '';

    //     strHTMLAgente_t += '<div class="col-md-1 col-xs-12" style="text-align:center;padding-bottom:2px;padding:15px">';
    //       strHTMLAgente_t += '<img src="'+strFoto_p+'" data-toggle="popover" data-trigger="hover" alt="Dyalogo" data-content="'+modalAgente('/DyalogoImagenes/usr'+intIdAgente_p+'.jpg',intIdAgente_p,intIdEstpas_p,strEstado_p,strColorEstado_p,strFechaHoraCambioEstadoFormat_p,strSentido_p,strNombreEstpasActaual_p,strPausa_p)+'" style="border:solid '+strColorEstado_p+' 4px; width: 60px; height: 60px;" title="" class="imagenuUsuarios img-circle" idusuario="'+intIdAgente_p+'" data-original-title="'+strNombreAgente_p+'">';
    //       strHTMLAgente_t += '<a class="users-list-name" style="color:'+strColorEstado_p+';" href="#">'+strNombreAgente_p+'</a>';
    //       strHTMLAgente_t += '<span class="users-list-date" style="color:'+strColorEstado_p+';">'+canalIcono(strSentido_p,strCanalActual_p,strEstado_p,booEnConversacion_p);
    //         strHTMLAgente_t += '&nbsp;&nbsp;<span style="color:'+strColorEstado_p+';" id="reloj_'+intIdAgente_p+intIdEstpas_p+'" style = "font-size:10px">'+strFechaHoraCambioEstado_p+'</span>';
    //       strHTMLAgente_t += '</span>';
    //     strHTMLAgente_t += '</div>';

    //     return strHTMLAgente_t;
    // }

    function getIdComunicacion(user,campan){
        let response='';
        $.ajax({
            async:false,
            url: "pages/Dashboard/TiempoRealServicios.php",
            type: "POST",
            data: {
                getidComunicacion: 'si',
                user: user,
                campan:campan
            },
            dataType: 'json',
            async: false,
            success: function(data) {
                if (data.estado == 'ok') {
                    response=data;
                    console.log("response: ", response);
                } else {
                    alertify.error(data.mensaje);
                }
            }
        });

        return response;
    }

    function agente(intIdEstpas_p, strNombreEstpasActaual_p, strEstado_p, strFechaHoraCambioEstado_p, intIdAgente_p, intIdCampanActual_p, strNombreAgente_p, strPausa_p, strColorEstado_p, strFechaHoraCambioEstadoFormat_p, strSentido_p, strCanalActual_p, strFoto_p, booEnConversacion_p, strDuracionEstadoTiempo_t) {
        // console.log('conversando :',booEnConversacion_p);
        var context='';
        if(booEnConversacion_p){
            context='conversando';
        }
        let srcFoto = (strFoto_p == "assets/img/Kakashi.fw.png") ? "/manager/assets/img/Kakashi.fw.png" : strFoto_p ;
        var strHTMLAgente_t = '';

        strHTMLAgente_t += '<div class="col-md-1 col-xs-12 contenedor_agente" style="text-align:center;padding-bottom:2px;padding:15px;">';

        strHTMLAgente_t += '<div class="padreEventos" id="padre_' + intIdAgente_p + '" style="z-index:50;" userid="' + intIdAgente_p + '">';
        strHTMLAgente_t += '<a href="#" ><div style="margin:auto;border:solid ' + strColorEstado_p + ' 4px; width: 60px; height: 60px;background:' + strColorEstado_p + ';opacity:.8" title="" class="menuEventos img-circle" idusuario="' + intIdAgente_p + '" data-original-title="' + strNombreAgente_p + '"><div style="height:50%;border-bottom:1px solid rgba(255,255,255,.5);display:flex"><i style="cursor:pointer; font-size:20px; margin:auto;color:#fff" title="Intrusion" class="fa fa-phone intruso '+context+'" key="' + intIdAgente_p + '" estado="' + strEstado_p + '" canal="' + strCanalActual_p + '" data-original-title="' + strNombreAgente_p + '" campan="'+intIdCampanActual_p+'"></i></div><div style="height:50%;"><i style="cursor:pointer; font-size:20px; margin-top:4px;color:#fff" title="Desconectar agente" class="fa fa-power-off disconnect-agent" key="' + intIdAgente_p + '" data-id-agent="' + intIdAgente_p + '"></i></div></div></a>';
        strHTMLAgente_t += '<a class="users-list-name" style="color:' + strColorEstado_p + ';" href="#">' + strNombreAgente_p + '</a>';
        strHTMLAgente_t += '<span class="users-list-date" style="color:' + strColorEstado_p + ';">' + canalIcono(strSentido_p, strCanalActual_p, strEstado_p,booEnConversacion_p);
        strHTMLAgente_t += '&nbsp;&nbsp;<span style="color:' + strColorEstado_p + ';" id="reloj_' + intIdAgente_p + intIdEstpas_p + '" style = "font-size:10px">'+calcTime(strFechaHoraCambioEstado_p)+'</span>';
        strHTMLAgente_t += '&nbsp;&nbsp;<input id="reloj_ChangeDate' + intIdAgente_p + intIdEstpas_p + '" value="'+strFechaHoraCambioEstado_p+'" hidden ></input>';
        strHTMLAgente_t += '</span>';
        strHTMLAgente_t += '</div>';

        strHTMLAgente_t += '<div class="intrusion" style="position: absolute;top: 5px;padding: 11px;left: 50%;transform: translate(-50%, 0%);z-index:100;" userid="' + intIdAgente_p + '"';
        strHTMLAgente_t += '<a href="#" id="toggle_' + intIdAgente_p + '" data-toggle="popover" alt="Dyalogo" data-content="' + modalAgente(srcFoto, intIdAgente_p, intIdEstpas_p, strEstado_p, strColorEstado_p, strFechaHoraCambioEstadoFormat_p, strSentido_p, strNombreEstpasActaual_p, strPausa_p) + '" style="position: absolute;top: 16px;text-align:center;padding-bottom:2px;"><img src="'+srcFoto+'" style="border:solid ' + strColorEstado_p + ' 4px; width: 60px; height: 60px;" title="" class="imagenuUsuarios img-circle" idusuario="' + intIdAgente_p + '" data-original-title="' + strNombreAgente_p + '"></a>';
        strHTMLAgente_t += '</div>';

        strHTMLAgente_t += '</div>';
        // strHTMLAgente_t += '<div class="col-md-12 col-xs-12">';
        // strHTMLAgente_t += '<div class="vertical-center">'
        // strHTMLAgente_t += '<button type="button" class="btn btn-success btn-sm disconnect-agent" data-id-agent="'+intIdAgente_p+'" >Desconectar Agente</button>'
        // strHTMLAgente_t += '</div>';
        // strHTMLAgente_t += '</div>';
        return strHTMLAgente_t;

    }

    function modalAgente(strFotoAgente_p,intIdAgente_p,intIdEstpas_p,strEstado_p,strColorEstado_p,strFecha_p,strSentido_p,strNombreEstpasActaual_p,strPausa_p){

        var strHTMLModalAgente_t = "";

        strHTMLModalAgente_t += "<table class='table' style='font-size:12px;' width='300px'>";
            strHTMLModalAgente_t += "<tr>";
                strHTMLModalAgente_t += "<td><img src='"+strFotoAgente_p+"' class='img-circle' style='width:100px; height:100px; border:solid "+strColorEstado_p+" 4px;'></td>";
                strHTMLModalAgente_t += "<td width='50%'>";
                    strHTMLModalAgente_t += "<table class='table table-bordered' width='100%'>";
                        strHTMLModalAgente_t += "<tr>";
                            strHTMLModalAgente_t += "<td style='color:"+strColorEstado_p+";'>"+strEstado_p+"</td>";
                        strHTMLModalAgente_t += "</tr>";
                        strHTMLModalAgente_t += "<tr>";
                            strHTMLModalAgente_t += "<td style='color:"+strColorEstado_p+";' id='relojTD_"+intIdAgente_p+intIdEstpas_p+"'></td>";
                        strHTMLModalAgente_t += "</tr>";
                        strHTMLModalAgente_t += "<tr>";
                            strHTMLModalAgente_t += "<td style='color:"+strColorEstado_p+";'>"+strSentido_p+"</td>";
                        strHTMLModalAgente_t += "</tr>";
                    strHTMLModalAgente_t += "</table>";
                strHTMLModalAgente_t += "</td>";
            strHTMLModalAgente_t += "</tr>";
            strHTMLModalAgente_t += "<tr>";

                if (strEstado_p == "Pausado") {

                    strHTMLModalAgente_t += "<th>Tipo de Pausa</th>";
                    strHTMLModalAgente_t += "<td style='color:"+strColorEstado_p+"' width='50%'>"+strPausa_p.toUpperCase()+"</td>";

                }else if(strEstado_p.toUpperCase().includes('OCUPADO')){

                    strHTMLModalAgente_t += "<th>Campaña Actual</th>";
                    strHTMLModalAgente_t += "<td style='color:"+strColorEstado_p+"' width='50%'>"+strNombreEstpasActaual_p.toUpperCase()+"</td>";

                }

            strHTMLModalAgente_t += "</tr>";
        strHTMLModalAgente_t += "</table>";

        return strHTMLModalAgente_t; 

    }

    function canalIcono(strSentido_p,strCanalActual_p,strEstado_p,booEnConversacion_p){

        $canal = '<i class="fa fa-phone"></i>&nbsp;<i class="fa fa-arrow-right"></i>';

        if (strEstado_p.toUpperCase().includes('OCUPADO')) {
            switch (strCanalActual_p) {
                case 'voip':

                    if (booEnConversacion_p) {
                        if(strSentido_p == 'Entrante'){
                            $canal = '<i class="fa fa-arrow-right" style = "font-size:10px"></i>&nbsp;<i class="fa fa-phone" style = "font-size:10px"></i>';
                        }else{
                            $canal = '<i class="fa fa-phone" style = "font-size:10px"></i>&nbsp;<i class="fa fa-arrow-right" style = "font-size:10px"></i>';
                            
                        }
                    }else{
                        $canal = '';
                    }
                    
                    break;

                case 'voz':

                    if (booEnConversacion_p) {

                        if(strSentido_p == 'Entrante'){
                            $canal = '<i class="fa fa-arrow-right" style = "font-size:10px"></i>&nbsp;<i class="fa fa-phone" style = "font-size:10px"></i>';
                        }else{
                            $canal = '<i class="fa fa-phone" style = "font-size:10px"></i>&nbsp;<i class="fa fa-arrow-right" style = "font-size:10px"></i>';
                            
                        }

                    }else{
                        $canal = '';
                    }
                    
                    break;

                case 'correo':

                    if(strSentido_p == 'Entrante'){
                        $canal = '<i class="fa fa-arrow-right"></i>&nbsp;<i class="fa fa-envelope-o"></i>';
                    }else{
                        $canal = '<i class="fa fa-envelope-o"></i>&nbsp;<i class="fa fa-arrow-right"></i>';
                    }
                    
                    break;

                case 'chat':

                    if(strSentido_p == 'Entrante'){
                        $canal = '<i class="fa fa-arrow-right"></i>&nbsp;<i class="fa fa-comment-o"></i>';
                    }else{
                        $canal = '<i class="fa fa-comment-o"></i>&nbsp;<i class="fa fa-arrow-right"></i>';
                    }
                    
                    break;

                default:

                    if (booEnConversacion_p) {
                            $canal = '<i class="fa fa-phone" style = "font-size:10px"></i>&nbsp;<i class="fa fa-arrow-right" style = "font-size:10px"></i>';
                    }else{
                        $canal = '';
                    }

                    break;
                    
            }
        }else{
            $canal = '';
        }

        return $canal;

    }


    function grupoMetas(strOperador_p,strColor_p,strIcono_p,strGrupo_p,intItMeta_p,intIdPaso_p,arrValorMetas_p,strSentido_p, strTipoCamp_p, intIdCampan_p){

        var strHtmlGrupo_t = '';

        if (strSentido_p == "Entrante") {

            strHtmlGrupo_t += '<div class="col-md-3" style="padding : 2px;">';
                strHtmlGrupo_t += '<div class="info-box" style="background-color:'+strColor_p+'; color: #FFFFFF;">';
                    strHtmlGrupo_t += '<span class="info-box-icon"><i class="'+strIcono_p+'"></i></span>';
                    strHtmlGrupo_t += '<div class="info-box-content">';
                    strHtmlGrupo_t += '<table width="100%" style="text-align: center;">';
                            strHtmlGrupo_t += '<tbody>';

                                strHtmlGrupo_t += '<tr>';

                                    strHtmlGrupo_t += '<td style="text-align:center;" colspan="6">';
                                        strHtmlGrupo_t += '<span class="info-box-text">'+strGrupo_p+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                strHtmlGrupo_t += '</tr>';

                                strHtmlGrupo_t += '<tr>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" class="info-box-number"><i class="fa fa-phone"></i></span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" class="info-box-number"><i class="fa fa-commenting-o"></i></span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" class="info-box-number"><i class="fa fa-whatsapp"></i></span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" class="info-box-number"><i class="fa fa-facebook-square"></i></span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" class="info-box-number"><i class="fa fa-envelope-o"></i></span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" class="info-box-number"><i class="fa  fa-list-alt"></i></span>';
                                    strHtmlGrupo_t += '</td>';

                                strHtmlGrupo_t += '</tr>';

                                strHtmlGrupo_t += '<tr>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaPor1_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[0].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaPor2_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[1].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaPor3_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[2].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaPor4_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[3].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaPor5_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[4].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaPor6_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[5].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                strHtmlGrupo_t += '</tr>';

                                strHtmlGrupo_t += '<tr>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMeta1_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[6].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaChat_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[7].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaWhat_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[8].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaFB_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[9].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaEMA_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[10].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                    strHtmlGrupo_t += '<td class="col-2">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMetaWeb_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number">'+arrValorMetas_p[11].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                strHtmlGrupo_t += '</tr>';

                            strHtmlGrupo_t += '</tbody>';
                    strHtmlGrupo_t += '</table>';
                    strHtmlGrupo_t += '</div>';
                strHtmlGrupo_t += '</div>';
            strHtmlGrupo_t += '</div>';

        }else if(strSentido_p == "Saliente"){

            strHtmlGrupo_t += '<div class="col-md-3" style="padding : 2px;">';
                strHtmlGrupo_t += '<div class="info-box" style="background-color:'+strColor_p+'; color: #FFFFFF;">';
                    strHtmlGrupo_t += '<span class="info-box-icon"><i class="'+strIcono_p+'"></i></span>';
                    strHtmlGrupo_t += '<div class="info-box-content">';
                    strHtmlGrupo_t += '<table width="100%">';
                            strHtmlGrupo_t += '<tbody>';

                        

                                strHtmlGrupo_t += '<tr>';
                                    strHtmlGrupo_t += '<td style="text-align:center;" colspan="4">';
                                        strHtmlGrupo_t += '<span class="info-box-text">'+strGrupo_p+'</span>';
                                    strHtmlGrupo_t += '</td>';
                                strHtmlGrupo_t += '</tr>';
                                strHtmlGrupo_t += '<tr>';
                                    strHtmlGrupo_t += '<td style="text-align:left;">';
                                        strHtmlGrupo_t += '<span style="font-size:15px;" id="spanMeta1_'+intItMeta_p+'_'+intIdCampan_p+'" class="info-box-number"><i class="fa fa-phone"></i>&nbsp;&nbsp;'+arrValorMetas_p[0].strValor_t+'</span>';
                                    strHtmlGrupo_t += '</td>';

                                strHtmlGrupo_t += '</tr>';



                            strHtmlGrupo_t += '</tbody>';
                    strHtmlGrupo_t += '</table>';

                        strHtmlGrupo_t += '<div class="progress">';

                    strHtmlGrupo_t += '<div id="divProgres_'+intItMeta_p+'_'+intIdCampan_p+'" class="progress-bar" style="width: '+arrValorMetas_p[1].strValor_t+'%">';

                        strHtmlGrupo_t += '</div>';

                    strHtmlGrupo_t += '</div>';

                    if(arrValorMetas_p[1].strMeta_t == "TMO" && (arrValorMetas_p[1].strCanal == "telefonia" || arrValorMetas_p[1].strCanal == "6")){
                        strOperador_p = "Seg";
                        // Si el valor viene con -1 remplazarlo por un 0
                        arrValorMetas_p[1].strValor_t = (arrValorMetas_p[1].strValor_t == -1) ? 0 : arrValorMetas_p[1].strValor_t;
                        strHtmlGrupo_t += '<span id="spanMeta2_'+intItMeta_p+'_'+intIdCampan_p+'" class="progress-description">&nbsp;&nbsp;'+arrValorMetas_p[1].strMeta_t+'&nbsp;&nbsp;<strong>'+arrValorMetas_p[1].strValor_t+'&nbsp;'+strOperador_p+'</strong></span>';

                    }else if((arrValorMetas_p[1].strMeta_t == "%Contactados" || arrValorMetas_p[1].strMeta_t == "%Efectivos") && (arrValorMetas_p[1].strCanal == "telefonia" || arrValorMetas_p[1].strCanal == "6")){
                        strOperador_p = "%";
                        strHtmlGrupo_t += '<span id="spanMeta2_'+intItMeta_p+'_'+intIdCampan_p+'" class="progress-description">&nbsp;&nbsp;'+arrValorMetas_p[1].strMeta_t.replace('%', '')+'&nbsp;&nbsp;<strong>'+arrValorMetas_p[1].strValor_t.toFixed(2)+'&nbsp;'+strOperador_p+'</strong></span>';
                    
                        // Se valida si el marcador de la campaña es predictivo, en caso se le adicionan las opciones editables de este marcador
                    }else if(strTipoCamp_p == 7 && arrValorMetas_p[1].strMeta_t == "Predictivo" ){
                        strHtmlGrupo_t += `
                        <div id="spanMeta2_${intItMeta_p}_${intIdCampan_p}">
                            <table width="100%" style="table-layout: fixed;">
                                <tr>
                                    <td><div class="tooltipPredictivo" title="Llamadas en cola" style="width: max-content;"><i class="fas fa-clock"></i> &nbsp;&nbsp; <strong id="spanMeta2_${intItMeta_p}_${intIdCampan_p}_cola" >${arrValorMetas_p[1].strValor_t.intMetColaMarc_t}</strong></div></td>
                                    <td><div class="tooltipPredictivo" title="Llamadas en curso" style="width: max-content;"><i class="fas fa-phone-volume"></i> &nbsp;&nbsp;<strong id="spanMeta2_${intItMeta_p}_${intIdCampan_p}_marcando">${arrValorMetas_p[1].strValor_t.intMetLlamadaCurso_t}</strong></div></td>
                                    <td><div class="tooltipPredictivo" title="Nivel de aceleracion" style="width: max-content;"><i class="fas fa-tachometer-alt"></i>  &nbsp;&nbsp; <i class="fas fa-caret-down btnChangeAceleracion" actionBtn="down" style="cursor: pointer;"></i>  <strong id="spanMeta2_${intItMeta_p}_${intIdCampan_p}_aceleracion" idCampan="${intIdCampan_p}" value="${arrValorMetas_p[1].strValor_t.intAceleracion_t}">&nbsp;&nbsp;${arrValorMetas_p[1].strValor_t.intAceleracion_t}&nbsp;&nbsp;</strong> <i class="fas fa-caret-up btnChangeAceleracion" actionBtn="up" style="cursor: pointer;"></i></div></td></tr>
                            </table>
                        </div>
                        `;
                    } else {
                        strHtmlGrupo_t += '<span id="spanMeta2_'+intItMeta_p+'_'+intIdCampan_p+'" class="progress-description"></span>';

                    }
                    strHtmlGrupo_t += '</div>';
                strHtmlGrupo_t += '</div>';
            strHtmlGrupo_t += '</div>';

        }


        return strHtmlGrupo_t;

    }

    function scrollbottom(xp){
        var y = $("#cuerpoModal").height();  
        var proporcion = xp * y / 100;
        proporcion = Math.ceil(y + proporcion);
        $("#cuerpoModal").animate({ scrollTop: proporcion }, 2000);
    }

    function intrusionChat(id) {
        var htmlCampos = '';
        var valido = false;
        $.ajax({
            url: "pages/Dashboard/TiempoRealServicios.php",
            type: "POST",
            data: {
                intrusionChat: 'si',
                id: id
            },
            dataType: 'json',
            async: false,
            success: function(data) {
                if (data.estado == 'ok') {
                    valido = true;
                    $.each(data.mensaje, function(campo, item) {
                        if(campo != 'conteo'){
                            if (item.agente == '1') {
                                htmlCampos += '<div class="row" style="padding:0px 10px;margin-bottom:8px;"><div class="chat agente"><p class="textmensaje textmensajeFecha">' + item.fecha_hora + '</p><p class="textmensaje">' + item.nombre_usuario + '</p><p class="textmensaje">' + item.mensaje + '</p></div></div>';
                            } else {
                                htmlCampos += '<div class="row" style="padding:0px 10px;margin-bottom:8px;margin-right:5px"><div class="chat cliente"><p class="textmensaje textmensajeFecha">' + item.fecha_hora + '</p><p class="textmensaje">' + item.identificacion_usuario + '</p><p class="textmensaje">' + item.mensaje + '</p></div></div>';
                            }
                        }
                    });
                    $("#chatIntruso").html(htmlCampos);
                    if(data.mensaje.conteo > parseInt($("#conteoChat").val())){
                        scrollbottom(7500);
                    }
                    $("#conteoChat").val(data.mensaje.conteo);
                } else {
                    alertify.error(data.mensaje);
                    valido = false;
                }
            }
        });
        return valido;
    }

    function RecargarIntrusionChat(id) {
        stopIntrusion = setInterval(function() {
            intrusionChat(id);
        }, 5000);
    }

    /**
     *BGCR - Esta funcion crea la solicitud hacia el backend para el cambio de la aceleracion de una campaña
    *@param idCampan = id de la campaña, intAceleracion = valor de la aceleracion, id de la etiqueta en donde se muestra la aceleracion
    *@return void
    */

    function changeAceleration(idCampan, intAceleracion, spanId){
        $.ajax({
            url: "pages/Dashboard/TiempoRealServicios.php",
            type: "POST",
            data: {
                updateAceleracion: 'si',
                idCampan: idCampan,
                intAceleracion: intAceleracion
            },
            dataType: 'json',
            success: function (data) {
                $("#"+spanId).html('&nbsp;&nbsp;'+intAceleracion+'&nbsp;&nbsp;');
                $("#"+spanId).attr('value',intAceleracion );
            }
        });
    }

    // Se adiciona evento para que al cerrar el modal del marcador deje vacio el iframe del visor
    $(".closeVisorColaMArcador").click(() => {
        $("#ifrVisorColaMArcador").attr("src","");
    })


    /**
     *BGCR - Esta funcion remplaza las colas de la campañas que se encuentren pintadas
    *@param none
    *@return void
    */

    function colasTiempoReal () { 
        $.ajax({
            url: "pages/Dashboard/MetricasTiempoReal/getColas.php",
            type: "GET",
            dataType: 'JSON',
            success: function (data) {
                data.forEach(eCampan => {
                    // Se valida si la campaña esta pintada
                    if($("#divEstpas_"+eCampan.CAMPAN_ConsInte__b).length){
                        // Por cada cola se valida si el valor es diferente a off
                        eCampan.lstDefinicionColas_t.forEach(cola => {
                            let colaView = getColaDiv(eCampan.CAMPAN_ConsInte__b, cola.typeChannel);
                            if(colaView){
                                if(colaView.html() != "off"){
                                    colaView.html(cola.value);
                                }
                            }

                        });
                    }
                });
            }
        });
     }

     function getColaDiv(idCampan, typeChannel ) {

        let colaView;
        switch (typeChannel) {
                // Telefonia
                case 1:
                    colaView = $(`#spanMetaPor1_0_${idCampan}`);
                    break;

                // Chat
                case 14:
                    colaView = $(`#spanMetaPor2_0_${idCampan}`);
                    break;
                    
                case 15:
                    colaView = $(`#spanMetaPor3_0_${idCampan}`);
                    break;

                case 16:
                    colaView = $(`#spanMetaPor4_0_${idCampan}`);
                    break;

                case 17:
                    colaView = $(`#spanMetaPor5_0_${idCampan}`);
                    break;

                case 19:
                    colaView = $(`#spanMetaPor6_0_${idCampan}`);
                    break;
            
                default:
                    colaView = false;
                    break;
            }

        return colaView;
    }

    function calcTime(timeChange) {
        timeChange = moment(timeChange);
        let timeNow = moment();

        // Calcular la diferencia en milisegundos entre las dos fechas
        const diferenciaEnMilisegundos = timeNow.diff(timeChange);

        // Obtener las horas, minutos y segundos de la diferencia en formato HH:mm:ss
        const horas = Math.floor(diferenciaEnMilisegundos / 3600000);
        const minutos = Math.floor((diferenciaEnMilisegundos % 3600000) / 60000);
        const segundos = Math.floor(((diferenciaEnMilisegundos % 3600000) % 60000) / 1000);

        // Imprimir la diferencia en formato HH:mm:ss
        return`${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;

    }


    function calcAllTimesDrawed() {
        $('input[id^="reloj_ChangeDate"]').each((i,elementTime) => {
            elementTime = $(elementTime);
            let timeChanged = calcTime(elementTime.val());
            let idRelojNum = elementTime.attr("id").split("reloj_ChangeDate")[1];
            let idReloj = "reloj_"+idRelojNum;
            let idRelojTD = "relojTD_"+idRelojNum;
            $("#"+idReloj).html(timeChanged);
            $("#"+idRelojTD).html(timeChanged);
            
        });
    }

    //JDBD - Llamamos la funcion que muestra el tiempo real.
    tiempoReal();
    statusAgent();
    colasTiempoReal();
    //JDBD - Creamos un intervalo para que llame la funcion de las metricas cada 5 minutos. (No tiene sentido obtenerlo antes)

    var tiempoRealInt = setInterval(function () {  
        let minute = new Date().getMinutes();
        let seconds = new Date().getSeconds();
        // Se consultan las metricas despues de 30 segundos de que la hora sea un multiplo de 5 minutos
        if(minute%5 == 0 && seconds/30 == 1){
            tiempoReal();
        }
    }, 1000);

    var colasTiempoRealInt = setInterval(colasTiempoReal, 5000);
    var statusAgentInt = setInterval(statusAgent, 3000);
    var calcAllTimesDrawedInt = setInterval(calcAllTimesDrawed, 1000);
</script>

<!-- links para exportar a excel -->
<script src="https://unpkg.com/xlsx@0.16.9/dist/xlsx.full.min.js"></script>
<script src="https://unpkg.com/file-saverjs@latest/FileSaver.min.js"></script>
<script src="https://unpkg.com/tableexport@latest/dist/js/tableexport.min.js"></script>

<!-- Funciones Para Reporte De Calificaciones -->
<script>

    //Al Cargar Archivo
    $(document).ready(function() {
        $('#AcordIntrusion').click();
        $("#AcordIntrusion").prop('class', "collapsed");
    });

    //Funcion Para Descargar Archivo
    function DescargarArchivo() {

        const $TablaReporte = document.querySelector("#TablaReporte");
        let tableExport = new TableExport($TablaReporte, {
            exportButtons: true,  //Queremos botones
            filename: "Reporte Calificaciones",  //Nombre del archivo de Excel
            sheetname: "Reporte Calificaciones",  //Título de la hoja
        });
        let datos = tableExport.getExportData();
        let preferenciasDocumento = datos.tabla.xlsx;
        tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
    
    };

    //Funcion Para Generar Reporte
    $("#BtnReporteIntrusion").click(function(){
        let FormReporte = new FormData();
        var IdConsultar= $("#TxtIdAgente").val();
        console.log("IdConsultar: ", IdConsultar);
        $("#ModalLoading").modal();
        $("#calidad").hide();
        $("#intrusion").hide();

        var ArrayProyectos= [];
        var ArrayIdsProyectos= [];
        FormReporte.append('IdConsultar', IdConsultar);
        $.ajax({
            url: "pages/Dashboard/MetricasTiempoReal/ConsultarReporte.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormReporte,
            success: function(php_response) {
                Respuesta= php_response.msg;
                Resultado= php_response.Resultado;
                $("#ContTitulos").attr('hidden', false);
                console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    //console.log("Resultado: ", Resultado);
                    for (let i = 0; i < Resultado.length; i++) {
                        const Datos = Resultado[i];
                        //console.log("Datos: ", Datos);
                        var Contador= 0;
                        var Row = $("<tr id='ContFilas' style='text-align: center;'>");
                        Datos.forEach(Dato => {
                            //console.log("Dato: ", Dato);
                            Contador= Contador+1;
                            if((Contador == 1) || (Contador == 6)){
                                Row.append($("<td style='position: relative;' hidden='true'>").text(Dato));
                            }else if((Contador == 8) || (Contador == 10)){
                                Row.append($("<td style='position: relative;' hidden='true'>").text(Dato));
                            }else if(Contador == 15){
                                Row.append($("<td style='position: relative;' hidden='true'>").text(Dato));
                                ArrayIdsProyectos.push(Dato);
                            }else{
                                Row.append($("<td style='position: relative;'>").text(Dato));
                                if(Contador == 2){
                                    ArrayProyectos.push(Dato);
                                }
                            }
                            $("#TablaReporte").append(Row);

                        });
                    }

                    function onlyUnique(value, index, self) { 
                        return self.indexOf(value) === index;
                    }
                    var SelectProyectos= $("#SelectProyectos");
                    var ListaIdsProyectos = ArrayIdsProyectos.filter( onlyUnique );
                    var ListaProyectos = ArrayProyectos.filter( onlyUnique );
                    ListaIdsProyectos = ListaIdsProyectos.filter(Nada => Nada != '');
                    ListaProyectos = ListaProyectos.filter(NoRegistrado => NoRegistrado != 'No Registrado');

                    for (let i = 0; i < ListaProyectos.length; i++) {                     
                        var OptionProyectos= $("<option>");
                        var IdProyecto= ListaIdsProyectos[i];
                        var Proyecto = ListaProyectos[i];
                        OptionProyectos.val(IdProyecto);
                        OptionProyectos.text(Proyecto);
                        SelectProyectos.append(OptionProyectos);   
                    }

                    $("#editarDatos").modal();
                    $("#ModalReporte").modal();
                    $("#ModalReporte").attr("style", "overflow-y: auto; display: block;");
                    $("#intrusion").show();
                    $("#calidad").show();
                    $('#ModalLoading').modal('hide');
                    DescargarArchivo();

                }else{
                    console.log("Respuesta: ", Respuesta);
                    swal({
                        icon: 'info',
                        title: ' 🤷🏽‍♂️  ¡Nada Por Aqui, Nada Por Alla! ', 
                        text: '!No Se Encontrarón Calificaciones Para Este Usuario!',
                        confirmButtonColor: '#2892DB'
                    })
                    $('#ModalLoading').modal('hide');
                    $("#calidad").show();
                    $("#intrusion").show();
                    
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
                console.log(php_response);
            }
        });

    });

    //Funcion Para Selecionar Filtros
    $("#SelectFiltrarPor").change(function(){
        TipoFiltro= $(this).val();
        //console.log("TipoFiltro: ", TipoFiltro);
        if(TipoFiltro == "Proyecto"){
            $("#divSelectProyectos").prop('hidden', false);
            $("#divFechaInicial").prop('hidden', true);
            $("#divFechaFinal").prop('hidden', true);
            $("#divBtnAplicarFiltros").prop('hidden', false);
        }else{
            $("#divSelectProyectos").prop('hidden', true);
            $("#divFechaInicial").prop('hidden', false);
            $("#divFechaFinal").prop('hidden', false);
            $("#divBtnAplicarFiltros").prop('hidden', false);
        }

    });

    //Funcion Para Desbloquear Fecha Final 
    $('body').on('change', '#FechaInicial', function() {
        document.getElementById("FechaFinal").disabled = false;
    });

    //Funcion Para Enviar Filtros
    function EnviarFiltros(FormFiltros) {

        var ArrayProyectos= [];
        var ArrayIdsProyectos= [];
        $.ajax({
            url: "pages/Dashboard/MetricasTiempoReal/ConsultarReporte.php",
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormFiltros,
            success: function(php_response) {
                Respuesta= php_response.msg;
                Resultado= php_response.Resultado;
                $("#ContTitulos").attr('hidden', false);
                console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){                
                    //console.log("Resultado: ", Resultado);
                    $("#BodyTabla").empty();
                    for (let i = 0; i < Resultado.length; i++) {
                        const Datos = Resultado[i];
                        //console.log("Datos: ", Datos);
                        var Contador= 0;
                        var Row = $("<tr id='ContFilas' style='text-align: center;'>");
                        Datos.forEach(Dato => {
                            //console.log("Dato: ", Dato);
                            Contador= Contador+1;
                            if((Contador == 1) || (Contador == 6)){
                                Row.append($("<td style='position: relative;' hidden='true'>").text(Dato));
                            }else if((Contador == 8) || (Contador == 10)){
                                Row.append($("<td style='position: relative;' hidden='true'>").text(Dato));
                            }else if(Contador == 15){
                                Row.append($("<td style='position: relative;' hidden='true'>").text(Dato));
                                ArrayIdsProyectos.push(Dato);
                            }else{
                                Row.append($("<td style='position: relative;'>").text(Dato));
                                if(Contador == 2){
                                    ArrayProyectos.push(Dato);
                                }
                            }
                            $("#TablaReporte").append(Row);

                        });
                    }

                    $('#ModalLoading').modal('hide');
                    $('#ModalReporte').modal();

                }else{
                    console.log("Respuesta: ", Respuesta);
                    swal({
                        icon: 'info',
                        title: ' 🤷🏽‍♂️  ¡Nada Por Aqui, Nada Por Alla! ', 
                        text: '!No Se Encontrarón Calificaciones Con Estos Filtros!',
                        confirmButtonColor: '#2892DB'
                    })
                    $("#BodyTabla").empty();
                    $('#ModalLoading').modal('hide');
                    $('#ModalReporte').modal();
                    
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
                console.log(php_response);
            }
        });
        
    }

    //Funcion Para Capturar Filtros
    $("#BtnAplicarFiltros").click(function(){ 
        
        let FormFiltros = new FormData();
        var IdAgente= $("#TxtIdAgente").val();
        var TipoFiltro= $('#SelectFiltrarPor').val();
        var IdProyecto= $("#SelectProyectos").val();
        var FechaInicial= $("#FechaInicial").val();
        var FechaFinal= $("#FechaFinal").val();

        function AletaVacio(CampoVacio) {
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "'+CampoVacio+'"',
                confirmButtonColor: '#2892DB'
            })
        }

        if(TipoFiltro == "Proyecto"){
            var CampoVacio= "Lista De Campañas";
            if((IdProyecto == null) || (IdProyecto == "")){
                AletaVacio(CampoVacio);  
            }else{
                $("#ModalLoading").modal();
                $('#ModalReporte').modal('hide');
                FormFiltros.append('IdAgente', IdAgente);
                FormFiltros.append('TipoFiltro', TipoFiltro);
                FormFiltros.append('IdProyecto', IdProyecto);
                FormFiltros.append('FechaInicial', FechaInicial);
                FormFiltros.append('FechaFinal', FechaFinal);

                EnviarFiltros(FormFiltros);
                
            }
        }else{
            if((FechaInicial == null) || (FechaInicial == "")){
                var CampoVacio= "Fecha Inicial";
                AletaVacio(CampoVacio);  
            }else if((FechaFinal == null) || (FechaFinal == "")){
                var CampoVacio= "Fecha Final";
                AletaVacio(CampoVacio);  
            }else{
                $("#ModalLoading").modal();
                $('#ModalReporte').modal('hide');
                FormFiltros.append('IdAgente', IdAgente);
                FormFiltros.append('TipoFiltro', TipoFiltro);
                FormFiltros.append('IdProyecto', IdProyecto);
                FormFiltros.append('FechaInicial', FechaInicial);
                FormFiltros.append('FechaFinal', FechaFinal);

                EnviarFiltros(FormFiltros);
                
            }
        }

    });

    //Funcion Para Limpiar Tabla Reporte
    function LimpiarTabla(){
        $("#BodyTabla").empty();
        $("#ModalReporte").modal('hide');
        $("#SelectFiltrarPor").val("Elige Una Opción");
        $("#divFechaInicial").prop('hidden', true);
        $("#divFechaFinal").prop('hidden', true);
        $("#divSelectProyectos").prop('hidden', true);
        $("#divBtnAplicarFiltros").prop('hidden', true);
    };

</script>
