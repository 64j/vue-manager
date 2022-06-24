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
    case 'OnBeforeManagerLogin':
        break;

    case 'OnManagerLogin':
        die('token:' . base64_encode($_SESSION['mgrToken']));
        break;

    case 'OnManagerAuthentication':
        // check user password - local authentication
        $hashType = evolutionCMS()->manager->getHashType($savedpassword);
        if ($hashType == 'phpass') {
            $matchPassword = evolutionCMS()->phpass->CheckPassword($_POST['password'], $savedpassword);
        } elseif ($hashType == 'md5') {
            $matchPassword = loginMD5($userid, $_POST['password'], $savedpassword, $username);
        } elseif ($hashType == 'v1') {
            $matchPassword = loginV1($userid, $_POST['password'], $savedpassword, $username);
        } else {
            $matchPassword = false;
        }

        if ($matchPassword) {
            $mgrToken = evolutionCMS()->db->getValue(evolutionCMS()->db->select('sessionid', evolutionCMS()->getFullTableName('user_attributes'), 'internalKey="' . (int) $userid . '"'));

            if ($mgrToken) {
                print json_encode([
                    'token' => base64_encode($mgrToken)
                ]);
            } else {
                header('HTTP/1.0 403 Forbidden');
                print $forbidden;
            }
        } else {
            header('HTTP/1.0 403 Forbidden');
            print $forbidden;
        }

        break;

    case 'OnPageNotFound':

        $q = $_REQUEST['q'];

        if (stripos($q, 'vue-manager/api') !== false) {
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

                if ($user['id']) {

                } else {
                    header('HTTP/1.0 403 Forbidden');

                    print json_encode([
                        'errors' => [
                            [
                                'code' => 403,
                                'message' => 'Forbidden'
                            ]
                        ]
                    ], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
                }
            } elseif (isset($_POST['username']) && isset($_POST['password'])) {
                require_once MODX_MANAGER_PATH . 'processors/login.processor.php';
            }

            exit;
        }

        break;
}
