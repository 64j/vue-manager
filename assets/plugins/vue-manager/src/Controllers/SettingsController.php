<?php

declare(strict_types=1);

namespace VueManager\Controllers;

use VueManager\Application;
use VueManager\Traits\ResponseTrait;

class SettingsController
{
    use ResponseTrait;

    /**
     * @param array $params
     * @return array[]
     * @throws \Exception
     */
    public function actionGet(array $params = []): array
    {
        global $modx_lang_attribute;

        $modx = evolutionCMS();

        $data = [];

        $data['config'] = $modx->config;
        $data['config']['lang_attribute'] = $modx_lang_attribute;

        $removeKeys = ['base_path', 'view', 'sys_files_checksum', 'check_files_onlogin', 'filemanager_path', 'rb_base_dir', 'site_manager_path'];
        foreach ($removeKeys as $k) {
            if (isset($data['config'][$k])) {
                unset($data['config'][$k]);
            }
        }

        $data['user'] = Application::getInstance()
            ->getUser();

        $data['permissions'] = $modx->db->getRow($modx->db->select('*', $modx->getFullTableName('user_roles'), "id='{$data['user']['role']}'"));

        $rs = $modx->db->makeArray($modx->db->select('*', '[+prefix+]categories'));
        $categories = [
            0 => [
                'id' => 0,
                'category' => Application::getInstance()
                    ->getLang('no_category'),
                'rank' => 0
            ]
        ];

        foreach ($rs as $r) {
            $categories[$r['id']] = $r;
        }

        $data['categories'] = $categories;

        return $this->ok($data);
    }
}
