<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Services\PluginService;
use VueManager\Traits\CrudControllerTrait;

class PluginController
{
    use CrudControllerTrait;

    public function __construct()
    {
        $this->service = new PluginService();
    }
}
