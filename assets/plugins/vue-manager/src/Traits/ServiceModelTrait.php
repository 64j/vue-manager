<?php

namespace VueManager\Traits;

use VueManager\Interfaces\ModelInterface;

trait ServiceModelTrait
{
    /**
     * @var \VueManager\Interfaces\ModelInterface|null
     */
    protected ?ModelInterface $model = null;

    /**
     * @return \VueManager\Interfaces\ModelInterface
     */
    public function getModel(): ModelInterface
    {
        return $this->model;
    }
}
