// FUNCION PARA INSERTAR UNA GESTIÓN APENAS SE ABRA EL FORMULARIO DE GESTIONES O DE BUSQUEDA MANUAL
function insertarRegistroG(formulario_crm, agente, datoContacto=null,canal=null,origen=null,sentido=null,id_comunicacion=null,campana_crm=null,miembro=null) {
    var idInsertado = '';
    var contacto='';
    var strSentido='';
    if(sentido =='2'){
        strSentido='Entrante';  
    }
    
    if(sentido =='1'){
        strSentido='Saliente';  
    }    
    if(datoContacto != null){
        contacto=datoContacto;
    }
    try {
        $.ajax({
            url: 'formularios/generados/PHP_Ejecutar.php',
            type: 'post',
            dataType:'JSON',
            async : false,
            data: {
                insertaRegistro: 'si',
                formulario: formulario_crm,
                agente: agente,
                datoContacto:contacto,
                canal:canal,
                origen:origen,
                sentido:strSentido,
                id_comunicacion:id_comunicacion,
                campana_crm:campana_crm,
                miembro:miembro
            },
            success: function (resultado) {
                console.log('esto me retorna al insertar el registro: ',typeof(resultado), 'valor: ',resultado);
                if(resultado.estado == 'ok'){
                    idInsertado = resultado.mensaje;
                }else{
                    try {
                        //alertify.error(resultado.mensaje);
                    } catch (e) {
                        //alert(resultado.mensaje);
                    }
                }
            },
            beforeSend:function(){
                try{
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="assets/img/clock.gif" /> Por favor espere mientras se valida la gestión'
                    });
                }catch(e){

                }
            },
            complete : function(){
                try {
                    $.unblockUI();
                } catch (error) {
                }
            },
            error:function(){
                //alert('SE GENERO UN ERROR AL CREAR LA GESTIÓN, POR FAVOR CIERRE ESTA ALERTA Y SELECCIONE LA COMUNICACIÓN DESDE EL PANEL DE COMUNICACIONES');
            }
        });
        console.log('insertado: '+idInsertado+' | insertarRegistroG('+formulario_crm, agente, datoContacto,canal,origen,sentido,id_comunicacion,campana_crm,miembro+')');
        return idInsertado;

    } catch (e) {
        console.log(e);
        return false;
    }
}

function selectedOption(campo,value){
    try {
        if($("#"+campo+" option[value='"+value+"']").text()){
            $("#"+campo).val(value);
            $("#"+campo).change();
            setTimeout(function(){
                $("#"+campo+" option[value='"+value+"']").prop('selected',true);
                $("#"+campo).select2();
            },500);
        }else{
            $("#"+campo).html("<option value='"+value+"'>Option</option>");
            $("#"+campo).change();
        }
        // let thisVal=$("#"+campo).attr('valstorage');
        // $("#"+campo+" option[value='"+value+"']").attr('selected',true);
        // console.log($("#"+campo).val());
        console.log('valstorage',value);
        // $("#"+campo).select2();
    } catch (error) {
        
    }
}

