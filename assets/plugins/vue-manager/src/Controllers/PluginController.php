<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\CrudControllerTrait;

class PluginController
{
    use CrudControllerTrait;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $service = sprintf($config['namespace'], 'Services') . 'PluginService';

        $this->service = new $service();
    }
}
