<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Services\ChunkService;
use VueManager\Traits\CrudControllerTrait;

class ChunkController
{
    use CrudControllerTrait;

    public function __construct()
    {
        $this->service = new ChunkService();
    }
}
