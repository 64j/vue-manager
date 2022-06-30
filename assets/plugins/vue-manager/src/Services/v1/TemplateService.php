<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Application;
use VueManager\Exceptions\NotFoundException;
use VueManager\Interfaces\ServiceInterface;

class TemplateService implements ServiceInterface
{
    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function create(array $params = []): array
    {
        $app = evolutionCMS();
        $data = [];

        if (!empty($params)) {
            $data = $this->read([
                'id' => $app->db->insert($params, $app->getFullTableName('site_templates'))
            ]);
        }

        return $data;
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function read(array $params = []): array
    {
        $app = evolutionCMS();
        $data = [];

        if (!empty($params['id'])) {
            $data = $app->db->getRow($app->db->select('*', $app->getFullTableName('site_templates'), 'id=' . (int) $params['id']));
        }

        if (!empty($data)) {
            return $data;
        }

        throw new NotFoundException();
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function update(array $params = []): array
    {
        $app = evolutionCMS();
        $data = array_merge($this->read($params), $params);
        $app->db->update($data, $app->getFullTableName('site_templates'), 'id=' . $data['id']);

        return $data;
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function delete(array $params = []): array
    {
        $app = evolutionCMS();
        $data = $this->read($params);
        $app->db->delete($app->getFullTableName('site_templates'), 'id=' . $data['id']);

        return $data;
    }

    /**
     * @param array $params
     * @return array
     */
    public function list(array $params = []): array
    {
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

        return $data;
    }
}
