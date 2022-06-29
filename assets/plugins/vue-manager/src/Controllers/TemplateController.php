<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Services\TemplateService;
use VueManager\Traits\ResponseTrait;

class TemplateController
{
    use ResponseTrait;

    /**
     * @var \VueManager\Services\TemplateService
     */
    protected TemplateService $service;

    public function __construct()
    {
        $this->service = new TemplateService();
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
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionDelete(array $params = []): array
    {
        return $this->ok($this->service->delete($params));
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionList(array $params = []): array
    {
        return $this->ok($this->service->list($params));
    }
}
