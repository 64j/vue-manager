<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\ResponseTrait;

class TreeController
{
    use ResponseTrait;

    /**
     * @var mixed
     */
    protected $service;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $service = sprintf($config['namespace'], 'Services') . 'TreeService';

        $this->service = new $service();
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionGet(array $params = []): array
    {
        return $this->ok($this->service->get($params), $this->service->getMeta());
    }
}
