<?php

namespace VueManager\Traits;

use VueManager\Interfaces\ServiceInterface;

trait CrudControllerTrait
{
    use ResponseTrait;

    /**
     * @var ServiceInterface
     */
    protected ServiceInterface $service;

    /**
     * @param array $params
     * @return array
     */
    public function actionCreate(array $params = []): array
    {
        return $this->ok(
            $this->service->create($params),
            $this->service->getMeta()
        );
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionRead(array $params = []): array
    {
        return $this->ok(
            $this->service->read($params),
            $this->service->getMeta()
        );
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionUpdate(array $params = []): array
    {
        return $this->ok(
            $this->service->update($params),
            $this->service->getMeta()
        );
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionDelete(array $params = []): array
    {
        return $this->ok(
            $this->service->delete($params),
            $this->service->getMeta()
        );
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionCopy(array $params = []): array
    {
        return $this->ok(
            $this->service->copy($params),
            $this->service->getMeta()
        );
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionList(array $params = []): array
    {
        return $this->ok(
            $this->service->list($params),
            $this->service->getMeta()
        );
    }
}