// FUNCION PARA CAMBIAR EN EL FORMULARIO LOS VALORES DE LOS CAMPOS QUE ESTEN GUARDADOS EN EL STORAGE
function asignarValores(ObjCliente,formData=null){
    console.log("me llaman");
    let conteo,campoEditado=0,section,collapse;
    // AQUI VA EL CODIGO PARA ASIGNAR LOS VALORES A LOS CAMPOS, YA SE HACE CUANDO EL ID_USER NO EXISTE, PERO FALTA QUE LOS DATOS PERSISTAN EN LA BUSQUEDA MANUAL
    $.each(ObjCliente, function (i, item) {
        conteo = i;
        ObjCliente[i] = item;
        
        //VALIDAMOS SI HAY NUEVOS CAMPOS QUE SE HAN MODIFICADO EN EL FORMULARIO PARA LA MISMA GESTIÓN
        if (typeof (formData) == 'object' && formData != null) {
            if (formData.hasOwnProperty('collapse')) {
                // ESTO ES PARA MANEJAR LAS SECCIONES DE ACORDEON
                if (ObjCliente[i].hasOwnProperty('collapse') && ObjCliente[i].href == formData.href) {
                    // LA SECCIÓN YA EXISTE, PERO LE DIERON DE NUEVO CLICK, HAY QUE ACTUALIZAR EL ESTADO EN EL STORAGE
                    campoEditado = 1;
                    ObjCliente[i].href = formData.href;
                    ObjCliente[i].collapse = formData.collapse;
                }
            }else {
                // ESTO ES PARA MANEJAR LOS CAMPOS DEL FORMULARIO
                if (ObjCliente[i].hasOwnProperty('id') && ObjCliente[i].id == formData.id) {
                    // EL CAMPO YA EXISTE, PERO LO MODIFICARON DE NUEVO, HAY QUE ACTUALIZAR SU VALOR
                    campoEditado = 1;
                    $("#"+ObjCliente[i].id).attr('valstorage',formData.value)
                    ObjCliente[i].value = formData.value;
                }
            }
        }

        // ESTO ES PARA PERSISTIR EL ESTADO DE LAS SECCIONES DE TIPO ACORDEON, O SEA, SI EL AGENTE LAS DEJO ABIERTAS O CERRADAS
        if(formData === null){            
            $(document).ready(function(){
                if (ObjCliente[i].hasOwnProperty('collapse')) {
                    var click = {};
                    section = document.getElementsByTagName('a');
                    $.each(section, function (h, href) {
                        if ($(this).attr('href') == ObjCliente[i].href) {
                            collapse = ObjCliente[i].href;
                            if ($(collapse).hasClass('in')) {
                                if (ObjCliente[i].collapse == 'false') {
                                    click[i] = {};
                                    click[i].href = ObjCliente[i].href;
                                    if (click[i].hasOwnProperty('click')) {
    
                                    } else {
                                        $(this).attr('aria-expanded', 'false');
                                        $(collapse).removeClass('in');
                                        $(collapse).attr('aria-expanded', 'false');
                                        click[i].click = 'si';
                                        console.log(click[i]);
                                    }
                                }
                            } else {
                                if (ObjCliente[i].collapse == 'true') {
                                    click[i] = {};
                                    click[i].href = ObjCliente[i].href;
                                    if (click[i].hasOwnProperty('click')) {
    
                                    } else {
                                        $(this).attr('aria-expanded', 'true');
                                        $(collapse).addClass('in');
                                        $(collapse).attr('aria-expanded', 'true');
                                        click[i].click = 'si';
                                        console.log(click[i]);
                                    }
                                }
    
                            }
                        }
                    });
                }
                // LE DAMOS EL VALOR QUE TENGA EL STORAGE GUARDADO A LOS ELMENTOS INPUT Y TEXTAREA DEL FORMULARIO
                if (ObjCliente[i].type == 'INPUT' || ObjCliente[i].type == 'TEXTAREA') {
                    console.log('asignando valores del storage',ObjCliente[i].id);
                    $("#" + ObjCliente[i].id).val(ObjCliente[i].value);
                    $("#" + ObjCliente[i].id).attr('valstorage',ObjCliente[i].value);
                    setTimeout(function(){
                        try {
                            $("#" + ObjCliente[i].id).change();
                        } catch (error) {}
                    },500);
                }

                if(ObjCliente[i].type == 'SELECT'){
                    $("#" + ObjCliente[i].id).attr('valstorage',ObjCliente[i].value);
                    setTimeout(function(){
                        selectedOption(ObjCliente[i].id,ObjCliente[i].value);
                    },1000);
                }

                if(ObjCliente[i].type == 'radio'){
                    $("input[name='" + ObjCliente[i].id + "']").val(ObjCliente[i].value);
                    $("#opt_" + ObjCliente[i].value).prop('checked',true).trigger('change');
                    $("input[name='" + ObjCliente[i].id + "']").attr('valstorage',ObjCliente[i].value);
                    setTimeout(function(){
                        try {
                            setTimeout(function(){
                                $("#" + ObjCliente[i].id).val(ObjCliente[i].value);
                            },500);
                            $("#" + ObjCliente[i].id).change();
                        } catch (error) {}
                    },500);
                }                
            });
        }
    });

    if (campoEditado === 0) {
        conteo++;
        if (typeof (formData) == 'object' && formData != null) {
            ObjCliente[conteo] = formData;
            // $("#"+formData.id).attr('valstorage',formData.value);
        }
    }

    return ObjCliente;
}

