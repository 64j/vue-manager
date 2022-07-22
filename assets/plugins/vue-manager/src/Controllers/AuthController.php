<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Exceptions\UnauthorizedException;

class AuthController
{
    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\UnauthorizedException
     */
    public function actionLogin(array $params): array
    {
        $app = evolutionCMS();
        $app->loadExtension('phpass');

        $username = (string) $app->db->escape($app->htmlspecialchars($params['username'] ?? '', ENT_NOQUOTES));
        $password = (string) $app->htmlspecialchars($params['password'] ?? '', ENT_NOQUOTES);

        $rs = $app->db->select(
            'mu.*, ua.*',
            '[+prefix+]manager_users AS mu, [+prefix+]user_attributes AS ua',
            'BINARY mu.username="' . $username . '" and ua.internalKey=mu.id'
        );

        $limit = $app->db->getRecordCount($rs);

        if ($limit == 0 || $limit > 1) {
            throw new UnauthorizedException();
        }

        $user = $app->db->getRow($rs);

        $internalKey = (int) $user['internalKey'];
        $dbasePassword = $user['password'];
        $matchPassword = false;

        if (!isset($rt) || !$rt || (is_array($rt) && !in_array(true, $rt))) {
            // check user password - local authentication
            $hashType = $app->manager->getHashType($dbasePassword);
            if ($hashType == 'phpass') {
                $matchPassword = $this->login($password, $dbasePassword);
            } elseif ($hashType == 'md5') {
                $matchPassword = $this->loginMD5($password, $dbasePassword, $username);
            } elseif ($hashType == 'v1') {
                $matchPassword = $this->loginV1($internalKey, $password, $dbasePassword, $username);
            }
        } else {
            if ($rt === true || (is_array($rt) && in_array(true, $rt))) {
                $matchPassword = true;
            }
        }

        if (!$matchPassword) {
            throw new UnauthorizedException('Incorrect username or password entered!');
        }

        session_destroy();
        session_id($user['sessionid']);
        session_start();

        $app->db->update([
            'failedlogincount' => 0,
            'logincount' => $user['logincount'] + 1,
            'lastlogin' => $user['thislogin'],
            'thislogin' => time(),
        ],
            $app->getFullTableName('user_attributes'),
            'internalKey=' . $internalKey
        );

        $_SESSION['usertype'] = 'manager';
        $_SESSION['mgrValidated'] = 1;
        $_SESSION['mgrInternalKey'] = $internalKey;
        $_SESSION['mgrRole'] = $user['role'];
        $_SESSION['mgrPermissions'] = $app->db->getRow(
            $app->db->select('*', $app->getFullTableName('user_roles'), 'id=' . $internalKey)
        );
        ksort($_SESSION['mgrPermissions']);

        $app->getUserSettings();

        return [
            'token' => base64_encode(md5($user['sessionid']))
        ];
    }

    /**
     * @return array
     * @throws \VueManager\Exceptions\UnauthorizedException
     */
    public function getUserByToken(): array
    {
        $app = evolutionCMS();
        $token = isset($_SERVER['HTTP_X_ACCESS_TOKEN']) ? base64_decode($_SERVER['HTTP_X_ACCESS_TOKEN']) : '';

        if ($token) {
            $user = $app->db->getRow($app->db->query('
                SELECT
                    ua.internalKey as id,
                    ua.fullname,
                    mu.username,
                    ua.role,
                    ua.sessionid
                FROM ' . $app->getFullTableName('user_attributes') . ' ua
                LEFT JOIN ' . $app->getFullTableName('manager_users') . ' mu ON mu.id=ua.internalKey
                WHERE
                    md5(ua.sessionid)="' . $app->db->escape($token) . '"
                    AND ua.blocked=0
                    AND ua.verified=1
            '));

            if (!empty($user['id'])) {
                session_destroy();
                session_id($user['sessionid']);
                session_start();

                $_SESSION['usertype'] = 'manager';
                $_SESSION['mgrValidated'] = 1;
                $_SESSION['mgrInternalKey'] = $user['id'];
                $_SESSION['mgrRole'] = $user['role'];
                $_SESSION['mgrPermissions'] = $app->db->getRow(
                    $app->db->select('*', $app->getFullTableName('user_roles'), 'id=' . $user['id'])
                );
                ksort($_SESSION['mgrPermissions']);

                unset($user['sessionid']);

                $app->getUserSettings();

                return $user;
            }
        }

        throw new UnauthorizedException('Error authorization token');
    }

    /**
     * @param string $givenPassword
     * @param string $dbasePassword
     * @return bool
     */
    protected function login(string $givenPassword, string $dbasePassword): bool
    {
        return evolutionCMS()->phpass->CheckPassword($givenPassword, $dbasePassword);
    }

    /**
     * @param string $givenPassword
     * @param string $dbasePassword
     * @param string $username
     * @return bool
     */
    protected function loginMD5(string $givenPassword, string $dbasePassword, string $username): bool
    {
        if ($dbasePassword != md5($givenPassword)) {
            return false;
        }
        $this->updateNewHash($username, $givenPassword);

        return true;
    }

    /**
     * @param string $username
     * @param string $password
     * @return void
     */
    protected function updateNewHash(string $username, string $password)
    {
        $modx = evolutionCMS();

        $field = [];
        $field['password'] = $modx->phpass->HashPassword($password);
        $modx->db->update($field, '[+prefix+]manager_users', "username='$username'");
    }

    /**
     * @param int $internalKey
     * @param string $givenPassword
     * @param string $dbasePassword
     * @param string $username
     * @return bool
     */
    protected function loginV1(int $internalKey, string $givenPassword, string $dbasePassword, string $username): bool
    {
        $modx = evolutionCMS();

        $user_algo = $modx->manager->getV1UserHashAlgorithm($internalKey);

        if (!isset($modx->config['pwd_hash_algo']) || empty($modx->config['pwd_hash_algo'])) {
            $modx->config['pwd_hash_algo'] = 'UNCRYPT';
        }

        if ($user_algo !== $modx->config['pwd_hash_algo']) {
            $modx->config['pwd_hash_algo'] = $user_algo;
        }

        if ($dbasePassword != $modx->manager->genV1Hash($givenPassword, $internalKey)) {
            return false;
        }

        $this->updateNewHash($username, $givenPassword);

        return true;
    }
}
