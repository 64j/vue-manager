<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Application;
use VueManager\Traits\ResponseTrait;

class TemplateController
{
    use ResponseTrait;

    /**
     * @param array $params
     * @return array
     */
    public function actionList(array $params = []): array
    {
        $modx = evolutionCMS();

        $data = [];

        $rs = $modx->db->makeArray(
            $modx->db->query('
                SELECT
                t.id,
                t.templatename AS name,
                t.templatealias AS alias,
                t.description,
                t.locked,
                t.category,
                IF(t.category=0,"' . Application::getInstance()
                    ->getLang('no_category') . '",c.category) AS category_name
                FROM ' . $modx->getFullTableName('site_templates') . ' t
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

        return $this->ok($data);
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionRead(array $params = []): array
    {
        $modx = evolutionCMS();
        $data = [];

        if (!empty($params['id'])) {
            $data = $modx->db->getRow($modx->db->select('*', '[+prefix+]site_templates', 'id=' . (int) $params['id']));
        }

        return $this->ok($data);
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionUpdate(array $params = []): array
    {
        return $this->ok();
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionCreate(array $params = []): array
    {
        return $this->ok();
    }

    /**
     * @param array $params
     * @return array
     */
    public function actionDelete(array $params = []): array
    {
        return $this->ok();
    }
}
