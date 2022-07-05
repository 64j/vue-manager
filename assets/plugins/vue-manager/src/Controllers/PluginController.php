<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class PluginController
{
    use CrudControllerTrait;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $service = sprintf($params['namespace'], 'Services') . 'PluginService';
        $model = sprintf($params['namespace'], 'Models') . 'SitePlugins';

        $this->service = new $service();
        $this->model = new $model();
    }
}
