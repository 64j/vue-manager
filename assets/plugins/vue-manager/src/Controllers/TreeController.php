<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\ResponseTrait;

class TreeController
{
    use ResponseTrait;

    /**
     * @param array $params
     * @return array
     */
    public function actionGet(array $params = []): array
    {
        return $this->ok();
    }
}
