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
        $model = $this->service->create($this->model->hydrate($params));

        return $this->ok($model, $model->__getMeta());
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionRead(array $params = []): array
    {
        $model = $this->service->read($this->model->hydrate($params));

        return $this->ok($model, $model->__getMeta());
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionUpdate(array $params = []): array
    {
        $model = $this->service->update($this->model->hydrate($params));

        return $this->ok($model, $model->__getMeta());
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionDelete(array $params = []): array
    {
        $model = $this->service->delete($this->model->hydrate($params));

        return $this->ok($model, $model->__getMeta());
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function actionCopy(array $params = []): array
    {
        $model = $this->service->copy($this->model->hydrate($params));

        return $this->ok($model, $model->__getMeta());
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
