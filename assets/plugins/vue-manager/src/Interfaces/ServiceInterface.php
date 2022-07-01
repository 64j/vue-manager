<?php

namespace VueManager\Interfaces;

use VueManager\Models\AbstractModel;

interface ServiceInterface
{
    /**
     * @param \VueManager\Models\AbstractModel $model
     * @return \VueManager\Models\AbstractModel
     */
    public function create(AbstractModel $model): AbstractModel;

    /**
     * @param \VueManager\Models\AbstractModel $model
     * @return \VueManager\Models\AbstractModel
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function read(AbstractModel $model): AbstractModel;

    /**
     * @param \VueManager\Models\AbstractModel $model
     * @return \VueManager\Models\AbstractModel
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function update(AbstractModel $model): AbstractModel;

    /**
     * @param \VueManager\Models\AbstractModel $model
     * @return \VueManager\Models\AbstractModel
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function delete(AbstractModel $model): AbstractModel;

    /**
     * @param array $params
     * @return AbstractModel[]|array
     */
    public function list(array $params = []): array;
}
