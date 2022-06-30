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
        $params['service'] .= 'TvService';
        $params['model'] .= 'Tv';

        $this->service = new $params['service']();
        $this->model = new $params['model']();
    }
}
