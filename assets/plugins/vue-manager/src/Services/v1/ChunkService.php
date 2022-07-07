<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Application;
use VueManager\Exceptions\NotFoundException;
use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\AbstractModel;
use VueManager\Models\v1\SiteHtmlsnippets;
use VueManager\Traits\ServicePermissionTrait;

class ChunkService implements ServiceInterface
{
    use ServicePermissionTrait;

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
     * @param SiteHtmlsnippets $model
     * @return \VueManager\Models\v1\SiteHtmlsnippets
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function create($model): SiteHtmlsnippets
    {
        $this->hasPermissionsCreate();
        $app = evolutionCMS();

        $data = $model->except(['id'])
            ->toData();

        $model->id = $app->db->insert($data, $app->getFullTableName('site_htmlsnippets'));

        if (!$model->id) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param SiteHtmlsnippets $model
     * @return \VueManager\Models\v1\SiteHtmlsnippets
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function read($model): SiteHtmlsnippets
    {
        $this->hasPermissionsRead();
        $app = evolutionCMS();

        if (!empty($model->id)) {
            $data = $app->db->getRow(
                $app->db->select('*', $app->getFullTableName('site_htmlsnippets'), 'id=' . $model->id)
            );

            if (!empty($data)) {
                return $model->hydrate($data);
            }
        }

        throw new NotFoundException();
    }

    /**
     * @param SiteHtmlsnippets $model
     * @return \VueManager\Models\v1\SiteHtmlsnippets
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function update($model): SiteHtmlsnippets
    {
        $this->hasPermissionsUpdate();
        $app = evolutionCMS();

        $data = $model->toArray();
        $model = $this->read($model)
            ->hydrate($data);
        $model->editedon = time();

        $app->db->update($model->toData(), $app->getFullTableName('site_htmlsnippets'), 'id=' . $model->id);

        return $model;
    }

    /**
     * @param SiteHtmlsnippets $model
     * @return \VueManager\Models\v1\SiteHtmlsnippets
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function delete($model): SiteHtmlsnippets
    {
        $this->hasPermissionsDelete();
        $app = evolutionCMS();

        if (!empty($model->id)) {
            $model = $this->read($model);
            $app->db->delete($app->getFullTableName('site_htmlsnippets'), 'id=' . $model->id);

            return $model;
        }

        throw new NotFoundException();
    }

    public function copy(AbstractModel $model): AbstractModel
    {
        // TODO: Implement copy() method.
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
                    IF(t.category=0,"' . Application::getInstance()
                        ->getLang('no_category') . '",c.category) AS category_name
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

        return [$data];
    }
}