// FUNCIÓN QUE REDIRECCIONA DE LA BUSQUEDA MANUAL AL FORMULARIO DE GESTIONES CUANDO SE ABRA UN REGISTRO DESDE LA BUSQUEDA MANUAL
function redireccionar(allGestiones,strOrigen,strAni){
    $(document).ready(function(){
        console.log('redireccionando al storage',allGestiones,strOrigen,strAni);
        // OCULTAR EL FORMULARIO DE BUSQUEDA MANUAL
        $("#buscador").hide();
        $("#botones").hide();
        $("#resulados").hide();
    
        $("#frameContenedor").attr('src', allGestiones.ObjGestion["server"] + '/crm_php/Estacion_contact_center.php?campan=true&user=' + allGestiones.id_user + '&view=si&canal=' + allGestiones.ObjGestion["canal"] + '&token=' + allGestiones.ObjGestion["token"] + '&id_gestion_cbx=' + allGestiones.id_gestion + '&predictiva=' + allGestiones.ObjGestion["predictiva"] + '&consinte=' + allGestiones.ObjGestion["consinte"] + '&campana_crm=' + allGestiones.ObjGestion["id_campana_crm"] + '&id_campana_cbx=' + allGestiones.ObjGestion["id_campana_cbx"] + '&sentido=' + allGestiones.ObjGestion["sentido"] + '&usuario=' + allGestiones.ObjGestion["usuario"]+ '&origen='+strOrigen+'&ani='+strAni);
    });
}

// FUNCIÓN QUE PROCESA LA PETICIÓN PARA ACTUALIZAR EL CODIGO MIEMBRO DEL REGISTRO CUANDO SE ABRA UN REGISTRO DESDE LA BUSQUEDA MANUAL
function actualizarMiembro(id,origen,id_gestion,campana,formulario,miembro,usuario){
    try {
        $.ajax({
            url: 'formularios/generados/PHP_Ejecutar.php',
            type: 'post',
            dataType:'JSON',
            data: {
                id: id,
                origen:origen,
                id_gestion:id_gestion,
                campana:campana,
                formulario: formulario,
                miembro:miembro,
                usuario:usuario,
                actualizarMiembro:'si'
            },
            success:function(data){

            },
            beforeSend:function(){
                try{
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="assets/img/clock.gif" /> Por favor espere mientras se valida la gestión'
                    });
                }catch(e){

                }
            },
            complete : function(){
                try {
                    $.unblockUI();
                } catch (error) {
                }
            },
            error:function(){
                //alert('SE GENERO UN ERROR AL ACTUALIZAR EL CODIGO MIEMBRO');
            }
        });
    } catch (e) {
        console.log(e);
    }
}

// FUNCIÓN QUE VALIDA QUE EL REGISTRO INSERTADO EN LA TABLA DE GESTIONES COINCIDA CON EL QUE EL STORAGE TIENE ALMACENADO CUANDO SE RECARGUE LA COMUNICACIÓN EN LA BUSQUEDA MANUAL
function validarRegistro(id,origen,id_gestion,campana,formulario,miembro,usuario){
    //PENDIENTE VALIDAR EL FORMULARIO EN PHP EJECUTAR
    try {
        $.ajax({
            url: 'formularios/generados/PHP_Ejecutar.php',
            type: 'post',
            dataType:'JSON',
            data: {
                id: id,
                origen:origen,
                id_gestion:id_gestion,
                campana:campana,
                formulario: formulario,
                miembro:miembro,
                usuario:usuario,
                validarRegistro:'si'
            },
            success:function(data){
                if(data.estado=='error'){
                    //alert(data.mensaje);
                }
            },
            beforeSend:function(){
                try{
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="assets/img/clock.gif" /> Por favor espere mientras se valida la gestión'
                    });
                }catch(e){

                }
            },
            complete : function(){
                try {
                    $.unblockUI();
                } catch (error) {
                }
            },
            error:function(){
                //alert('SE GENERO UN ERROR AL VALIDAR LA COMUNICACIÓN');
            }
        });
    } catch (e) {
        console.log(e);
    }
}

