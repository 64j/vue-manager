<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Application;
use VueManager\Exceptions\NotFoundException;
use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\SiteTemplates;
use VueManager\Traits\ServicePermissionTrait;

class TemplateService implements ServiceInterface
{
    use ServicePermissionTrait;

    /**
     * @var array
     */
    protected array $permissions = [
        'create' => ['new_template', 'save_template'],
        'read' => ['edit_template'],
        'update' => ['edit_template', 'save_template'],
        'delete' => ['delete_template'],
        'list' => ['edit_template']
    ];

    /**
     * @param SiteTemplates $model
     * @return \VueManager\Models\v1\SiteTemplates
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function create($model): SiteTemplates
    {
        $this->hasPermissionsCreate();
        $app = evolutionCMS();

        $data = $model->except(['id'])
            ->toData();

        $model->id = $app->db->insert($data, $app->getFullTableName('site_templates'));

        if (!$model->id) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param SiteTemplates $model
     * @return SiteTemplates
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function read($model): SiteTemplates
    {
        $this->hasPermissionsRead();
        $app = evolutionCMS();

        if (!empty($model->id)) {
            $data = $app->db->getRow(
                $app->db->select('*', $app->getFullTableName('site_templates'), 'id=' . $model->id)
            );

            if (!empty($data)) {
                return $model->hydrate($data);
            }
        }

        throw new NotFoundException();
    }

    /**
     * @param SiteTemplates $model
     * @return SiteTemplates
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function update($model): SiteTemplates
    {
        $this->hasPermissionsUpdate();
        $app = evolutionCMS();

        $data = $model->toArray();
        $model = $this->read($model)
            ->hydrate($data);
        $model->editedon = time();

        $app->db->update($model->toData(), $app->getFullTableName('site_templates'), 'id=' . $model->id);

        return $model;
    }

    /**
     * @param SiteTemplates $model
     * @return \VueManager\Models\v1\SiteTemplates
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function delete($model): SiteTemplates
    {
        $this->hasPermissionsDelete();
        $app = evolutionCMS();

        if (!empty($model->id)) {
            $model = $this->read($model);
            $app->db->delete($app->getFullTableName('site_templates'), 'id=' . $model->id);

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
        $app = evolutionCMS();
        $data = [];

        if (!empty($params['categories'])) {
            $rs = $app->db->makeArray(
                $app->db->query('
                    SELECT
                    t.id,
                    t.templatename AS name,
                    t.templatealias AS alias,
                    t.description,
                    t.locked,
                    t.category,
                    IF(t.category=0,"' . Application::getInstance()
                        ->getLang('no_category') . '",c.category) AS category_name
                    FROM ' . $app->getFullTableName('site_templates') . ' t
                    LEFT JOIN ' . $app->getFullTableName('categories') . ' c ON c.id=t.category
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
            $data = $app->db->makeArray(
                $app->db->query('
                    SELECT
                    t.id,
                    t.templatename AS name,
                    t.templatealias AS alias,
                    t.description,
                    t.locked,
                    t.category
                    FROM ' . $app->getFullTableName('site_templates') . ' t
                    ORDER BY t.templatename
                ')
            );
        }

        return [$data];
    }
}
