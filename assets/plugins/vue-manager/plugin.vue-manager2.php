<?php

require_once '__autoload.php';

$e = evolutionCMS()->event;

$forbidden = json_encode([
    'errors' => [
        [
            'code' => 403,
            'message' => 'Forbidden'
        ]
    ]
], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);

switch ($e->name) {
    case 'OnPageNotFound':
        $q = $_REQUEST['q'];

        if (stripos($q, 'vue-manager/api') !== false) {

        }

        break;

    case 'OnBeforeManagerLogin':
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
        break;

    case 'OnManagerLogin':
        header('Content-Type: application/json; charset=utf-8');
        print json_encode([
            'token' => base64_encode($_SESSION['mgrToken'])
        ]);
        exit;
        break;

    case 'OnPageNotFound1':

        $q = $_REQUEST['q'];

        if (stripos($q, 'vue-manager/api') !== false) {
            $path = explode('/', explode('vue-manager/api/', $q)[1] ?? '') ?? [];
            $id = intval($path[1] ?? 0);
            $path = $path[0] ?? null;

            header('Content-Type: application/json; charset=utf-8');

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

            $user = [];
            $token = isset($_SERVER['HTTP_X_ACCESS_TOKEN']) ? base64_decode($_SERVER['HTTP_X_ACCESS_TOKEN']) : null;

            $_POST += json_decode(file_get_contents('php://input'), true) ?? [];

            if ($token) {
                $user = evolutionCMS()->db->getRow(evolutionCMS()->db->query('
                    SELECT
                        ua.internalKey as id,
                        ua.fullname,
                        mu.username,
                        ua.role
                    FROM ' . evolutionCMS()->getFullTableName('user_attributes') . ' ua
                    LEFT JOIN ' . evolutionCMS()->getFullTableName('manager_users') . ' mu ON mu.id=ua.internalKey
                    WHERE
                        md5(ua.sessionid)="' . evolutionCMS()->db->escape($token) . '"
                        AND ua.blocked=0
                        AND ua.verified=1
                '));

                $data = [];
                $meta = [];

                $app = new \VueManager\Application($_POST);

                if ($user['id']) {
                    switch ($path) {
                        case 'settings':
                            $data = $app->getSettings($user);
                            break;

                        case 'templates':
                            $data = $app->getTemplates();
                            break;

                        case 'template':
                            $data = $app->getTemplate($id);
                            break;

                        case 'tvs':
                            $data = $app->getTvs();
                            break;

                        case 'tv':
                            $data = $app->getTv($id);
                            break;

                        case 'chunks':
                            $data = $app->getChunks();
                            break;

                        case 'chunk':
                            $data = $app->getChunk($id);
                            break;

                        case 'snippets':
                            $data = $app->getSnippets();
                            break;

                        case 'snippet':
                            $data = $app->getSnippet($id);
                            break;

                        case 'plugins':
                            $data = $app->getPlugins();
                            break;

                        case 'plugin':
                            $data = $app->getPlugin($id);
                            break;

                        case 'modules':
                            $data = $app->getModules();
                            break;

                        case 'module':
                            $data = $app->getModule($id);
                            break;

                        case 'module-exec':
                            $data = $app->getModuleExec($id);
                            break;

                        case 'users':
                            [$data, $meta] = $app->getUsers();
                            break;

                        case 'user':
                            $data = $app->getUser($id);
                            break;

                        case 'web-users':
                            [$data, $meta] = $app->getWebUsers();
                            break;

                        case 'web-user':
                            $data = $app->getWebUser($id);
                            break;

                        case 'roles':
                            [$data, $meta] = $app->getRoles();
                            break;

                        case 'role':
                            [$data, $meta] = $app->getRole($id);
                            break;
                    }

                    print json_encode([
                        'data' => $data,
                        'meta' => $meta,
                    ], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
                } else {
                    header('HTTP/1.0 403 Forbidden');
                    print $forbidden;
                }
            } elseif (isset($_POST['username']) && isset($_POST['password'])) {
                require_once MODX_MANAGER_PATH . 'processors/login.processor.php';
            } else {
                header('HTTP/1.0 403 Forbidden');
                print $forbidden;
            }

            exit;
        }

        break;
}