// FUNCIÓN QUE GUARDA UNA COMUNICACIÓN EN EL STORAGE
function guardarStorage(idGestion, id_user, dataGestion, formData = null, formulario_crm = null, agente = null, datoContacto=null, canal=null, origen=null,sentido=null,campana_crm=null,rellamada=false,validar=false) {
    console.log("storage invocado sin borrar cache CON OTRO CAMBIO 2",idGestion, id_user, dataGestion, formData, formulario_crm, agente, datoContacto, canal, origen,sentido,campana_crm,rellamada,validar);
    if(validar){
        try{
            if (typeof (Storage) !== 'undefined') {
                var d = new Date();
                var mes = '0';
                var minuto = '0';
                var segundo = '0';
                var dia = '0';
                if (d.getMonth() > 9) {
                    mes = '';
                }
                if (d.getMinutes() > 9) {
                    minuto = '';
                }
                if (d.getSeconds() > 9) {
                    segundo = '';
                }
                if (d.getDate() > 9) {
                    dia = '';
                }
                var strFecha_t = d.getFullYear() + '-' + mes + (d.getMonth() + 1) + '-' + dia + d.getDate() + ' ' + d.getHours() + ':' + minuto + d.getMinutes() + ':' + segundo + d.getSeconds();

                var ObjInicio_t = {};
                var saveLocal = {};
                var allGestiones = {};
                var regGestionado = {};
                var ObjCampos_t = {};
                var cliGestionado = {};
                var select = {};

                var getGestiones = sessionStorage.getItem("gestiones");
                var yaExiste = 0;
                var campoEditado = 0;
                var contador, gestion, conteo, section, collapse, RegistroInsertado;
                
                var strOrigen='Sin origen';
                var strAni='0';
                var telefonia=false;
                var valido=true;

                // VALIDAR SI EL OBJETO EN EL STORAGE YA EXISTE
                if (getGestiones !== null && getGestiones !== "" && getGestiones !== false && getGestiones !== undefined) {
                    gestion = JSON.parse(getGestiones);

                    // HAY QUE RECORRER TODO EL STORAGE PARA VOLVER A ASIGNARLE LOS OBJETOS QUE YA TENGA GUARDADOS, SI ESTO NO SE HACE, ENTONCES SE PERDERIAN
                    $.each(gestion, function (index, value) {
                        contador = index;
                        allGestiones[index] = value;
                        try{
                            // VALIDAMOS SI EXISTE UNA GESTIÓN DE TELEFONIA, ESTO LO HAGO PARA NO DUPLICAR LAS GESTIONES DE TELEFONIA YA QUE SIEMPRE DEBE EXISTIR MAXIMO 1 EN EL STORAGE
                            let tipoCanal=allGestiones[index].id_gestion.split("telefonia");
                            if(tipoCanal.length > 1){
                                telefonia=true;
                            }
                        }catch(e){
                            console.warn(e);
                        }

                        // VALIDAR EL ID_USER, SI ES -1 O NULO ES PORQUE NO SE HA ABIERTO EL FORMULARIO DE GESTIONES Y DEBE ESTAR EN EL FORMULARIO DE BUSQUEDA MANUAL
                        if (typeof (id_user) !== 'undefined' && id_user !== null && id_user !== 'N/A') {
                            if (typeof (value) == 'object' && value.hasOwnProperty('id_gestion')) {
                                if (value.id_gestion == idGestion) {
                                    //VALIDAR SI YA TIENE EL FORMULARIO ABIERTO PARA SABER SI TOCA REDIRECCIONAR DE LA BUSQUEDA MANUAL AL FORMULARIO
                                    yaExiste = 1;
                                    console.log("entro al storage por el if de id_user");
                                    regGestionado.id_gestion = value.id_gestion;
                                    regGestionado.fechaInicio = value.fechaInicio;
                                    regGestionado.ObjGestion = dataGestion;

                                    if (value.hasOwnProperty('RegistroInsertado')) {
                                        regGestionado.RegistroInsertado = value.RegistroInsertado;
                                    }else{
                                        if (formulario_crm != 'null' && formulario_crm != null) {
                                            cliGestionado.id_gestion = value.id_gestion;
                                            cliGestionado.fechaInicio = value.fechaInicio;

                                            if (allGestiones[index].hasOwnProperty('FormAbierto')) {
                                                cliGestionado.FormAbierto = value.FormAbierto;
                                            }

                                            if (allGestiones[index].hasOwnProperty('id_user')) {
                                                cliGestionado.id_user = value.id_user;
                                            }

                                            if (allGestiones[index].hasOwnProperty('ObjGestion')) {
                                                cliGestionado.ObjGestion = value.ObjGestion;
                                            }

                                            if (allGestiones[index].hasOwnProperty('ObjCliente')){
                                                cliGestionado.ObjCliente = value.ObjCliente;
                                            }

                                            if(!rellamada){
                                                RegistroInsertado = insertarRegistroG(formulario_crm, agente, datoContacto, canal, origen,sentido,idGestion,campana_crm,id_user);
                                                if (RegistroInsertado) {
                                                    cliGestionado.RegistroInsertado = RegistroInsertado;
                                                }
                                            }

                                            allGestiones[index] = cliGestionado;
                                        }
                                    }

                                    if(value.hasOwnProperty('FormAbierto')){
                                        regGestionado.FormAbierto = value.FormAbierto;
                                        if(value.hasOwnProperty('id_user') && id_user == '-1' && value.id_user !='-1'){
                                            //YA TENIA UN ID DE USER Y EL FORMULARIO ABIERTO, HAY QUE REDIRECCIONAR AL FORMULARIO DE GESTIONES
                                            if(value.ObjGestion["origen"].length >0){
                                                strOrigen=value.ObjGestion["origen"];
                                            }

                                            if(value.ObjGestion["ani"].length >0){
                                                strAni=value.ObjGestion["ani"];
                                            }

                                            regGestionado.id_user = value.id_user;
                                            regGestionado.FormAbierto = true;

                                            redireccionar(value,strOrigen,strAni);
                                        }else{
                                            if(id_user != '-1' && value.id_user == '-1'){
                                                // SE ABRIO EL FORMULARIO DE GESTIONES
                                                regGestionado.FormAbierto = true;
                                                actualizarMiembro(value.RegistroInsertado,value.ObjGestion["origen"],value.id_gestion,value.ObjGestion["id_campana_crm"],value.ObjGestion["formulario"],id_user,value.ObjGestion["usuario"]);
                                            }else{
                                                // SE RECARGO LA COMUNICACIÓN EN LA BUSQUEDA MANUAL, LA VALIDACIÓN CUANDO SE RECARGA DESDE EL FORMULARIO DE GESTIONES LA HACE PIES.PHP
                                                if(id_user == '-1' && value.id_user == '-1'){
                                                    regGestionado.FormAbierto = false;
                                                    validarRegistro(value.RegistroInsertado,value.ObjGestion["origen"],value.id_gestion,value.ObjGestion["id_campana_crm"],value.ObjGestion["formulario"],id_user,value.ObjGestion["usuario"]);
                                                }
                                            }
                                            regGestionado.id_user = id_user;
                                        }
                                    }else{
                                        regGestionado.FormAbierto = false;
                                        regGestionado.id_user = id_user;
                                    }

                                    if (value.hasOwnProperty('ObjCliente')){
                                        regGestionado.ObjCliente = value.ObjCliente;
                                        asignarValores(value.ObjCliente);
                                    }

                                    allGestiones[index] = regGestionado;
                                }
                            }
                        }else {
                            if (typeof (allGestiones[index]) == 'object') {
                                if (allGestiones[index].id_gestion == idGestion) {
                                    yaExiste = 1;
                                    try{
                                        if (allGestiones[index].hasOwnProperty('ObjCliente')) {
                                            if(typeof(allGestiones[index].ObjCliente) == 'object'){
                                                console.log('tiene campos editados');
                                                allGestiones[index].ObjCliente=asignarValores(allGestiones[index].ObjCliente,formData);
                                            }
                                        } else {
                                            // AGREGAMOS LOS CAMPOS QUE EL AGENTE HA CAMBIADO EN EL FORMULARIO Y CREAMOS EL OBJETO ObjCliente PORQUE EN ESTE PASO NO EXISTE
                                            if (typeof (formData) == 'object' && formData != null) {

                                                if (typeof (value) == 'object') {
                                                    cliGestionado.id_gestion = value.id_gestion;
                                                    cliGestionado.fechaInicio = value.fechaInicio;

                                                    if (allGestiones[index].hasOwnProperty('id_user')) {
                                                        cliGestionado.id_user = value.id_user;
                                                    }

                                                    if (allGestiones[index].hasOwnProperty('ObjGestion')) {
                                                        cliGestionado.ObjGestion = value.ObjGestion;
                                                    }

                                                    if (allGestiones[index].hasOwnProperty('FormAbierto')) {
                                                        cliGestionado.FormAbierto = value.FormAbierto;
                                                    }

                                                    if (allGestiones[index].hasOwnProperty('RegistroInsertado')) {
                                                        cliGestionado.RegistroInsertado = value.RegistroInsertado;
                                                    }
                                                }

                                                ObjCampos_t[0] = formData;
                                                cliGestionado.ObjCliente = ObjCampos_t;
                                                allGestiones[index] = cliGestionado;
                                            }
                                        }
                                    }catch(e){
                                        console.warn(e);
                                    }
                                }
                            }else{
                                console.log("entro aqui 123");
                                if (idGestion == value || idGestion === null) {
                                    yaExiste = 1;
                                }
                            }
                        }
                    });
                    
                    console.log('cierro el storage para los registros que ya existen',JSON.stringify(allGestiones));
                    sessionStorage.setItem("gestiones", JSON.stringify(allGestiones));

                    //AGREGAR LAS NUEVAS COMUNICACIONES AL STORAGE
                    if (yaExiste === 0 && idGestion !== null && idGestion !== '' && idGestion !== '0' && formData === null) {
                        //AGREGAMOS LAS NUEVAS COMUNICACIONES AL STORAGE
                        try{
                            //VALIDAMOS SI LA NUEVA COMUNICACIÓN ES DE TELEFONIA Y SI YA NO EXISTE UNA GESTIÓN DE TELEFONIA
                            let tipoGestion=idGestion.split("telefonia");
                            if(tipoGestion.length > 1 && telefonia){
                                valido=false;
                            }
                            
                        }catch(e){
                            console.warn(e)
                        }
                        
                        //AGREGAR NUEVAS COMUNICACIÓNES
                        console.log('storage valido',valido);
                        if(valido){ 
                            console.log('hay que agregar una comunicación nueva al storage');
                            contador++;
                            ObjInicio_t.fechaInicio = strFecha_t;
                            ObjInicio_t.id_gestion = idGestion;
                            ObjInicio_t.ObjGestion = dataGestion;

                            if(typeof (id_user) !== 'undefined' && id_user !== null && id_user !== 'N/A'){
                                ObjInicio_t.id_user = id_user;
                                if(id_user !='-1'){
                                    ObjInicio_t.FormAbierto = true;
                                }else{
                                    ObjInicio_t.FormAbierto = false;
                                }
                            }else{
                                ObjInicio_t.FormAbierto = false;
                                ObjInicio_t.id_user = '-1';
                            }

                            if (formulario_crm != 'null' && formulario_crm != null) {
                                if(!rellamada){
                                    RegistroInsertado = insertarRegistroG(formulario_crm, agente, datoContacto, canal, origen,sentido,idGestion,campana_crm,ObjInicio_t.id_user);
                                    if (RegistroInsertado) {
                                        ObjInicio_t.RegistroInsertado = RegistroInsertado;
                                    }
                                }
                            }

                            allGestiones[contador] = ObjInicio_t;
                            sessionStorage.setItem("gestiones", JSON.stringify(allGestiones));
                        }else{
                            //LA NUEVA COMUNICACIÓN ES DE TELEFONIA PERO YA HAY UNA GESTIÓN DE TELEFONIA Y COMO NO PUEDEN HABER 2, ENTONCES ES UNA RELLAMADA, HAY ES QUE ACTUALIZAR EL ID DE COMUNICACIÓN
                            let getGestion = sessionStorage.getItem("gestiones");
                            getGestion = JSON.parse(getGestion);
                            $.each(getGestion, function (i, item) {
                                try{
                                    if (typeof (item) == 'object') {
                                        //BUSCAMOS EN EL STORAGE CUAL ES EL OBJETO QUE YA TIENE LA GESTIÓN DE TELEFONIA
                                        tipocanal=item.id_gestion.split("telefonia");
                                        if(tipocanal.length > 1){
                                            let gestionActual=getGestion[i].id_gestion;
                                            let thisFormulario;
                                            if (formulario_crm != 'null' && formulario_crm != null) {
                                                thisFormulario=formulario_crm;
                                            }else{
                                                thisFormulario=$("#main #FormularioDatos").contents().find("#script").val();
                                            }
                                            //TOCA ACTUALIZAR EL ID DE COMUNICACIÓN YA QUE SE PROCESO UNA LLAMADA
                                            console.log('este es el formulario',thisFormulario);

                                            //ESTO APLICA CUANDO ESTAMOS EN LA BUSQUEDA MANUAL
                                            if(thisFormulario == '-1'){
                                                thisFormulario=gestion[i].ObjGestion["formulario"];
                                            }

                                            if(!getGestion[i].hasOwnProperty('RegistroInsertado')){
                                                //SE GENERO UNA NUEVA LLAMADA EN EL MISMO CODIGO MIEMBRO, HAY QUE INSERTAR UN NUEVO REGISTRO
                                                RegistroInsertado = insertarRegistroG(thisFormulario, getGestion[i].ObjGestion["usuario"], getGestion[i].ObjGestion["ani"], getGestion[i].ObjGestion["canal"], getGestion[i].ObjGestion["origen"],getGestion[i].ObjGestion["sentido"],idGestion,getGestion[i].ObjGestion["id_campana_crm"],getGestion[i].ObjGestion["consinte"]);
                                                if (RegistroInsertado) {
                                                    getGestion[i].RegistroInsertado = RegistroInsertado;
                                                }
                                            }

                                            //VALIDAR SI ES UNA LLAMADA TRANSFERIDA
                                            let arrLlega=idGestion.split("telefonia_");
                                            let arrEsta=getGestion[i].id_gestion.split("telefonia_");
                                            
                                            if(arrLlega.length > 1 && arrEsta.length > 1){
                                                if(arrEsta[1].split("_").length == 2){
                                                    idGestion=getGestion[i].id_gestion;
                                                }
                                            }
                                            
                                            //ESTE AJAX HACE QUE SE ACTUALICE EL ID DE COMUNICACIÓN EN EL FORMULARIO Y EN EL STORAGE
                                            $.ajax({
                                                url      : 'formularios/generados/PHP_Ejecutar.php',
                                                type     : 'POST',
                                                async     : false,
                                                data     : {
                                                    gestionActual : gestionActual,  
                                                    gestionNueva: idGestion, 
                                                    formulario: thisFormulario, 
                                                    campan:getGestion[i].ObjGestion["id_campana_crm"], 
                                                    origen:getGestion[i].ObjGestion["origen"], 
                                                    insertado: getGestion[i].RegistroInsertado, 
                                                    usuario:getGestion[i].ObjGestion["usuario"], 
                                                    miembro:getGestion[i].ObjGestion["consinte"], 
                                                    actualizarComunicacionStorage:'si'
                                                },
                                                dataType : 'JSON',
                                                success  : function(data) {
                                                    // ACTUALIZAMOS EL ID DE COMUNICACIÓN PARA ESTE OBJETO DE COMUNICACIÓN
                                                    if(data.estado == 'ok'){
                                                        getGestion[i].id_gestion=idGestion;
                                                        sessionStorage.setItem("gestiones", JSON.stringify(getGestion));
                                                    }else{
                                                        console.log(data.mensaje);
                                                    }
                                                },
                                                beforeSend:function(){
                                                    try{
                                                        $.blockUI({
                                                            baseZ: 2000,
                                                            message: '<img src="assets/img/clock.gif" /> Por favor espere mientras se valida la gestión'
                                                        });
                                                    }catch(e){
                                    
                                                    }
                                                },
                                                complete : function(){
                                                    try {
                                                        $.unblockUI();
                                                    } catch (error) {
                                                    }
                                                },
                                                error:function(){
                                                    console.log('SE GENERO UN ERROR AL ACTUALIZAR EL IDENTIFICADOR DE LA COMUNICACIÓN');
                                                }
                                            });
                                        }
                                    }
                                }catch(e){
                                    console.warn(e);
                                    getGestion[i].id_gestion=idGestion;
                                    sessionStorage.setItem("gestiones", JSON.stringify(getGestion));
                                }
                            });
                        }
                    }

                }else{
                    //CREAMOS EL OBJETO DEL STORAGE CUANDO NO EXISTA
                    if (idGestion !== '' && idGestion !== '0') {
                            ObjInicio_t.fechaInicio = strFecha_t;
                            ObjInicio_t.id_gestion = idGestion;
                            ObjInicio_t.ObjGestion = dataGestion;

                            if(typeof (id_user) !== 'undefined' && id_user !== null && id_user !== 'N/A'){
                                ObjInicio_t.id_user = id_user;
                                if(id_user !='-1'){
                                    ObjInicio_t.FormAbierto = true;
                                }else{
                                    ObjInicio_t.FormAbierto = false;
                                }
                            }else{
                                ObjInicio_t.FormAbierto = false;
                                ObjInicio_t.id_user = '-1';
                            }

                            if (formulario_crm != 'null' && formulario_crm != null) {
                                if(!rellamada){
                                    RegistroInsertado = insertarRegistroG(formulario_crm, agente, datoContacto, canal, origen,sentido,idGestion,campana_crm,ObjInicio_t.id_user);
                                    if (RegistroInsertado) {
                                        ObjInicio_t.RegistroInsertado = RegistroInsertado;
                                    }
                                }
                            }
                            saveLocal[0] = ObjInicio_t;
                            sessionStorage.setItem("gestiones", JSON.stringify(saveLocal));
                            console.log('crear el storage por primera vez');
                        }
                }
            } else {
                // EL NAVEGADOR NO ES COMPATIBLE CON EL STORAGE
                console.log('sessionStorage no compatible');
            }
        }catch(e){
            console.warn(e);
        }
    }
}

