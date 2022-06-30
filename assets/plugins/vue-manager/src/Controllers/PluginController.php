<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class PluginController
{
    use CrudControllerTrait;

    public function __construct(array $params = [])
    {
        $params['service'] .= 'PluginService';
        $params['model'] .= 'Plugin';

        $this->service = new $params['service']();
        $this->model = new $params['model']();
    }
}
