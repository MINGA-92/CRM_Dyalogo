<?php

require_once __DIR__.'/../models/VisorWSTemplate.php';

class VisorController{

    public function whatsappSaliente():void
    {
        echo "llego aqui";
        $this->viewWSTemplate((isset($_GET['idViewer'])) ? $_GET['idViewer'] : "0");
    }
    private function viewWSTemplate( string $idWsMessage ):void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO GET
        Helpers::method($_SERVER['REQUEST_METHOD'],'GET');

        if($idWsMessage != "0"){

                $visor = new VisorWSTemplate();
                $visor->setId($idWsMessage);

                echo $idWsMessage;

                // Se obtienen los parametros enviados
                $incomingParameters = $visor->getIncomingParameters();

                $visor->setpasoId($incomingParameters['id_estpas']);
                $Parameters = json_decode($incomingParameters['parametros_entrantes'])->parametros;


                // Se obtiene el texto de la plantilla
                
                $textTemplate = $visor->getTemplate($visor->getpasoId())['texto'];
                $message = $visor->buildMessage($Parameters , $textTemplate );
                $visor->buildPdf($message, $incomingParameters);

                exit();
            }

            // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
            showResponse("Parametros incompletos o invalidos");

        }


 }
 
 ?>


    
