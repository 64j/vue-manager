<?php

declare(strict_types=1);

namespace VueManager\Auth;

class Login
{
    /**
     * @param array $params
     * @return array
     */
    public function auth(array $params): array
    {
        return [
            'token' => $params
        ];
    }
}
