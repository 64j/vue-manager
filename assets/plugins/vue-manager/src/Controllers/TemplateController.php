<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Models\Template;
use VueManager\Services\TemplateService;
use VueManager\Traits\CrudControllerTrait;

class TemplateController
{
    use CrudControllerTrait;

    public function __construct()
    {
        $this->service = new TemplateService();
        $this->model = new Template();
    }
}
