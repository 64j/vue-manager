<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class ModuleController
{
    use CrudControllerTrait;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $service = sprintf($params['namespace'], 'Services') . 'ModuleService';
        $model = sprintf($params['namespace'], 'Models') . 'SiteModules';

        $this->service = new $service();
        $this->model = new $model();
    }
}
