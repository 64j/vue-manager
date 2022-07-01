<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class UserController
{
    use CrudControllerTrait;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $params['service'] .= 'UserService';
        $params['model'] .= 'UserAttributes';

        $this->service = new $params['service']();
        $this->model = new $params['model']();
    }
}
