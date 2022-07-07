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
     */
    public function read(AbstractModel $model): AbstractModel;

    /**
     * @param \VueManager\Models\AbstractModel $model
     * @return \VueManager\Models\AbstractModel
     */
    public function update(AbstractModel $model): AbstractModel;

    /**
     * @param \VueManager\Models\AbstractModel $model
     * @return \VueManager\Models\AbstractModel
     */
    public function delete(AbstractModel $model): AbstractModel;

    /**
     * @param \VueManager\Models\AbstractModel $model
     * @return \VueManager\Models\AbstractModel
     */
    public function copy(AbstractModel $model): AbstractModel;

    /**
     * @param array $params
     * @return AbstractModel[]|array
     */
    public function list(array $params = []): array;
}
