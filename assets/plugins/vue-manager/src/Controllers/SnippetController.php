<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class SnippetController
{
    use CrudControllerTrait;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $service = sprintf($params['namespace'], 'Services') . 'SnippetService';
        $model = sprintf($params['namespace'], 'Models') . 'SiteSnippets';

        $this->service = new $service();
        $this->model = new $model();
    }
}
