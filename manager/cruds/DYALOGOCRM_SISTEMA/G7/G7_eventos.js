$(function(){

    //function para NOMBRE 

    $("#G7_C33").on('blur',function(e){});

    //function para ORDEN 

    $("#G7_C34").on('blur',function(e){});

    //function para NÚMERO DE COLUMNAS 

    $("#G7_C35").on('blur',function(e){});

    //function para APARIENCIA 

    $("#G7_C36").change(function(){ 
        
         
    });

    //function para MINIMIADA POR DEFECTO 

    $("#G7_C37").change(function(){
        if($(this).is(":checked")){

        }else{

        }
    });

    //function para TIPO 

    $("#G7_C38").change(function(){ 
        
         
    });

    //function para GUION 

    $("#G7_C60").change(function(){
        
    }); 
});

function before_save(){ return true; }

function after_save(){}

function after_save_error(){}

function before_edit(){}

function after_edit(){}

function before_add(){}

function after_add(){}

function before_delete(){}

function after_delete(){}

function after_delete_error(){}