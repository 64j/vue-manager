<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Services\ModuleService;
use VueManager\Traits\CrudControllerTrait;

class ModuleController
{
    use CrudControllerTrait;

    public function __construct()
    {
        $this->service = new ModuleService();
    }
}
