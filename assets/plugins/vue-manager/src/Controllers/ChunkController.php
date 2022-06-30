<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class ChunkController
{
    use CrudControllerTrait;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $params['service'] .= 'ChunkService';
        $params['model'] .= 'Chunk';

        $this->service = new $params['service']();
        $this->model = new $params['model']();
    }
}
