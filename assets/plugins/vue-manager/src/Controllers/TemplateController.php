<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class TemplateController
{
    use CrudControllerTrait;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $service = sprintf($config['namespace'], 'Services') . 'TemplateService';

        $this->service = new $service();
    }
}
