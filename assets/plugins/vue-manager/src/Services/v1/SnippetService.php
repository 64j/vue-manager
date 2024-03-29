<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Exceptions\NotFoundException;
use VueManager\Exceptions\PermissionException;
use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\SiteSnippets;
use VueManager\Traits\ServiceMetaTrait;
use VueManager\Traits\ServicePermissionTrait;

class SnippetService implements ServiceInterface
{
    use ServicePermissionTrait;
    use ServiceMetaTrait;

    /**
     * @var array
     */
    protected array $permissions = [
        'create' => ['new_snippet', 'save_snippet'],
        'read' => ['edit_snippet'],
        'update' => ['edit_snippet', 'save_snippet'],
        'delete' => ['delete_snippet'],
        'list' => ['edit_snippet']
    ];

    /**
     * @var SiteSnippets
     */
    protected SiteSnippets $model;

    public function __construct()
    {
        $this->model = new SiteSnippets();
    }

    /**
     * @param array $params
     *
     * @return SiteSnippets
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function create(array $params = []): SiteSnippets
    {
        $this->hasPermissionsCreate();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);
        $model->createdon = time();

        $data = $model->except(['id'])
            ->toData();

        $model->id = $app->db->insert($data, $app->getFullTableName('site_snippets'));

        if (!$model->id) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param array $params
     *
     * @return SiteSnippets
     * @throws PermissionException
     */
    public function read(array $params = []): SiteSnippets
    {
        $this->hasPermissionsRead();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);

        if (!empty($model->id)) {
            $data = $app->db->getRow(
                $app->db->select('*', $app->getFullTableName('site_snippets'), 'id=' . $model->id)
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
     * @return SiteSnippets
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function update(array $params = []): SiteSnippets
    {
        $this->hasPermissionsUpdate();
        $app = evolutionCMS();

        $model = $this->read($params)
            ->hydrate($params);

        if (!$model->id) {
            throw new NotFoundException();
        }

        $model->editedon = time();

        $app->db->update($model->toData(), $app->getFullTableName('site_snippets'), 'id=' . $model->id);

        return $this->read($model->toArray());
    }

    /**
     * @param array $params
     *
     * @return SiteSnippets
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function delete(array $params = []): SiteSnippets
    {
        $this->hasPermissionsDelete();
        $app = evolutionCMS();

        $model = $this->read($params);

        if (!empty($model->id)) {
            $app->db->delete($app->getFullTableName('site_snippets'), 'id=' . $model->id);

            return $model;
        }

        throw new NotFoundException();
    }

    /**
     * @param array $params
     *
     * @return SiteSnippets
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function copy(array $params = []): SiteSnippets
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
                    t.disabled,
                    t.category,
                    IF(t.category=0,"' . vum()->getLang('no_category') . '",c.category) AS category_name
                    FROM ' . $modx->getFullTableName('site_snippets') . ' t
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
                    FROM ' . $modx->getFullTableName('site_snippets') . ' t
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
