<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Traits\ResponseTrait;

class AuthController
{
    use ResponseTrait;

    /**
     * @var mixed
     */
    protected $service;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $service = sprintf($params['namespace'], 'Services') . 'AuthService';

        $this->service = new $service();
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionLogin(array $params): array
    {
        return $this->ok($this->service->login($params));
    }

    /**
     * @return array
     */
    public function getUserByToken(): array
    {
        return $this->ok($this->service->getUserByToken());
    }
}
