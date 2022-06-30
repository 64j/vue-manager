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
        $params['service'] .= 'ModuleService';
        $params['model'] .= 'Module';

        $this->service = new $params['service']();
        $this->model = new $params['model']();
    }
}
