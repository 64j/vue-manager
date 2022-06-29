<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Services\PluginService;
use VueManager\Traits\ResponseTrait;

class PluginController
{
    use ResponseTrait;

    /**
     * @var \VueManager\Services\PluginService
     */
    protected PluginService $service;

    public function __construct()
    {
        $this->service = new PluginService();
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionList(array $params = []): array
    {
        return $this->ok($this->service->list($params));
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionRead(array $params = []): array
    {
        return $this->ok($this->service->read($params));
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionUpdate(array $params = []): array
    {
        return $this->ok($this->service->update($params));
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionCreate(array $params = []): array
    {
        return $this->ok($this->service->create($params));
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionDelete(array $params = []): array
    {
        return $this->ok($this->service->delete($params));
    }
}
