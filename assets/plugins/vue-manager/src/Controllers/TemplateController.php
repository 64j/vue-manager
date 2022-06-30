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
        $params['service'] .= 'TemplateService';
        $params['model'] .= 'Template';

        $this->service = new $params['service']();
        $this->model = new $params['model']();
    }
}
