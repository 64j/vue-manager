<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Application;
use VueManager\Exceptions\NotFoundException;
use VueManager\Interfaces\ModelInterface;
use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\SiteTemplates;
use VueManager\Traits\ServiceMetaTrait;
use VueManager\Traits\ServicePermissionTrait;

class TemplateService implements ServiceInterface
{
    use ServicePermissionTrait;
    use ServiceMetaTrait;

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
     * @var \VueManager\Models\v1\SiteTemplates|null
     */
    protected ?ModelInterface $model = null;

    public function __construct()
    {
        $this->model = new SiteTemplates();
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteTemplates
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function create(array $params = []): SiteTemplates
    {
        $this->hasPermissionsCreate();
        $app = evolutionCMS();
        $this->model->hydrate($params, true);
        $data = $this->model->except(['id'])
            ->toData();

        $this->model->id = $app->db->insert($data, $app->getFullTableName('site_templates'));
        $this->model->createdon = time();

        if (!$this->model->id) {
            throw new NotFoundException();
        }

        return $this->update($this->model->toArray());
    }

    /**
     * @param array $params
     * @return SiteTemplates
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function read(array $params = []): SiteTemplates
    {
        $this->hasPermissionsRead();
        $app = evolutionCMS();

        if (!empty($params['id'])) {
            $params = $app->db->getRow(
                $app->db->select('*', $app->getFullTableName('site_templates'), 'id=' . (int) $params['id'])
            ) ?: $params;
        }

        $this->model->hydrate($params, true);

        $meta = [
            'tvSelected' => [],
            'tvs' => [
                'selected' => [],
                'unselected' => []
            ]
        ];

        $noCategory = Application::getInstance()
            ->getLang('no_category');

        $field = 'tv.id, tv.name, tr.templateid, tv.description, tv.caption, tv.locked, ifnull(cat.category,"' . $noCategory . '") AS category, ifnull(cat.id,0) as categoryId';

        $table = $app->getFullTableName('site_tmplvars') . ' tv
        LEFT JOIN ' . $app->getFullTableName('site_tmplvar_templates') . ' tr ON tv.id=tr.tmplvarid
        LEFT JOIN ' . $app->getFullTableName('categories') . ' cat ON tv.category=cat.id';

        if (!empty($this->model->id)) {
            $sql = $app->db->select(
                $field,
                $table,
                'templateid=' . $this->model->id . ' GROUP BY tv.id',
                'tr.rank ASC, tv.rank ASC, caption ASC, id ASC'
            );

            while ($r = $app->db->getRow($sql)) {
                if (!isset($meta['tvs']['selected'][$r['categoryId']])) {
                    $meta['tvs']['selected'][$r['categoryId']] = [
                        'id' => $r['categoryId'],
                        'name' => $r['category'],
                        'items' => []
                    ];
                }

                $meta['tvs']['selected'][$r['categoryId']]['items'][$r['id']] = $r;
                $meta['tvSelected'][] = $r['id'];
            }
        }

        $sql = $app->db->select(
            $field,
            $table,
            $meta['tvSelected'] ? 'tv.id NOT IN(' . implode(',', $meta['tvSelected']) . ')' : ''
        );

        while ($r = $app->db->getRow($sql)) {
            if (!isset($meta['tvs']['unselected'][$r['categoryId']])) {
                $meta['tvs']['unselected'][$r['categoryId']] = [
                    'id' => $r['categoryId'],
                    'name' => $r['category'],
                    'items' => []
                ];
            }

            $meta['tvs']['unselected'][$r['categoryId']]['items'][$r['id']] = $r;
        }

        $this->setMeta($meta);

        return $this->model;
    }

    /**
     * @param array $params
     * @return SiteTemplates
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function update(array $params = []): SiteTemplates
    {
        $this->hasPermissionsUpdate();
        $app = evolutionCMS();

        $model = $this->read($params)
            ->hydrate($params);

        if (!$model->id) {
            throw new NotFoundException();
        }

        $model->editedon = time();

        $app->db->update($model->toData(), $app->getFullTableName('site_templates'), 'id=' . $model->id);

        if (isset($params['tvSelected'])) {
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

            if (!empty($params['tvSelected'])) {
                foreach ($params['tvSelected'] as $id) {
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

        return $this->read($model->toArray());
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteTemplates
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function delete(array $params = []): SiteTemplates
    {
        $this->hasPermissionsDelete();
        $app = evolutionCMS();
        $model = $this->read($params);

        if (!empty($model->id)) {
            $app->db->delete(
                $app->getFullTableName('site_templates'),
                'id=' . $model->id
            );

            $app->db->delete(
                $app->getFullTableName('site_tmplvar_templates'),
                'templateid=' . $model->id
            );

            return $model;
        }

        throw new NotFoundException();
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteTemplates
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function copy(array $params = []): SiteTemplates
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

        $this->setMeta([
            'pagination' => []
        ]);

        return $data;
    }
}
