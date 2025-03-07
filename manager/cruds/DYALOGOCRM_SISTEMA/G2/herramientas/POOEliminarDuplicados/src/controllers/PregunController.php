<?php

namespace Dyalogo\Script\controllers;

use Dyalogo\Script\controllers\Controllers;

class PregunController extends Controllers

{
    public function __construct($models)
    {
        parent::__construct($models);
    }

    public function obtenerPregun(int $id): array
    {

        $model = $this->models->obtenerPregun();

        return $model->getPregun($id);
    }
}
