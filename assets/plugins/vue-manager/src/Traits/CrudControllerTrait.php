<?php

namespace VueManager\Traits;

use VueManager\Models\AbstractModel;

trait CrudControllerTrait
{
    use ResponseTrait;

    protected $service;

    /**
     * @var \VueManager\Models\AbstractModel
     */
    protected AbstractModel $model;

    /**
     * @param array $params
     * @return array
     */
    public function actionCreate(array $params = []): array
    {
        return $this->ok($this->service->create($this->model->hydrate($params)
            ->toArray()));
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionRead(array $params = []): array
    {
        return $this->ok($this->service->read($this->model->hydrate($params)
            ->toArray()));
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionUpdate(array $params = []): array
    {
        return $this->ok($this->service->update($this->model->hydrate($params)
            ->toArray()));
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionDelete(array $params = []): array
    {
        return $this->ok($this->service->delete($this->model->hydrate($params)
            ->toArray()));
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionList(array $params = []): array
    {
        return $this->ok(...$this->service->list($params));
    }
}
