<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Application;
use VueManager\Exceptions\NotFoundException;
use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\SitePlugins;
use VueManager\Traits\ServicePermissionTrait;

class PluginService implements ServiceInterface
{
    use ServicePermissionTrait;

    /**
     * @var array
     */
    protected array $permissions = [
        'create' => ['new_plugin', 'save_plugin'],
        'read' => ['edit_plugin'],
        'update' => ['edit_plugin', 'save_plugin'],
        'delete' => ['delete_plugin'],
        'list' => ['edit_plugin']
    ];

    /**
     * @param SitePlugins $model
     * @return \VueManager\Models\v1\SitePlugins
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function create($model): SitePlugins
    {
        $this->hasPermissionsCreate();
        $app = evolutionCMS();

        $data = $model->except(['id'])
            ->toData();

        $model->id = $app->db->insert($data, $app->getFullTableName('site_plugins'));

        if (!$model->id) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param SitePlugins $model
     * @return \VueManager\Models\v1\SitePlugins
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function read($model): SitePlugins
    {
        $this->hasPermissionsRead();
        $app = evolutionCMS();

        if (!empty($model->id)) {
            $data = $app->db->getRow(
                $app->db->select('*', $app->getFullTableName('site_plugins'), 'id=' . $model->id)
            );

            if (!empty($data)) {
                return $model->hydrate($data);
            }
        }

        throw new NotFoundException();
    }

    /**
     * @param SitePlugins $model
     * @return \VueManager\Models\v1\SitePlugins
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function update($model): SitePlugins
    {
        $this->hasPermissionsUpdate();
        $app = evolutionCMS();

        $data = $model->toArray();
        $model = $this->read($model)
            ->hydrate($data);
        $model->editedon = time();

        $app->db->update($model->toData(), $app->getFullTableName('site_plugins'), 'id=' . $model->id);

        return $model;
    }

    /**
     * @param SitePlugins $model
     * @return \VueManager\Models\v1\SitePlugins
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function delete($model): SitePlugins
    {
        $this->hasPermissionsDelete();
        $app = evolutionCMS();

        if (!empty($model->id)) {
            $model = $this->read($model);
            $app->db->delete($app->getFullTableName('site_plugins'), 'id=' . $model->id);

            return $model;
        }

        throw new NotFoundException();
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
                    FROM ' . $modx->getFullTableName('site_plugins') . ' t
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
                    FROM ' . $modx->getFullTableName('site_plugins') . ' t
                    ORDER BY t.name
                ')
            );
        }

        return [$data];
    }
}
