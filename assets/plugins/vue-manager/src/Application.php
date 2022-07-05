<?php

declare(strict_types=1);

namespace VueManager;

use ErrorException;
use VueManager\Controllers\AuthController;
use VueManager\Exceptions\Handler;
use VueManager\Exceptions\NotFoundException;

class Application
{
    /**
     * @var null|Application
     */
    private static ?Application $instance = null;

    /**
     * @var array
     */
    protected array $method;

    /**
     * @var array
     */
    protected array $params;

    /**
     * @var array|null
     */
    protected ?array $user = null;

    /**
     * @var array
     */
    protected array $lang = [];

    /**
     * @var array
     */
    protected array $exceptMethods = [
        AuthController::class => [
            'actionLogin'
        ]
    ];

    /**
     * @throws \ErrorException
     * @throws \VueManager\Exceptions\UnauthorizedException
     */
    public function __construct()
    {
        $evo = evolutionCMS();
        $evo->loadExtension('ManagerAPI');
        (new Handler)->registerErrorHandling();

        $this->setCors();
        $this->parseBody();

        if (!(isset($this->exceptMethods[$this->method[0]]) && in_array($this->method[1], $this->exceptMethods[$this->method[0]]))) {
            $this->user = (new AuthController())->getUserByToken();
        }

        $_lang = [];
        $manager_language = $evo->getConfig('manager_language');
        if (file_exists($file = MODX_MANAGER_PATH . 'includes/lang/' . $manager_language . '.inc.php')) {
            include_once $file;
        } else {
            include_once MODX_MANAGER_PATH . 'includes/lang/english.inc.php';
        }

        $this->lang = $_lang;
    }

    /**
     * @return \VueManager\Application
     */
    public static function getInstance(): Application
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return void
     * @throws \ErrorException
     */
    protected function setCors(): void
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');
        }

//        if (isset($_COOKIE['PHPSESSID'])) {
//            header('Set-Cookie: PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; SameSite=None');
//        }

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: POST");
            }
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
            exit(0);
        }

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new ErrorException('Method Not Allowed', 405);
        }
    }

    /**
     * @return void
     */
    protected function parseBody(): void
    {
        $body = $_POST + (json_decode(file_get_contents('php://input'), true) ?? []);

        $this->method = explode('@', 'VueManager\\Controllers\\' . ($body['method'] ?? ''));
        $this->method[0] = !empty($this->method[0]) ? $this->method[0] . 'Controller' : '';
        $this->method[1] = !empty($this->method[1]) ? 'action' . ucfirst($this->method[1]) : '';

        $this->params = $body['params'] ?? [];
    }

    /**
     * @return string
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function run(): string
    {
        if ($this->checkMethod()) {
            return $this->responseFromMethod();
        }

        throw new NotFoundException('Method Not Found');
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
        $evo = evolutionCMS();
        $version = max(1, substr($evo->getConfig('settings_version'), 0, 1));

        $params = [
            'namespace' => 'VueManager\%s\v' . $version . '\\',
            'service' => 'VueManager\Services\v' . $version . '\\',
            'model' => 'VueManager\Models\v' . $version . '\\',
        ];

        return $this->response(
            call_user_func([
                new $this->method[0]($params),
                $this->method[1]
            ], $this->params)
        );
    }

    /**
     * @param array $response
     * @return string
     */
    protected function response(array $response): string
    {
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=utf-8');

        return json_encode($response, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getUser(string $key = null)
    {
        return $this->user[$key] ?? $this->user;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getLang(string $key = null)
    {
        return $this->lang[$key] ?? $this->lang;
    }
}
