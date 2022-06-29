<?php

declare(strict_types=1);

namespace VueManager;

use VueManager\Auth\Auth;
use VueManager\Exception\Handler;

class Application
{
    /**
     * @var array
     */
    protected array $method;

    /**
     * @var array
     */
    protected array $params;

    /**
     * @var array
     */
    protected array $response = [];

    /**
     * @var array
     */
    protected array $user = [];

    /**
     * @var int
     */
    protected int $responseCode = 200;

    /**
     * @var array
     */
    protected array $exceptMethods = [
        Auth::class => [
            'actionLogin'
        ]
    ];

    /**
     *
     */
    public function __construct()
    {
        $this->parseBody();

        (new Handler)->registerErrorHandling();
    }

    /**
     * @return void
     */
    protected function parseBody(): void
    {
        $body = $_POST + (json_decode(file_get_contents('php://input'), true) ?? []);

        $this->method = explode('@', 'VueManager\\' . $body['method'] ?? '');
        $this->method[1] = isset($this->method[1]) ? 'action' . ucfirst($this->method[1]) : '';

        $this->params = $body['params'] ?? [];
    }

    /**
     * @return string
     * @throws \ErrorException
     * @throws \VueManager\Exception\UnauthorizedException
     */
    public function run(): string
    {
        if (!(isset($this->exceptMethods[$this->method[0]]) && in_array($this->method[1], $this->exceptMethods[$this->method[0]]))) {
            $this->user = (new Auth())->getUserByToken();
        }

        if ($this->checkMethod()) {
            return $this->responseFromMethod();
        }

        throw new \ErrorException('Method not found');
    }

    /**
     * @return bool
     */
    protected function checkMethod(): bool
    {
        return method_exists(...$this->method) && is_callable($this->method);
    }

    /**
     * @return string
     */
    protected function responseFromMethod(): string
    {
        $this->response = call_user_func([
            new $this->method[0](),
            $this->method[1]
        ], $this->params);

        return $this->response();
    }

    /**
     * @return string
     */
    protected function response(): string
    {
        header('Content-Type: application/json; charset=utf-8');

        http_response_code($this->responseCode);

        return json_encode($this->response, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
    }
}
