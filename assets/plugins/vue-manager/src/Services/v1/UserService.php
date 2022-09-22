<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\ManagerUsers;
use VueManager\Traits\ServiceMetaTrait;
use VueManager\Traits\ServicePermissionTrait;

class UserService implements ServiceInterface
{
    use ServicePermissionTrait;
    use ServiceMetaTrait;

    /**
     * @var array
     */
    protected array $permissions = [
        'create' => [],
        'read' => [],
        'update' => [],
        'delete' => [],
        'list' => []
    ];

    /**
     * @var ManagerUsers
     */
    protected ManagerUsers $model;

    public function __construct()
    {
        $this->model = new ManagerUsers();
    }

    /**
     * @param array $params
     *
     * @return ManagerUsers
     */
    public function create(array $params = []): ManagerUsers
    {
        // TODO: Implement create() method.
    }

    /**
     * @param array $params
     *
     * @return ManagerUsers
     */
    public function read(array $params = []): ManagerUsers
    {
        // TODO: Implement read() method.
    }

    /**
     * @param array $params
     *
     * @return ManagerUsers
     */
    public function update(array $params = []): ManagerUsers
    {
        // TODO: Implement update() method.
    }

    /**
     * @param array $params
     *
     * @return ManagerUsers
     */
    public function delete(array $params = []): ManagerUsers
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param array $params
     *
     * @return ManagerUsers
     */
    public function copy(array $params = []): ManagerUsers
    {
        // TODO: Implement copy() method.
    }

    /**
     * @param array $params
     * @return ManagerUsers[]|array
     */
    public function list(array $params = []): array
    {
        $app = evolutionCMS();
        
        $limit = intval($params['limit'] ?? 10);
        $offset = intval($params['offset'] ?? 0);
        $meta = [];

        $meta['pagination'] = [
            'total' => $app->db->getRow($app->db->query('SELECT COUNT(*) as cnt FROM ' . $app->getFullTableName('user_attributes')))['cnt'],
            'limit' => $limit
        ];

        if ($offset > $meta['pagination']['total']) {
            $offset = $meta['pagination']['total'] > $limit ? $meta['pagination']['total'] - $limit : 0;
        }

        $data = $app->db->makeArray($app->db->query('
            SELECT
            ua.internalKey as id,
            mu.username,
            ua.fullname,
            ur.name as role,
            ua.email,
            DATE_FORMAT(FROM_UNIXTIME(ua.lastlogin), "%d-%m-%Y %H:%i") AS lastlogin,
            ua.logincount,
            ua.blocked
            FROM ' . $app->getFullTableName('user_attributes') . ' ua
            LEFT JOIN ' . $app->getFullTableName('manager_users') . ' mu ON mu.id=ua.internalKey
            LEFT JOIN ' . $app->getFullTableName('user_roles') . ' ur ON ur.id=ua.role
            LIMIT ' . $limit . ' OFFSET ' . $offset . '
        '));
        
        $this->setMeta($meta);

        return $data;
    }
}
