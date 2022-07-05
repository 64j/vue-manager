<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class TemplateController
{
    use CrudControllerTrait;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $service = sprintf($params['namespace'], 'Services') . 'TemplateService';
        $model = sprintf($params['namespace'], 'Models') . 'SiteTemplates';

        $this->service = new $service();
        $this->model = new $model();
    }
}
