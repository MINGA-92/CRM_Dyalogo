$(function(){

    //function para NOMBRE 

    $("#G23_C214").on('blur',function(e){});

    //function para HUESPED 

    $("#G23_C215").on('blur',function(e){});

    //function para NUMEROS 

    $("#G23_C245").change(function(){
        
    });

    //function para PASO 

    $("#G23_C246").on('blur',function(e){});

    //function para LIMITE DE LLAMADAS 

    $("#G23_C216").on('blur',function(e){});

    //function para VALIDAR LISTA NEGRA 

    $("#G23_C217").change(function(){
        if($(this).is(":checked")){

        }else{

        }
    }); 
});

function before_save(){ return true;}

function after_save(){}

function after_save_error(){}

function before_edit(){}

function after_edit(){}

function before_add(){}

function after_add(){}

function before_delete(){}

function after_delete(){}

function after_delete_error(){}