// FUNCIÓN QUE BORRA UNA COMUNICACIÓN DEL STROAGE
function borrarStorage(idGestion) {
    var getGestion = sessionStorage.getItem("gestiones");
    var telefonia=false;
    var tipocanal;
    try {
        var objTelefonia_t=idGestion.split("telefonia");
        if(objTelefonia_t.length > 1){
            telefonia=true;
        }
    } catch (e) {
        console.warn(e);
    }
    console.log('borra telefonia antes del each',idGestion,telefonia);
    getGestion = JSON.parse(getGestion);

    // RECORRER CADA GESTIÓN DEL STORAGE PARA ENCONTAR LA QUE COINCIDA CON EL VALOR DEL PARAMETRO
    $.each(getGestion, function (i, item) {
        if (typeof (item) == 'object') {
            if(telefonia){
                try{
                    // SI LA COMUNICACIÓN QUE HAY QUE BORRAR ES DE TELEFONIA, ENTONCES BORRAMOS TODAS LAS COMUNICACIONES DE TELEFONIA QUE HAYAN, NO DEBE DE HABER MAS DE 1, PERO HAY QUE HACERLO POR SEGURIDAD
                    console.log('borra telefonia');
                    tipocanal=item.id_gestion.split("telefonia");
                    if(tipocanal.length > 1){
                        delete getGestion[i];
                        sessionStorage.setItem("gestiones", JSON.stringify(getGestion));
                    }
                }catch(e){
                    console.warn(e);
                }
            }else{
                // SI LA COMUNICACIÓN QUE HAY QUE ELIMINAR NO ES DE TELEFONIA, ENTONCES ELIMINAMOS SOLO ESA COMUNICACIÓN
                if (item.hasOwnProperty('id_gestion') && item.id_gestion == idGestion) {
                    delete getGestion[i];
                    sessionStorage.setItem("gestiones", JSON.stringify(getGestion));
                }
            }
        }

        // VALIDAR SI EL OBJETO DEL STORAGE QUEDO VACIO, SÍ SI, ENTONCE BORRAMOS TOTALMENTE EL STORAGE
        if (Object.entries(getGestion).length === 0) {
            sessionStorage.removeItem("gestiones");
        }
    });

}

