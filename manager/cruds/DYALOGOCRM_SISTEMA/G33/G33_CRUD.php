<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);

    require_once('Agendador.php');

    if(isset($_POST['llenarListaNavegacion'])){
        $data = new Agendador();
        echo $data->llenarListaNavegacion();
    }

    if(isset($_POST['guardar'])){
        $data = new Agendador();
        echo json_encode($data->saveAgendador($_POST));
    }

    if(isset($_POST['actualizar'])){
        $data = new Agendador();
        echo json_encode($data->saveAgendador($_POST));
    }

    if(isset($_POST['getCampos'])){
        $data = new Agendador;
        $id=isset($_POST['bd']) && is_numeric($_POST['bd'])? $data->getCamposBD($_POST['bd']) : array('estado'=>'error','mensaje'=>'Parametros incompletos');
        echo json_encode($id);
    }
    
    if(isset($_POST['getAgendador'])){
        $data = new Agendador;
        $data=isset($_POST['id']) && $_POST['id'] != '' ? $data->getAgendador($_POST['id']) : array('estado'=>'error','mensaje'=>'Parametros incompletos');
        echo json_encode($data);
    }
    
    if(isset($_POST['getCondiciones'])){
        $data = new Agendador;
        $data=isset($_POST['id']) && $_POST['id'] != '' ? $data->getCondiciones($_POST['id']) : array('estado'=>'error','mensaje'=>'Parametros incompletos');
        echo json_encode($data);
    }
    
    if(isset($_POST['getOpciones'])){
        $data = new Agendador;
        $id=isset($_POST['opcion']) && is_numeric($_POST['opcion'])? $data->getOpciones($_POST['opcion']) : array('estado'=>'error','mensaje'=>'Parametros incompletos');
        echo json_encode($id);
    }

    if(isset($_POST['getWebForm'])){
        $data = new Agendador;
        $data=isset($_POST['id']) && $_POST['id'] != '' ? $data->getWebForm($_POST['id']) : array('estado'=>'error','mensaje'=>'Parametros incompletos');
        echo json_encode($data);
    }

    if(isset($_POST['getEstpas'])){
        $data = new Agendador;
        $data=isset($_POST['idEstrat']) && $_POST['idEstrat'] != '' ? $data->getEstpas($_POST['idEstrat']) : array('estado'=>'error','mensaje'=>'Parametros incompletos');
        echo json_encode($data);
    }