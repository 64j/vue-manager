<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\ResponseTrait;

class SettingsController
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
        $service = sprintf($config['namespace'], 'Services') . 'SettingsService';

        $this->service = new $service();
    }

    /**
     * @param array $params
     * @return array[]
     * @throws \Exception
     */
    public function actionGet(array $params = []): array
    {
        return $this->ok(
            $this->service->get($params)
        );
    }
}
