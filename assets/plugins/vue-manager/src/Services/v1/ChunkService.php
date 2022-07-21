<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Exceptions\NotFoundException;
use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\SiteHtmlsnippets;
use VueManager\Traits\ServiceMetaTrait;
use VueManager\Traits\ServicePermissionTrait;

class ChunkService implements ServiceInterface
{
    use ServicePermissionTrait;
    use ServiceMetaTrait;

    /**
     * @var array
     */
    protected array $permissions = [
        'create' => ['new_chunk', 'save_chunk'],
        'read' => ['edit_chunk'],
        'update' => ['edit_chunk', 'save_chunk'],
        'delete' => ['delete_chunk'],
        'list' => ['edit_chunk']
    ];

    /**
     * @var \VueManager\Models\v1\SiteHtmlsnippets
     */
    protected SiteHtmlsnippets $model;

    public function __construct()
    {
        $this->model = new SiteHtmlsnippets();
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteHtmlsnippets
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function create(array $params = []): SiteHtmlsnippets
    {
        $this->hasPermissionsCreate();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);
        $model->createdon = time();

        $data = $model->except(['id'])
            ->toData();

        $model->id = $app->db->insert($data, $app->getFullTableName('site_htmlsnippets'));

        if (!$model->id) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteHtmlsnippets
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function read(array $params = []): SiteHtmlsnippets
    {
        $this->hasPermissionsRead();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);

        if (!empty($model->id)) {
            $data = $app->db->getRow(
                $app->db->select('*', $app->getFullTableName('site_htmlsnippets'), 'id=' . $model->id)
            );

            if (!empty($data)) {
                $model->hydrate($data);
            }
        }

        return $model;
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteHtmlsnippets
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function update(array $params = []): SiteHtmlsnippets
    {
        $this->hasPermissionsUpdate();
        $app = evolutionCMS();

        $model = $this->read($params)
            ->hydrate($params);

        if (!$model->id) {
            throw new NotFoundException();
        }

        $model->editedon = time();

        $app->db->update($model->toData(), $app->getFullTableName('site_htmlsnippets'), 'id=' . $model->id);

        return $this->read($model->toArray());
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteHtmlsnippets
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function delete(array $params = []): SiteHtmlsnippets
    {
        $this->hasPermissionsDelete();
        $app = evolutionCMS();

        $model = $this->read($params);

        if (!empty($model->id)) {
            $app->db->delete($app->getFullTableName('site_htmlsnippets'), 'id=' . $model->id);

            return $model;
        }

        throw new NotFoundException();
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteHtmlsnippets
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function copy(array $params = []): SiteHtmlsnippets
    {
        return $this->create($this->read($params)
            ->toArray());
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function list(array $params = []): array
    {
        $this->hasPermissionsList();
        $modx = evolutionCMS();
        $data = [];

        if (!empty($params['categories'])) {
            $rs = $modx->db->makeArray(
                $modx->db->query('
                    SELECT
                    t.id,
                    t.name,
                    t.description,
                    t.locked,
                    t.disabled,
                    t.category,
                    IF(t.category=0,"' . vum()->getLang('no_category') . '",c.category) AS category_name
                    FROM ' . $modx->getFullTableName('site_htmlsnippets') . ' t
                    LEFT JOIN ' . $modx->getFullTableName('categories') . ' c ON c.id=t.category
                    ORDER BY c.rank
                ')
            );

            foreach ($rs as $r) {
                if (!isset($data[$r['category']])) {
                    $data[$r['category']] = [
                        'id' => $r['category'],
                        'name' => $r['category_name'],
                        'items' => []
                    ];
                }

                unset($r['category_name']);

                $data[$r['category']]['items'][$r['id']] = $r;
            }
        } else {
            $data = $modx->db->makeArray(
                $modx->db->query('
                    SELECT
                    t.id,
                    t.name,
                    t.description,
                    t.locked,
                    t.disabled,
                    t.category
                    FROM ' . $modx->getFullTableName('site_htmlsnippets') . ' t
                    ORDER BY t.name
                ')
            );
        }

        $this->setMeta([
            'pagination' => []
        ]);

        return $data;
    }
}
