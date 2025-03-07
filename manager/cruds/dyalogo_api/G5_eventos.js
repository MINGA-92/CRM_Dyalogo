$(function(){

    //function para NOMBRE 

    $("#G5_C28").on('blur',function(e){});

    //function para TIPO 

    $("#G5_C29").change(function(){ 
        if($(this).val() == '2'){
            $("#busqueda_guion_oculto").show();
        }else{
            $("#busqueda_guion_oculto").hide();
        }
    });

    //function para OBSERVACION 

    $("#G5_C30").on('blur',function(e){});

    //function para CAMPO PRINCIPAL 

    $("#G5_C31").change(function(){
        $("#primario").val($(this).val());        
    });

    //function para CAMPO SECUNDARIO 

    $("#G5_C59").change(function(){
        $("#segundario").val($(this).val()); 
    }); 

    $("#G6_C40").change(function(){
        var valores = $(this).val();
        if(valores == '3' || valores == '4'){
            $("#numero_mini_oculto").show();
            $("#numero_maxi_oculto").show();
        }else{
            $("#numero_mini_oculto").hide();
            $("#numero_maxi_oculto").hide();
        }

        if(valores == '5'){
            $("#fecha_mini_oculto").show();
            $("#fecha_maxi_oculto").show();
        }else{
            $("#fecha_mini_oculto").hide();
            $("#fecha_maxi_oculto").hide();
        }

        if(valores == '10'){
            $("#hora_mini_oculto").show();
            $("#hora_maxi_oculto").show();
        }else{
            $("#hora_mini_oculto").hide();
            $("#hora_maxi_oculto").hide();
        }

        if(valores == '6'){
            $("#lista_oculto").show();
        }else{
            $("#lista_oculto").hide();
        }

        if(valores == '11' || valores == '12'){
            $("#detalle_guion_oculto").show();
        }else{
            $("#detalle_guion_oculto").hide();
        }

        if(valores == '11'){
            $("#detallesParaGuion1").show();
        }else{
            $("#detallesParaGuion1").hide();
        }
        if(valores == '12'){
            $(".Mdetalle_guion_oculto").show();
            $("#Guidet").attr('disabled',false);
            $("#GuidetM").attr('disabled',false);
        }else{
            $(".Mdetalle_guion_oculto").hide();
            $("#Guidet").attr('disabled',true);
            $("#GuidetM").attr('disabled',true);
        }

        /*echo '<option value="0">Seleccione</option>';
        echo '<option value="1">Texto</option>';
        echo '<option value="2">Memo</option>';
        echo '<option value="3">Numerico</option>';
        echo '<option value="4">Decimal</option>';
        echo '<option value="5">Fecha</option>';
        echo '<option value="10">Hora</option>';
        echo '<option value="6">Lista</option>';
        echo '<option value="11">Guión</option>';
        echo '<option value="8">Casilla de verificación</option>';
        echo '<option value="9">Libreto / Label</option>';   */
    })

    $("#G6_C43").change(function(){
        var valores = $(this).val();
        $.ajax({
            type    : 'post',
            url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php?camposGuion_incude_id=true',
            data    : { guion : valores },
            success : function(data){
                $("#Guidet").html(data);
                $("#camposGuion").html(data);
            }
        });
    });
});


function after_save(){}

function after_save_error(){}

function before_edit(){}

function after_edit(){}

function before_add(){}

function after_add(){}

function before_delete(){}

function after_delete(){}

function after_delete_error(){}