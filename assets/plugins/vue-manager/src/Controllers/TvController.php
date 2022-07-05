<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class TvController
{
    use CrudControllerTrait;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $service = sprintf($params['namespace'], 'Services') . 'TvService';
        $model = sprintf($params['namespace'], 'Models') . 'SiteTmplvars';

        $this->service = new $service();
        $this->model = new $model();
    }
}
