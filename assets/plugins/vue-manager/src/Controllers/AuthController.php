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
        $evo = evolutionCMS();
        $evo->loadExtension('ManagerAPI');
        $evo->loadExtension('phpass');

        $username = (string) $evo->db->escape($evo->htmlspecialchars($params['username'] ?? '', ENT_NOQUOTES));
        $password = (string) $evo->htmlspecialchars($params['password'] ?? '', ENT_NOQUOTES);

        $rs = $evo->db->select(
            'mu.*, ua.*',
            '[+prefix+]manager_users AS mu, [+prefix+]user_attributes AS ua',
            'BINARY mu.username="' . $username . '" and ua.internalKey=mu.id'
        );

        $limit = $evo->db->getRecordCount($rs);

        if ($limit == 0 || $limit > 1) {
            throw new UnauthorizedException();
        }

        $row = $evo->db->getRow($rs);

        $internalKey = (int) $row['internalKey'];
        $dbasePassword = $row['password'];
        $matchPassword = false;

        if (!isset($rt) || !$rt || (is_array($rt) && !in_array(true, $rt))) {
            // check user password - local authentication
            $hashType = $evo->manager->getHashType($dbasePassword);
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

        $sessionId = session_id();

        $evo->db->update([
            'failedlogincount' => 0,
            'logincount' => $row['logincount'] + 1,
            'lastlogin' => $row['thislogin'],
            'thislogin' => time(),
            'sessionid' => $sessionId
        ],
            $evo->getFullTableName('user_attributes'),
            'internalKey=' . $internalKey
        );

        return [
            'token' => base64_encode(md5($sessionId))
        ];
    }

    /**
     * @return array
     * @throws \VueManager\Exceptions\UnauthorizedException
     */
    public function getUserByToken(): array
    {
        $evo = evolutionCMS();
        $token = isset($_SERVER['HTTP_X_ACCESS_TOKEN']) ? base64_decode($_SERVER['HTTP_X_ACCESS_TOKEN']) : '';

        if ($token) {
            $user = $evo->db->getRow($evo->db->query('
                SELECT
                    ua.internalKey as id,
                    ua.fullname,
                    mu.username,
                    ua.role
                FROM ' . $evo->getFullTableName('user_attributes') . ' ua
                LEFT JOIN ' . $evo->getFullTableName('manager_users') . ' mu ON mu.id=ua.internalKey
                WHERE
                    md5(ua.sessionid)="' . $evo->db->escape($token) . '"
                    AND ua.blocked=0
                    AND ua.verified=1
            '));

            if (isset($user['id'])) {
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
