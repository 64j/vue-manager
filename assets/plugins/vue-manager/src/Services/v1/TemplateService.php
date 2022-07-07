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
                $model = $model->hydrate($data);

                $model->__meta = [
                    'tvSelected' => [],
                    'tvs' => [
                        'selected' => [],
                        'unselected' => []
                    ]
                ];

                $noCategory = Application::getInstance()
                    ->getLang('no_category');

                $field = 'tv.id, tv.name, tr.templateid, tv.description, tv.caption, tv.locked, ifnull(cat.category,"' . $noCategory . '") AS category, ifnull(cat.id,0) as catid';

                $table = sprintf(
                    '%s tv
                    LEFT JOIN %s tr ON tv.id=tr.tmplvarid
                    LEFT JOIN %s cat ON tv.category=cat.id',
                    $app->getFullTableName('site_tmplvars'),
                    $app->getFullTableName('site_tmplvar_templates'),
                    $app->getFullTableName('categories')
                );

                $sql = $app->db->select(
                    $field,
                    $table,
                    'templateid=' . $model->id . ' GROUP BY tv.id',
                    'tr.rank ASC, tv.rank ASC, caption ASC, id ASC'
                );

                while ($r = $app->db->getRow($sql)) {
                    if (!isset($model->__meta['tvs']['selected'][$r['catid']])) {
                        $model->__meta['tvs']['selected'][$r['catid']] = [
                            'id' => $r['catid'],
                            'name' => $r['category'],
                            'items' => []
                        ];
                    }

                    $model->__meta['tvs']['selected'][$r['catid']]['items'][$r['id']] = $r;
                    $model->__meta['tvSelected'][] = $r['id'];
                }

                $sql = $app->db->select(
                    $field,
                    $table,
                    $model->__meta['tvSelected'] ? 'tv.id NOT IN(' . implode(',', $model->__meta['tvSelected']) . ')' : ''
                );

                while ($r = $app->db->getRow($sql)) {
                    if (!isset($model->__meta['tvs']['unselected'][$r['catid']])) {
                        $model->__meta['tvs']['unselected'][$r['catid']] = [
                            'id' => $r['catid'],
                            'name' => $r['category'],
                            'items' => []
                        ];
                    }

                    $model->__meta['tvs']['unselected'][$r['catid']]['items'][$r['id']] = $r;
                }

                return $model;
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

        $rs = $app->db->select('*', $app->getFullTableName('site_templates'), 'id=' . $model->id);

        if (!$app->db->getRecordCount($rs)) {
            throw new NotFoundException();
        }

        $model->editedon = time();

        $app->db->update($model->toData(), $app->getFullTableName('site_templates'), 'id=' . $model->id);

        if (isset($model->__meta['tvSelected'])) {
            $rs = $app->db->select(
                'tmplvarid, `rank`',
                $app->getFullTableName('site_tmplvar_templates'),
                'templateid=' . $model->id
            );

            $ranksArr = [];
            $highest = 0;

            while ($r = $app->db->getRow($rs)) {
                $ranksArr[$r['tmplvarid']] = $r['rank'];
                $highest = max($highest, $r['rank']);
            }

            $app->db->delete(
                $app->getFullTableName('site_tmplvar_templates'),
                'templateid=' . $model->id
            );

            if (!empty($model->__meta['tvSelected'])) {
                foreach ($model->__meta['tvSelected'] as $id) {
                    if (is_integer($id)) {
                        $app->db->insert(array(
                            'templateid' => $model->id,
                            'tmplvarid' => $id,
                            'rank' => $ranksArr[$id] ?? $highest += 1
                        ), $app->getFullTableName('site_tmplvar_templates'));
                    }
                }
            }
        }

        return $this->read($model);
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
