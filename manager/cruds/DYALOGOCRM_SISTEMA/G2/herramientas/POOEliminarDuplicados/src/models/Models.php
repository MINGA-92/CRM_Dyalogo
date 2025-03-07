<?php

namespace Dyalogo\Script\models;

use Dyalogo\Script\utils\Mysql;

use Dyalogo\Script\models\Estrategia;
use Dyalogo\Script\models\Campanas;
use Dyalogo\Script\models\BackOffice;
use Dyalogo\Script\models\Bot;
use Dyalogo\Script\models\EmailSalientes;
use Dyalogo\Script\models\SMSsalientes;
use Dyalogo\Script\models\PregunModel;


class Models
{

    public function __construct()
    {
        $this->mysqli = Mysql::mysqli();
    }

    public function obtenerRepetidos(string $estrategia, string $llave): array
    {
        # Obtenemos todos los registros duplicados


        $estrategia = new Estrategia($estrategia, $llave);

        $estrategia->getIdEstrategia();
        $estrategia->getIdBD();
        $estrategia->getRepetidos();
        $estrategia->buscarRepetidosId();

        // echo json_encode($estrategia->getRepetidosId());

        return $estrategia->getRepetidosId();
    }

    public function depurarDuplicados(string $strEstrategia, string $llave): void
    {
        # Depurar los registros repetidos

        $estrategia = new Estrategia($strEstrategia, $llave);

        $estrategia->getIdEstrategia();
        $estrategia->getIdBD();
        $estrategia->getRepetidos();
        $estrategia->buscarRepetidosId();

        $campana =  new Campanas($strEstrategia, $llave);
        $campana->getCampanas();
        // $campana->getInfoByIdCampana();


        $backOffice = new BackOffice($strEstrategia, $llave);
        $backOffice->getBackOffice();


        $emailSalientes  = new EmailSalientes($strEstrategia, $llave);
        $emailSalientes->getEmailSalientes();


        $smsSalientes  = new SMSsalientes($strEstrategia, $llave);
        $smsSalientes->getSMS();

        $bot = new Bot($strEstrategia, $llave);
        $bot->getBot();

        // $estrategia->buscarRepetidos($campana, $backOffice, $emailSalientes, $smsSalientes, $bot);
    }


    /**
     * retono la clase pregun model
     * @return PregunModel
     */
    public function obtenerPregun(): PregunModel
    {

        // echo "pregun";

        return new PregunModel();
    }
}
