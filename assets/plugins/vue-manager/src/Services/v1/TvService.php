<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Exceptions\NotFoundException;
use VueManager\Exceptions\PermissionException;
use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\SiteTmplvars;
use VueManager\Traits\ServiceMetaTrait;
use VueManager\Traits\ServicePermissionTrait;

class TvService implements ServiceInterface
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
     * @var SiteTmplvars
     */
    public SiteTmplvars $model;

    public function __construct()
    {
        $this->model = new SiteTmplvars();
    }

    /**
     * @param array $params
     *
     * @return SiteTmplvars
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function create(array $params = []): SiteTmplvars
    {
        $this->hasPermissionsCreate();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);
        $model->createdon = time();

        $data = $model->except(['id'])
            ->toData();

        $model->id = $app->db->insert($data, $app->getFullTableName('site_tmplvars'));

        if (!$model->id) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param array $params
     *
     * @return SiteTmplvars
     * @throws PermissionException
     */
    public function read(array $params = []): SiteTmplvars
    {
        $this->hasPermissionsRead();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);

        if (!empty($model->id)) {
            $data = $app->db->getRow(
                $app->db->select('*', $app->getFullTableName('site_tmplvars'), 'id=' . $model->id)
            );

            if (!empty($data)) {
                $model->hydrate($data);
            }
        }

        return $model;
    }

    /**
     * @param array $params
     *
     * @return SiteTmplvars
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function update(array $params = []): SiteTmplvars
    {
        $this->hasPermissionsUpdate();
        $app = evolutionCMS();

        $model = $this->read($params)
            ->hydrate($params);

        if (!$model->id) {
            throw new NotFoundException();
        }

        $model->editedon = time();

        $app->db->update($model->toData(), $app->getFullTableName('site_tmplvars'), 'id=' . $model->id);

        return $this->read($model->toArray());
    }

    /**
     * @param array $params
     *
     * @return SiteTmplvars
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function delete(array $params = []): SiteTmplvars
    {
        $this->hasPermissionsDelete();
        $app = evolutionCMS();

        $model = $this->read($params);

        if (!empty($model->id)) {
            $app->db->delete($app->getFullTableName('site_tmplvars'), 'id=' . $model->id);

            return $model;
        }

        throw new NotFoundException();
    }

    /**
     * @param array $params
     *
     * @return SiteTmplvars
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function copy(array $params = []): SiteTmplvars
    {
        return $this->create($this->read($params)
            ->toArray());
    }

    /**
     * @param array $params
     * @return array
     * @throws PermissionException
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
                    t.category,
                    IF(t.category=0,"' . vum()->getLang('no_category') . '",c.category) AS category_name
                    FROM ' . $modx->getFullTableName('site_tmplvars') . ' t
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
                    t.category
                    FROM ' . $modx->getFullTableName('site_tmplvars') . ' t
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
