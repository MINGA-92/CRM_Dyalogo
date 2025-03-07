<?php
    require_once(__DIR__ ."/../../helpers/parameters.php");
?>
<script>
    function llamarIntegracionWS(intWS_t,intLlave_t,formWS){
        var formData = new FormData($("#FormularioDatos")[0]);
        formData.append('ws',intWS_t);
        formData.append('llave',intLlave_t);
        formData.append('formRequired',formWS);
        $.ajax({
            url:'formularios/integracionesCrm_CRUD.php?getDataWS',
            type:'POST',
            data:formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> PROCESANDO PETICION'
                });
            },
            success:function(data){
                console.log("CONSUMIRWS",data);
                if(data){
                    if(data.dataWS["Error"] != null  || data.dataWS["Error"] != 'null'){
                        if(data.dataWS["file"]!=''){
                            window.open('<?=base_url_image?>downloadFileWS/'+data.dataWS["file"], "_blank");
                        }else{
                            if(data.dataRelaciones.length > 0){
                                $.each(data.dataRelaciones, function(item, value){
                                    $.each(data.dataWS["solicitud"], function(i, campo){
                                        enlazaCampos(i,value.parametro,value.lista,value.campoG,campo,intLlave_t)
                                        // if(i == value.parametro){
                                        //     if(value.lista != null){
                                        //         traducirLista(intLlave_t,value.campoG,value.lista,campo);
                                        //     }else{
                                        //         $("#"+value.campoG).val(campo);
                                        //     }
                                        // }
                                    });        
                                });
                                //alertify.success("Proceso ejecutado con exito");
                            }else{
                                console.log("No se han configurado las relaciones entre campos del formulario y el Web Service");
                            }
                        }
                    }else{
                        console.log("Ocurrio un error al procesar la solicitud: "+data.dataWS["Error"]);
                    }
                }
            },
            complete:function(){
                $.unblockUI();
            },
            error:function(){
                alertify.error("Ocurrio un error al procesar la solicitud");
                $.unblockUI();
            }
        });
    }

    function traducirLista(llave,campo,lista,pregun){
        $.ajax({
            url:'formularios/integracionesCrm_CRUD.php?getOpcionWS',
            type:'POST',
            dataType: 'json',
            data:{llave:llave, lista:lista, opcion:pregun},
            beforeSend:function(){
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> PROCESANDO PETICION'
                });
            },
            success:function(data){
                if(data.mensaje=='ok'){
                    if(data.tipo=='lista'){
                        $("#"+campo).val(data.opcion).trigger('change');
                    }else{
                        $("#"+campo).val();
                        $("#"+campo).attr('opt',data.opcion).change();
                    }
                }else{
                    alertify.error(data.mensaje);
                }
            },
            complete:function(){
                $.unblockUI();
            },
            error:function(){
                alertify.error("Ocurrio un error al procesar la solicitud");
                $.unblockUI();
            }
        });    
    }

    function enlazaCampos(parametroWS,parametroG,lista,campoG,datoWS,llave){
        console.log(typeof(datoWS),parametroWS,datoWS);
        let tipo=typeof(datoWS);

        switch(tipo){
            case 'string':
                if(parametroWS==parametroG){
                    if(lista != null){
                        traducirLista(llave,campoG,lista,datoWS);
                    }else{
                        $("#"+campoG).val(datoWS);
                    }
                }
                break;
            case 'object':
                for (const property in datoWS) {
                    enlazaCampos(property,parametroG,lista,campoG,datoWS[property],llave);
                }
                break;
            case 'number':
                if(parametroWS==parametroG){
                    if(lista != null){
                        traducirLista(llave,campoG,lista,datoWS);
                    }else{
                        $("#"+campoG).val(datoWS);
                    }
                }
                break;
        }
    }
</script>