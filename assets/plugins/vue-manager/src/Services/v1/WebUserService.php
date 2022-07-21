<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\WebUsers;
use VueManager\Traits\ServiceMetaTrait;
use VueManager\Traits\ServicePermissionTrait;

class WebUserService implements ServiceInterface
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
     * @var \VueManager\Models\v1\WebUsers
     */
    protected WebUsers $model;

    public function __construct()
    {
        $this->model = new WebUsers();
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\WebUsers
     */
    public function create(array $params = []): WebUsers
    {
        // TODO: Implement create() method.
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\WebUsers
     */
    public function read(array $params = []): WebUsers
    {
        // TODO: Implement read() method.
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\WebUsers
     */
    public function update(array $params = []): WebUsers
    {
        // TODO: Implement update() method.
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\WebUsers
     */
    public function delete(array $params = []): WebUsers
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\WebUsers
     */
    public function copy(array $params = []): WebUsers
    {
        // TODO: Implement copy() method.
    }

    /**
     * @param array $params
     * @return WebUsers[]|array
     */
    public function list(array $params = []): array
    {
        $app = evolutionCMS();

        $limit = intval($params['limit'] ?? 10);
        $offset = intval($params['offset'] ?? 0);
        $meta = [];

        $meta['pagination'] = [
            'total' => $app->db->getRow($app->db->query('SELECT COUNT(*) as cnt FROM ' . $app->getFullTableName('web_user_attributes')))['cnt'],
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
            ua.email,
            DATE_FORMAT(FROM_UNIXTIME(ua.lastlogin), "%d-%m-%Y %H:%i") AS lastlogin,
            ua.logincount,
            ua.blocked
            FROM ' . $app->getFullTableName('web_user_attributes') . ' ua
            LEFT JOIN ' . $app->getFullTableName('web_users') . ' mu ON mu.id=ua.internalKey
            LIMIT ' . $limit . ' OFFSET ' . $offset . '
        '));

        $this->setMeta($meta);

        return $data;
    }
}
