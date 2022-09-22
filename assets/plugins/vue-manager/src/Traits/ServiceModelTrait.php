<?php

namespace VueManager\Traits;

use VueManager\Interfaces\ModelInterface;

trait ServiceModelTrait
{
    /**
     * @var ModelInterface|null
     */
    protected ?ModelInterface $model = null;

    /**
     * @return ModelInterface
     */
    public function getModel(): ModelInterface
    {
        return $this->model;
    }
}
