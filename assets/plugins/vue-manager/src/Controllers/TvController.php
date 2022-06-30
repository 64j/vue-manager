<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Services\TvService;
use VueManager\Traits\CrudControllerTrait;

class TvController
{
    use CrudControllerTrait;

    public function __construct()
    {
        $this->service = new TvService();
    }
}
