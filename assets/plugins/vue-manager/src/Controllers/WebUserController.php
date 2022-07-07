<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class WebUserController
{
    use CrudControllerTrait;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $params['service'] .= 'WebUserService';
        $params['model'] .= 'WebUserAttributes';

        $this->service = new $params['service']();
        $this->model = new $params['model']();
    }
}
