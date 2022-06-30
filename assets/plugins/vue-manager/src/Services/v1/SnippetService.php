<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Application;
use VueManager\Exceptions\NotFoundException;
use VueManager\Interfaces\ServiceInterface;

class SnippetService implements ServiceInterface
{
    /**
     * @param array $params
     * @return array
     */
    public function create(array $params = []): array
    {
        $data = [];

        return $data;
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function read(array $params = []): array
    {
        $modx = evolutionCMS();
        $data = [];

        if (!empty($params['id'])) {
            $data = $modx->db->getRow($modx->db->select('*', $modx->getFullTableName('site_snippets'), 'id=' . (int) $params['id']));
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
        $data = $this->read($params);

        return $data;
    }

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function delete(array $params = []): array
    {
        $data = $this->read($params);

        return $data;
    }

    /**
     * @param array $params
     * @return array
     */
    public function list(array $params = []): array
    {
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

        return $data;
    }
}
