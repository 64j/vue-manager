<?php

declare(strict_types=1);

namespace VueManager;

class Application
{
    /**
     * @var string
     */
    protected string $token;

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

    public function __construct()
    {
        $this->token = isset($_SERVER['HTTP_X_ACCESS_TOKEN']) ? base64_decode($_SERVER['HTTP_X_ACCESS_TOKEN']) : '';
        $body = $_POST + (json_decode(file_get_contents('php://input'), true) ?? []);
        $this->method = explode('@', '\\VueManager\\' . $body['method'] ?? '');
        $this->params = $body['params'] ?? [];
    }

    /**
     * @return void
     */
    public function run()
    {
        if (isset($this->params['login'], $this->params['password'])) {
            $this->response = (new Auth\Login())->auth($this->params);
        } elseif ($this->checkUserByToken() && count($this->method) == 2 && method_exists(...$this->method) && is_callable($this->method)) {
            $this->response = call_user_func(array(
                $this->method[0],
                $this->method[1]
            ), $this->params);
        } else {
            $this->unauthorizedPage();
        }

        print $this->response();
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

    /**
     * @return array
     */
    protected function checkUserByToken(): array
    {
        $evo = evolutionCMS();

        $this->user = [];

        if ($this->token) {
            $this->user = $evo->db->getRow($evo->db->query('
                SELECT
                    ua.internalKey as id,
                    ua.fullname,
                    mu.username,
                    ua.role
                FROM ' . $evo->getFullTableName('user_attributes') . ' ua
                LEFT JOIN ' . $evo->getFullTableName('manager_users') . ' mu ON mu.id=ua.internalKey
                WHERE
                    md5(ua.sessionid)="' . $evo->db->escape($this->token) . '"
                    AND ua.blocked=0
                    AND ua.verified=1
            '));

            if (!isset($this->user['id'])) {
                $this->unauthorizedPage('Wrong login or password');
            }
        } else {
            $this->unauthorizedPage('Error authorization');
        }

        return $this->user;
    }

    /**
     * @param string|null $message
     * @return void
     */
    protected function errorPage(string $message = null): void
    {
        $this->responseCode = 404;
        $this->setResponseMessage($message);
    }

    /**
     * @param string|null $message
     * @return void
     */
    protected function unauthorizedPage(string $message = null): void
    {
        $this->responseCode = 401;
        $this->setResponseMessage($message);
    }

    /**
     * @param string|null $message
     * @return void
     */
    protected function setResponseMessage(string $message = null)
    {
        if ($message) {
            $this->response['errors'][] = [
                'code' => $this->responseCode,
                'message' => $message
            ];
        }
    }
}
