<?php

declare(strict_types=1);

namespace VueManager;

use VueManager\Auth\Auth;
use VueManager\Exception\Handler;
use VueManager\Exception\NotFoundException;

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
        Auth::class => [
            'actionLogin'
        ]
    ];

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $evo = evolutionCMS();
        (new Handler)->registerErrorHandling();

        $this->setCors();
        $this->parseBody();

        if (!(isset($this->exceptMethods[$this->method[0]]) && in_array($this->method[1], $this->exceptMethods[$this->method[0]]))) {
            $this->user = (new Auth())->getUserByToken();
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
     * @return void
     */
    protected function setCors(): void
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');
        }

        /*if(isset($_COOKIE["PHPSESSID"])){
          header('Set-Cookie: PHPSESSID='.$_COOKIE["PHPSESSID"].'; SameSite=None');
        }*/

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            }
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
            exit(0);
        }
    }

    /**
     * @return void
     * @throws \VueManager\Exception\NotFoundException
     */
    protected function parseBody(): void
    {
        $body = $_POST + (json_decode(file_get_contents('php://input'), true) ?? []);

        if (!isset($body['method'])) {
            throw new NotFoundException('Method not found');
        }

        $this->method = explode('@', 'VueManager\\' . $body['method'] ?? '');
        $this->method[1] = isset($this->method[1]) ? 'action' . ucfirst($this->method[1]) : '';

        $this->params = $body['params'] ?? [];
    }

    /**
     * @return string
     * @throws \VueManager\Exception\NotFoundException
     */
    public function run(): string
    {
        if ($this->checkMethod()) {
            return $this->responseFromMethod();
        }

        throw new NotFoundException('Method not found');
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
        $this->method[0] = get_class($this) === $this->method[0] ? $this : new $this->method[0]();

        return $this->response(
            call_user_func([
                $this->method[0],
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
        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        return json_encode($response, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @return array
     */
    public function actionSettings(): array
    {
        global $modx_lang_attribute;

        $modx = evolutionCMS();

        $data = [];

        $data['config'] = $modx->config;
        $data['config']['lang_attribute'] = $modx_lang_attribute;

        $removeKeys = ['base_path', 'view', 'sys_files_checksum', 'check_files_onlogin', 'filemanager_path', 'rb_base_dir', 'site_manager_path'];
        foreach ($removeKeys as $k) {
            if (isset($data['config'][$k])) {
                unset($data['config'][$k]);
            }
        }

        $data['permissions'] = $modx->db->getRow($modx->db->select('*', $modx->getFullTableName('user_roles'), "id='{$this->user['role']}'"));

        $data['user'] = $this->user;

        $rs = $modx->db->makeArray($modx->db->select('*', '[+prefix+]categories'));
        $categories = [
            0 => [
                'id' => 0,
                'category' => $this->lang['no_category'],
                'rank' => 0
            ]
        ];

        foreach ($rs as $r) {
            $categories[$r['id']] = $r;
        }

        $data['categories'] = $categories;

        return [
            'data' => $data
        ];
    }

    /**
     * @param array $params
     * @return array[]
     */
    public function actionTree(array $params = []): array
    {
        return [
            'data' => []
        ];
    }
}
