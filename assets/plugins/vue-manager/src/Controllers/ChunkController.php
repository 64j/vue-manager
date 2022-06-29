<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\ResponseTrait;

class ChunkController
{
    use ResponseTrait;

    /**
     * @param array $params
     * @return array
     */
    public function actionList(array $params = []): array
    {
        return $this->ok();
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionRead(array $params = []): array
    {
        return $this->ok();
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionUpdate(array $params = []): array
    {
        return $this->ok();
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionCreate(array $params = []): array
    {
        return $this->ok();
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionDelete(array $params = []): array
    {
        return $this->ok();
    }
}