// FUNCIÓN QUE AGREGA LOS EVENTOS DE CHANGE() Y CLICK() A LOS CAMPOS DE LOS FORMULARIOS
function agregarEventos(){
    console.log('agregar eventos del storage');
    try {
        // AGREGAMOS UN EVENTO DE CHANGE A TODOS LOS ELEMENTOS DEL FORMULARIO
        let targetNode = document.querySelector("form");
        for (let i = 0, len = targetNode.length; i < len; i += 1) {
            $(targetNode[i]).on("change", ev => {
                let Campo = {
                    id: ev.target.id,
                    value: ev.target.value,
                    type: ev.target.tagName
                };

                if(ev.target.type == 'radio'){
                    Campo.type= ev.target.type
                    Campo.id=ev.target.name;
                }
                let gestion = document.getElementById('id_gestion_cbx').value;
                guardarStorage(gestion, null, null, Campo,null,null,null,null,null,null,null,false,true);
            });
        }
    
    } catch (e) {
        console.warn(e);
    }

    // DETECTAR LOS CLICK EN LAS SECCIONES PARA ALMACENARLOS EN EL STORAGE
    $('a').click(function () {
        if ($(this).attr('data-toggle')) {
            try {
                var collapse = $(this).attr('href');
                var campo = {
                    href: collapse
                };
        
                if ($(collapse).hasClass('in')) {
                    campo.collapse = 'false';
                } else {
                    campo.collapse = 'true';
                }
        
                var gestion = document.getElementById('id_gestion_cbx').value;
                guardarStorage(gestion, null, null, campo,null,null,null,null,null,null,null,false,true);
            } catch (e) {
                console.warn(e);
            }
        }
    });
}
