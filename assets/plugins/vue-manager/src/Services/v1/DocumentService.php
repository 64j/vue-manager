<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Exceptions\NotFoundException;
use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\SiteContent;
use VueManager\Traits\ServiceMetaTrait;
use VueManager\Traits\ServicePermissionTrait;

class DocumentService implements ServiceInterface
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
     * @var \VueManager\Models\v1\SiteContent
     */
    protected SiteContent $model;

    public function __construct()
    {
        $this->model = new SiteContent();
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteContent
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function create(array $params = []): SiteContent
    {
        $this->hasPermissionsCreate();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);
        $model->createdon = time();

        $data = $model->except(['id'])
            ->toData();

        $model->id = $app->db->insert($data, $app->getFullTableName('site_content'));

        if (!$model->id) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteContent
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function read(array $params = []): SiteContent
    {
        $this->hasPermissionsRead();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);

        if (!empty($model->id)) {
            $data = $app->db->getRow(
                $app->db->select('*', $app->getFullTableName('site_content'), 'id=' . $model->id)
            );

            if (!empty($data)) {
                $model->hydrate($data);

                $this->setMeta([
                    'templates' => $this->getTemplates((int) $data['template']),
                    'types' => ['text/html', 'text/plain', 'text/xml']
                ]);
            }
        }

        return $model;
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteContent
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function update(array $params = []): SiteContent
    {
        $this->hasPermissionsUpdate();
        $app = evolutionCMS();

        $model = $this->read($params)
            ->hydrate($params);

        if (!$model->id) {
            throw new NotFoundException();
        }

        $model->editedon = time();

        $app->db->update($model->toData(), $app->getFullTableName('site_content'), 'id=' . $model->id);

        return $this->read($model->toArray());
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteContent
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function delete(array $params = []): SiteContent
    {
        $this->hasPermissionsDelete();
        $app = evolutionCMS();

        $model = $this->read($params);

        if (!empty($model->id)) {
            $app->db->delete($app->getFullTableName('site_content'), 'id=' . $model->id);

            return $model;
        }

        throw new NotFoundException();
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\SiteContent
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function copy(array $params = []): SiteContent
    {
        return $this->create($this->read($params)
            ->toArray());
    }

    /**
     * @inheritDoc
     */
    public function list(array $params = []): array
    {
        return [];
    }

    /**
     * @param int $template
     * @return array
     */
    protected function getTemplates(int $template = 0): array
    {
        $app = evolutionCMS();
        $data = [];

        $field = 't.templatename, t.selectable, t.id, c.category, c.id AS category_id';
        $from = $app->getFullTableName('site_templates') . ' AS t LEFT JOIN ' . $app->getFullTableName('categories') . ' AS c ON t.category = c.id';
        $rs = $app->db->select($field, $from, '', 'c.category, t.templatename ASC');
        $currentCategory = '';

        while ($row = $app->db->getRow($rs)) {
            $categoryId = (int) $row['category_id'];
            if ($row['selectable'] != 1 && $row['id'] != $template) {
                continue;
            }

            $thisCategory = $row['category'];
            if ($thisCategory == null) {
                $thisCategory = vum()->getLang('no_category');
            }

            if ($thisCategory != $currentCategory) {
                $data[$categoryId] = [
                    'id' => $categoryId,
                    'title' => $thisCategory,
                    'items' => []
                ];
            }

            $data[$categoryId]['items'][] = [
                'id' => $row['id'],
                'name' => $row['templatename']
            ];

            $currentCategory = $thisCategory;
        }

        return $data;
    }
}
