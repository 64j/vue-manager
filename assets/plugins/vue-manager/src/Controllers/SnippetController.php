<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class SnippetController
{
    use CrudControllerTrait;

    public function __construct(array $params = [])
    {
        $params['service'] .= 'SnippetService';
        $params['model'] .= 'Snippet';

        $this->service = new $params['service']();
        $this->model = new $params['model']();
    }
}
