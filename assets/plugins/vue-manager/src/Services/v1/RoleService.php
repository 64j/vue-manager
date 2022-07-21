<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Exceptions\NotFoundException;
use VueManager\Interfaces\ServiceInterface;
use VueManager\Models\v1\UserRoles;
use VueManager\Traits\ServiceMetaTrait;
use VueManager\Traits\ServicePermissionTrait;

class RoleService implements ServiceInterface
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
     * @var \VueManager\Models\v1\UserRoles
     */
    protected UserRoles $model;

    public function __construct()
    {
        $this->model = new UserRoles();
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\UserRoles
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function create(array $params = []): UserRoles
    {
        $this->hasPermissionsCreate();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);

        $data = $model->except(['id'])
            ->toData();

        $model->id = $app->db->insert($data, $app->getFullTableName('user_roles'));

        if (!$model->id) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\UserRoles
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function read(array $params = []): UserRoles
    {
        $this->hasPermissionsRead();
        $app = evolutionCMS();

        $model = $this->model->hydrate($params, true);

        if (!empty($model->id)) {
            $data = $app->db->getRow(
                $app->db->select('*', $app->getFullTableName('user_roles'), 'id=' . $model->id)
            );

            if (!empty($data)) {
                $model->hydrate($data);
            }
        }

        $meta['categories'] = [
            [
                'page_data_general' => [
                    'title' => vum()->getLang('page_data_general'),
                    'items' => [
                        'frames' => [
                            'title' => vum()->getLang('role_frames'),
                            'disabled' => true
                        ],
                        'home' => [
                            'title' => vum()->getLang('role_home'),
                            'disabled' => true
                        ],
                        'messages' => [
                            'title' => vum()->getLang('role_messages')
                        ],
                        'logout' => [
                            'title' => vum()->getLang('role_logout'),
                            'disabled' => true
                        ],
                        'help' => [
                            'title' => vum()->getLang('role_help')
                        ],
                        'action_ok' => [
                            'title' => vum()->getLang('role_actionok'),
                            'disabled' => true
                        ],
                        'error_dialog' => [
                            'title' => vum()->getLang('role_errors'),
                            'disabled' => true
                        ],
                        'about' => [
                            'title' => vum()->getLang('role_about'),
                            'disabled' => true
                        ],
                        'credits' => [
                            'title' => vum()->getLang('role_credits'),
                            'disabled' => true
                        ],
                        'change_password' => [
                            'title' => vum()->getLang('role_change_password')
                        ],
                        'save_password' => [
                            'title' => vum()->getLang('role_save_password')
                        ],
                    ]
                ]
            ],
            [
                'role_content_management' => [
                    'title' => vum()->getLang('role_content_management'),
                    'items' => [
                        'view_document' => [
                            'title' => vum()->getLang('role_view_docdata'),
                            'disabled' => true
                        ],
                        'new_document' => [
                            'title' => vum()->getLang('role_create_doc')
                        ],
                        'edit_document' => [
                            'title' => vum()->getLang('role_edit_doc')
                        ],
                        'change_resourcetype' => [
                            'title' => vum()->getLang('role_change_resourcetype')
                        ],
                        'save_document' => [
                            'title' => vum()->getLang('role_save_doc')
                        ],
                        'publish_document' => [
                            'title' => vum()->getLang('role_publish_doc')
                        ],
                        'delete_document' => [
                            'title' => vum()->getLang('role_delete_doc')
                        ],
                        'empty_trash' => [
                            'title' => vum()->getLang('role_empty_trash')
                        ],
                        'empty_cache' => [
                            'title' => vum()->getLang('role_cache_refresh')
                        ],
                        'view_unpublished' => [
                            'title' => vum()->getLang('role_view_unpublished')
                        ]
                    ]
                ]
            ],
            [
                'role_file_management' => [
                    'title' => vum()->getLang('role_file_management'),
                    'items' => [
                        'file_manager' => [
                            'title' => vum()->getLang('role_file_manager')
                        ],
                        'assets_files' => [
                            'title' => vum()->getLang('role_assets_files')
                        ],
                        'assets_images' => [
                            'title' => vum()->getLang('role_assets_images')
                        ],
                    ]
                ],
                'category_management' => [
                    'title' => vum()->getLang('category_management'),
                    'items' => [
                        'category_manager' => [
                            'title' => vum()->getLang('role_category_manager')
                        ],
                    ]
                ]
            ],
            [
                'role_module_management' => [
                    'title' => vum()->getLang('role_module_management'),
                    'items' => [
                        'new_module' => [
                            'title' => vum()->getLang('role_new_module')
                        ],
                        'edit_module' => [
                            'title' => vum()->getLang('role_edit_module')
                        ],
                        'save_module' => [
                            'title' => vum()->getLang('role_save_module')
                        ],
                        'delete_module' => [
                            'title' => vum()->getLang('role_delete_module')
                        ],
                        'exec_module' => [
                            'title' => vum()->getLang('role_run_module')
                        ],
                    ]
                ]
            ],
            null,
            [
                'role_template_management' => [
                    'title' => vum()->getLang('role_template_management'),
                    'items' => [
                        'new_template' => [
                            'title' => vum()->getLang('role_create_template')
                        ],
                        'edit_template' => [
                            'title' => vum()->getLang('role_edit_template')
                        ],
                        'save_template' => [
                            'title' => vum()->getLang('role_save_template')
                        ],
                        'delete_template' => [
                            'title' => vum()->getLang('role_delete_template')
                        ],
                    ]
                ]
            ],
            [
                'role_snippet_management' => [
                    'title' => vum()->getLang('role_snippet_management'),
                    'items' => [
                        'new_snippet' => [
                            'title' => vum()->getLang('role_create_snippet')
                        ],
                        'edit_snippet' => [
                            'title' => vum()->getLang('role_edit_snippet')
                        ],
                        'save_snippet' => [
                            'title' => vum()->getLang('role_save_snippet')
                        ],
                        'delete_snippet' => [
                            'title' => vum()->getLang('role_delete_snippet')
                        ],
                    ]
                ]
            ],
            [
                'role_chunk_management' => [
                    'title' => vum()->getLang('role_chunk_management'),
                    'items' => [
                        'new_chunk' => [
                            'title' => vum()->getLang('role_create_chunk')
                        ],
                        'edit_chunk' => [
                            'title' => vum()->getLang('role_edit_chunk')
                        ],
                        'save_chunk' => [
                            'title' => vum()->getLang('role_save_chunk')
                        ],
                        'delete_chunk' => [
                            'title' => vum()->getLang('role_delete_chunk')
                        ],
                    ]
                ]
            ],
            [
                'role_plugin_management' => [
                    'title' => vum()->getLang('role_plugin_management'),
                    'items' => [
                        'new_plugin' => [
                            'title' => vum()->getLang('role_create_plugin')
                        ],
                        'edit_plugin' => [
                            'title' => vum()->getLang('role_edit_plugin')
                        ],
                        'save_plugin' => [
                            'title' => vum()->getLang('role_save_plugin')
                        ],
                        'delete_plugin' => [
                            'title' => vum()->getLang('role_delete_plugin')
                        ],
                    ]
                ]
            ],
            null,
            [
                'role_user_management' => [
                    'title' => vum()->getLang('role_user_management'),
                    'items' => [
                        'new_user' => [
                            'title' => vum()->getLang('role_new_user')
                        ],
                        'edit_user' => [
                            'title' => vum()->getLang('role_edit_user')
                        ],
                        'save_user' => [
                            'title' => vum()->getLang('role_save_user')
                        ],
                        'delete_user' => [
                            'title' => vum()->getLang('role_delete_user')
                        ],
                    ]
                ]
            ],
            [
                'role_web_user_management' => [
                    'title' => vum()->getLang('role_web_user_management'),
                    'items' => [
                        'new_web_user' => [
                            'title' => vum()->getLang('role_new_web_user')
                        ],
                        'edit_web_user' => [
                            'title' => vum()->getLang('role_edit_web_user')
                        ],
                        'save_web_user' => [
                            'title' => vum()->getLang('role_save_web_user')
                        ],
                        'delete_web_user' => [
                            'title' => vum()->getLang('role_delete_web_user')
                        ],
                    ]
                ]
            ],
            [
                'role_udperms' => [
                    'title' => vum()->getLang('role_udperms'),
                    'items' => [
                        'access_permissions' => [
                            'title' => vum()->getLang('role_access_persmissions')
                        ],
                        'web_access_permissions' => [
                            'title' => vum()->getLang('role_web_access_persmissions')
                        ],
                    ]
                ]
            ],
            [
                'role_role_management' => [
                    'title' => vum()->getLang('role_role_management'),
                    'items' => [
                        'new_role' => [
                            'title' => vum()->getLang('role_new_role')
                        ],
                        'edit_role' => [
                            'title' => vum()->getLang('role_edit_role')
                        ],
                        'save_role' => [
                            'title' => vum()->getLang('role_save_role')
                        ],
                        'delete_role' => [
                            'title' => vum()->getLang('role_delete_role')
                        ],
                    ]
                ]
            ],
            null,
            [
                'role_eventlog_management' => [
                    'title' => vum()->getLang('role_eventlog_management'),
                    'items' => [
                        'view_eventlog' => [
                            'title' => vum()->getLang('role_view_eventlog')
                        ],
                        'delete_eventlog' => [
                            'title' => vum()->getLang('role_delete_eventlog')
                        ],
                    ]
                ]
            ],
            [
                'role_config_management' => [
                    'title' => vum()->getLang('role_config_management'),
                    'items' => [
                        'logs' => [
                            'title' => vum()->getLang('role_view_logs')
                        ],
                        'settings' => [
                            'title' => vum()->getLang('role_edit_settings')
                        ],
                        'bk_manager' => [
                            'title' => vum()->getLang('role_bk_manager')
                        ],
                        'import_static' => [
                            'title' => vum()->getLang('role_import_static')
                        ],
                        'export_static' => [
                            'title' => vum()->getLang('role_export_static')
                        ],
                        'remove_locks' => [
                            'title' => vum()->getLang('role_remove_locks')
                        ],
                        'display_locks' => [
                            'title' => vum()->getLang('role_display_locks')
                        ],
                    ]
                ]
            ],
        ];

        $this->setMeta($meta);

        return $model;
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\UserRoles
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function update(array $params = []): UserRoles
    {
        $this->hasPermissionsUpdate();
        $app = evolutionCMS();

        $model = $this->read($params)
            ->hydrate($params);

        if (!$model->id) {
            throw new NotFoundException();
        }

        if ($model->id == 1) {
            throw new NotFoundException();
        }

        $app->db->update($model->toData(), $app->getFullTableName('user_roles'), 'id=' . $model->id);

        return $this->read($model->toArray());
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\UserRoles
     * @throws \VueManager\Exceptions\NotFoundException
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function delete(array $params = []): UserRoles
    {
        $this->hasPermissionsDelete();
        $app = evolutionCMS();

        $model = $this->read($params);

        if (!empty($model->id)) {
            if ($model->id == 1) {
                throw new NotFoundException();
            }

            $app->db->delete($app->getFullTableName('user_roles'), 'id=' . $model->id);

            return $model;
        }

        throw new NotFoundException();
    }

    /**
     * @param array $params
     * @return \VueManager\Models\v1\UserRoles
     * @throws \VueManager\Exceptions\PermissionException
     */
    public function copy(array $params = []): UserRoles
    {
        return $this->create($this->read($params)
            ->toArray());
    }

    /**
     * @param array $params
     * @return UserRoles[]|array
     */
    public function list(array $params = []): array
    {
        $app = evolutionCMS();
        $data = $app->db->makeArray($app->db->select('name, id, description', $app->getFullTableName('user_roles'), '', 'id'));

        $this->setMeta([]);

        return $data;
    }
}
