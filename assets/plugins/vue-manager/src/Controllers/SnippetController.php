<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Services\SnippetService;
use VueManager\Traits\CrudControllerTrait;

class SnippetController
{
    use CrudControllerTrait;

    public function __construct()
    {
        $this->service = new SnippetService();
    }
}
