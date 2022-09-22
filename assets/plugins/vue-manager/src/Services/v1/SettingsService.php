<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use Exception;

class SettingsService
{
    /**
     * @param array $params
     * @return array[]
     * @throws Exception
     */
    public function get(array $params = []): array
    {
        $app = evolutionCMS();
        $data = [
            'config' => $app->config,
            'user' => vum()->getUser(),
            'categories' => $this->getCategories(),
            'permissions' => $this->getPermissionsByRole((int) vum()->getUser('role')),
        ];

        $removeKeys = ['base_path', 'view', 'sys_files_checksum', 'check_files_onlogin', 'filemanager_path', 'rb_base_dir', 'site_manager_path'];
        foreach ($removeKeys as $k) {
            if (isset($data['config'][$k])) {
                unset($data['config'][$k]);
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getCategories(): array
    {
        $app = evolutionCMS();

        $data = [
            0 => [
                'id' => 0,
                'category' => vum()->getLang('no_category'),
                'rank' => 0
            ]
        ];

        $rs = $app->db->makeArray($app->db->select('*', '[+prefix+]categories'));

        foreach ($rs as $r) {
            $data[$r['id']] = $r;
        }

        return $data;
    }

    /**
     * @param int $role
     * @return array
     */
    protected function getPermissionsByRole(int $role): array
    {
        $app = evolutionCMS();

        return $app->db->getRow($app->db->select('*', $app->getFullTableName('user_roles'), 'id=' . $role));
    }
}
