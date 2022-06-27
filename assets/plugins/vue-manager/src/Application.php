<?php
/**
 * @author 64j
 * @license MIT
 */

declare(strict_types=1);

namespace VueManager;

class Application
{
    /**
     * @var array
     */
    protected array $body = [];

    /**
     * @var array
     */
    protected array $lang = [];

    /**
     * @var int
     */
    protected int $page;

    /**
     * @var int
     */
    protected int $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @param array $body
     */
    public function __construct(array $body = [])
    {
        $modx = evolutionCMS();

        $this->body = $body;

        $manager_language = $modx->getConfig('manager_language');
        if (file_exists($file = MODX_MANAGER_PATH . 'includes/lang/' . $manager_language . '.inc.php')) {
            include_once $file;
        } else {
            include_once MODX_MANAGER_PATH . 'includes/lang/english.inc.php';
        }

        $this->lang = $_lang;

        $this->page = intval($_GET['p'] ?? 0);
        $this->limit = intval($modx->getConfig('number_of_results'));
        $this->limit = 1;
        $this->offset = $this->page > 1 ? ($this->page - 1) * $this->limit : 0;
    }

    /**
     * @param array $user
     * @return array
     */
    public function getSettings(array $user): array
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

        $data['permissions'] = $modx->db->getRow($modx->db->select('*', $modx->getFullTableName('user_roles'), "id='{$user['role']}'"));

        $data['user'] = $user;

        $rs = $modx->db->makeArray($modx->db->select('*', '[+prefix+]categories'));
        $categories = [
            0 => [
                'id' => 0,
                'category' => $this->lang['no_category'],
                'rank' => 0
            ]
        ];
        foreach ($rs as $r) {
            $categories[$r['id']] = $r;
        }

        $data['categories'] = $categories;

        return $data;
    }

    /**
     * @return array
     */
    public function getTemplates(): array
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
                IF(t.category=0,"' . $this->lang['no_category'] . '",c.category) AS category_name
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

        return $data;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getTemplate(int $id): array
    {
        $modx = evolutionCMS();
        $data = [];

        if ($id) {
            $data = $modx->db->getRow($modx->db->select('*', '[+prefix+]site_templates', 'id=' . $id));
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
            $data['id'] = 3;
        }

        /*if (!empty($data)) {
          $meta['events'] = [
            'OnTempFormPrerender' => $modx->invokeEvent('OnTempFormPrerender', ['id' => $id]),
            'OnTempFormRender' => $modx->invokeEvent('OnTempFormRender', ['id' => $id]),
          ];
        }*/

        return $data;
    }

    /**
     * @return array
     */
    public function getTvs(): array
    {
        $modx = evolutionCMS();
        $data = [];

        $rs = $modx->db->makeArray(
            $modx->db->query('
                SELECT
                t.id,
                t.name,
                t.description,
                t.locked,
                t.category,
                IF(t.category=0,"' . $this->lang['no_category'] . '",c.category) AS category_name
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

        return $data;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getTv(int $id): array
    {
        $modx = evolutionCMS();
        $data = [];

        if ($id) {
            $data = $modx->db->getRow($modx->db->select('*', '[+prefix+]site_tmplvars', 'id=' . $id));
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getChunks(): array
    {
        $modx = evolutionCMS();
        $data = [];

        $rs = $modx->db->makeArray(
            $modx->db->query('
                SELECT
                t.id,
                t.name,
                t.description,
                t.locked,
                t.disabled,
                t.category,
                IF(t.category=0,"' . $this->lang['no_category'] . '",c.category) AS category_name
                FROM ' . $modx->getFullTableName('site_htmlsnippets') . ' t
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

        return $data;
    }

    /**
     * @param $id
     * @return array
     */
    public function getChunk($id): array
    {
        $modx = evolutionCMS();
        $data = [];

        if ($id) {
            $data = $modx->db->getRow($modx->db->select('*', '[+prefix+]site_htmlsnippets', 'id=' . $id));
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getSnippets(): array
    {
        $modx = evolutionCMS();
        $data = [];

        $rs = $modx->db->makeArray(
            $modx->db->query('
                SELECT
                t.id,
                t.name,
                t.description,
                t.locked,
                t.disabled,
                t.category,
                IF(t.category=0,"' . $this->lang['no_category'] . '",c.category) AS category_name
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

        return $data;
    }

    /**
     * @param $id
     * @return array
     */
    public function getSnippet($id): array
    {
        $modx = evolutionCMS();
        $data = [];

        if ($id) {
            $data = $modx->db->getRow($modx->db->select('*', '[+prefix+]site_snippets', 'id=' . $id));
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getPlugins(): array
    {
        $modx = evolutionCMS();
        $data = [];

        $rs = $modx->db->makeArray(
            $modx->db->query('
                SELECT
                t.id,
                t.name,
                t.description,
                t.locked,
                t.disabled,
                t.category,
                IF(t.category=0,"' . $this->lang['no_category'] . '",c.category) AS category_name
                FROM ' . $modx->getFullTableName('site_plugins') . ' t
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

        return $data;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getPlugin(int $id): array
    {
        $modx = evolutionCMS();
        $data = [];

        if ($id) {
            $data = $modx->db->getRow($modx->db->select('*', '[+prefix+]site_plugins', 'id=' . $id));
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        $modx = evolutionCMS();
        $data = [];

        $rs = $modx->db->makeArray(
            $modx->db->query('
                SELECT
                t.id,
                t.name,
                t.description,
                t.locked,
                t.disabled,
                t.category,
                IF(t.category=0,"' . $this->lang['no_category'] . '",c.category) AS category_name
                FROM ' . $modx->getFullTableName('site_modules') . ' t
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

        return $data;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getModule(int $id): array
    {
        $modx = evolutionCMS();
        $data = [];

        if ($id) {
            $data = $modx->db->getRow($modx->db->select('*', '[+prefix+]site_modules', 'id=' . $id));
        }

        return $data;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getModuleExec(int $id): array
    {
        $data = [];

        if ($id) {
            $data['title'] = 'Module id: ' . $id;
            $data['result'] = $id . ' processors/execute_module.processor.php';
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        $modx = evolutionCMS();
        $data = [];
        $meta = [];

        $meta['pagination'] = [
            'total' => $modx->db->getRow($modx->db->query('SELECT COUNT(*) as cnt FROM ' . $modx->getFullTableName('user_attributes')))['cnt'],
            'limit' => $this->limit
        ];

        if ($this->offset > $meta['pagination']['total']) {
            $this->offset = $meta['pagination']['total'] > $this->limit ? $meta['pagination']['total'] - $this->limit : 0;
        }

        $data = $modx->db->makeArray($modx->db->query('
            SELECT
            ua.internalKey as id,
            mu.username,
            ua.fullname,
            ur.name as role,
            ua.email,
            DATE_FORMAT(FROM_UNIXTIME(ua.lastlogin), "%d-%m-%Y %H:%i") AS lastlogin,
            ua.logincount,
            ua.blocked
            FROM ' . $modx->getFullTableName('user_attributes') . ' ua
            LEFT JOIN ' . $modx->getFullTableName('manager_users') . ' mu ON mu.id=ua.internalKey
            LEFT JOIN ' . $modx->getFullTableName('user_roles') . ' ur ON ur.id=ua.role
            LIMIT ' . $this->limit . ' OFFSET ' . $this->offset . '
        '));

        return [$data, $meta];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getUser(int $id): array
    {
        $modx = evolutionCMS();
        $data = [];

        if ($id) {

        }

        return $data;
    }

    /**
     * @return array
     */
    public function getWebUsers(): array
    {
        $modx = evolutionCMS();
        $data = [];

        $meta['pagination'] = [
            'total' => $modx->db->getRow($modx->db->query('SELECT COUNT(*) as cnt FROM ' . $modx->getFullTableName('web_user_attributes')))['cnt'],
            'limit' => $this->limit
        ];

        if ($this->offset > $meta['pagination']['total']) {
            $this->offset = $meta['pagination']['total'] > $this->limit ? $meta['pagination']['total'] - $this->limit : 0;
        }

        $data = $modx->db->makeArray($modx->db->query('
            SELECT
            ua.internalKey as id,
            mu.username,
            ua.fullname,
            ua.email,
            DATE_FORMAT(FROM_UNIXTIME(ua.lastlogin), "%d-%m-%Y %H:%i") AS lastlogin,
            ua.logincount,
            ua.blocked
            FROM ' . $modx->getFullTableName('web_user_attributes') . ' ua
            LEFT JOIN ' . $modx->getFullTableName('web_users') . ' mu ON mu.id=ua.internalKey
            LIMIT ' . $this->limit . ' OFFSET ' . $this->offset . '
        '));

        return $data;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getWebUser(int $id): array
    {
        $modx = evolutionCMS();
        $data = [];

        if ($id) {

        }

        return $data;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $modx = evolutionCMS();

        $data = $modx->db->makeArray($modx->db->select('name, id, description', $modx->getFullTableName('user_roles'), '', 'name'));
        $meta = [];

        return [$data, $meta];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getRole(int $id): array
    {
        $modx = evolutionCMS();
        $data = [];
        $meta = [];

        if ($id) {
            $data = $modx->db->getRow($modx->db->select('*', $modx->getFullTableName('user_roles'), 'id=' . $id));
        }

        $meta['categories'] = [
            [
                'page_data_general' => [
                    'lang' => 'page_data_general',
                    'items' => [
                        'frames' => [
                            'lang' => 'role_frames',
                            'disabled' => true
                        ],
                        'home' => [
                            'lang' => 'role_home',
                            'disabled' => true
                        ],
                        'messages' => [
                            'lang' => 'role_messages'
                        ],
                        'logout' => [
                            'lang' => 'role_logout',
                            'disabled' => true
                        ],
                        'help' => [
                            'lang' => 'role_help'
                        ],
                        'action_ok' => [
                            'lang' => 'role_actionok',
                            'disabled' => true
                        ],
                        'error_dialog' => [
                            'lang' => 'role_errors',
                            'disabled' => true
                        ],
                        'about' => [
                            'lang' => 'role_about',
                            'disabled' => true
                        ],
                        'credits' => [
                            'lang' => 'role_credits',
                            'disabled' => true
                        ],
                        'change_password' => [
                            'lang' => 'role_change_password'
                        ],
                        'save_password' => [
                            'lang' => 'role_save_password'
                        ],
                    ]
                ]
            ],
            [
                'role_content_management' => [
                    'lang' => 'role_content_management',
                    'items' => [
                        'view_document' => [
                            'lang' => 'role_view_docdata',
                            'disabled' => true
                        ],
                        'new_document' => [
                            'lang' => 'role_create_doc'
                        ],
                        'edit_document' => [
                            'lang' => 'role_edit_doc'
                        ],
                        'change_resourcetype' => [
                            'lang' => 'role_change_resourcetype'
                        ],
                        'save_document' => [
                            'lang' => 'role_save_doc'
                        ],
                        'publish_document' => [
                            'lang' => 'role_publish_doc'
                        ],
                        'delete_document' => [
                            'lang' => 'role_delete_doc'
                        ],
                        'empty_trash' => [
                            'lang' => 'role_empty_trash'
                        ],
                        'empty_cache' => [
                            'lang' => 'role_cache_refresh'
                        ],
                        'view_unpublished' => [
                            'lang' => 'role_view_unpublished'
                        ]
                    ]
                ]
            ],
            [
                'role_file_management' => [
                    'lang' => 'role_file_management',
                    'items' => [
                        'file_manager' => [
                            'lang' => 'role_file_manager'
                        ],
                        'assets_files' => [
                            'lang' => 'role_assets_files'
                        ],
                        'assets_images' => [
                            'lang' => 'role_assets_images'
                        ],
                    ]
                ],
                'category_management' => [
                    'lang' => 'category_management',
                    'items' => [
                        'category_manager' => [
                            'lang' => 'role_category_manager'
                        ],
                    ]
                ]
            ],
            [
                'role_module_management' => [
                    'lang' => 'role_module_management',
                    'items' => [
                        'new_module' => [
                            'lang' => 'role_new_module'
                        ],
                        'edit_module' => [
                            'lang' => 'role_edit_module'
                        ],
                        'save_module' => [
                            'lang' => 'role_save_module'
                        ],
                        'delete_module' => [
                            'lang' => 'role_delete_module'
                        ],
                        'exec_module' => [
                            'lang' => 'role_run_module'
                        ],
                    ]
                ]
            ],
            null,
            [
                'role_template_management' => [
                    'lang' => 'role_template_management',
                    'items' => [
                        'new_template' => [
                            'lang' => 'role_create_template'
                        ],
                        'edit_template' => [
                            'lang' => 'role_edit_template'
                        ],
                        'save_template' => [
                            'lang' => 'role_save_template'
                        ],
                        'delete_template' => [
                            'lang' => 'role_delete_template'
                        ],
                    ]
                ]
            ],
            [
                'role_snippet_management' => [
                    'lang' => 'role_snippet_management',
                    'items' => [
                        'new_snippet' => [
                            'lang' => 'role_create_snippet'
                        ],
                        'edit_snippet' => [
                            'lang' => 'role_edit_snippet'
                        ],
                        'save_snippet' => [
                            'lang' => 'role_save_snippet'
                        ],
                        'delete_snippet' => [
                            'lang' => 'role_delete_snippet'
                        ],
                    ]
                ]
            ],
            [
                'role_chunk_management' => [
                    'lang' => 'role_chunk_management',
                    'items' => [
                        'new_chunk' => [
                            'lang' => 'role_create_chunk'
                        ],
                        'edit_chunk' => [
                            'lang' => 'role_edit_chunk'
                        ],
                        'save_chunk' => [
                            'lang' => 'role_save_chunk'
                        ],
                        'delete_chunk' => [
                            'lang' => 'role_delete_chunk'
                        ],
                    ]
                ]
            ],
            [
                'role_plugin_management' => [
                    'lang' => 'role_plugin_management',
                    'items' => [
                        'new_plugin' => [
                            'lang' => 'role_create_plugin'
                        ],
                        'edit_plugin' => [
                            'lang' => 'role_edit_plugin'
                        ],
                        'save_plugin' => [
                            'lang' => 'role_save_plugin'
                        ],
                        'delete_plugin' => [
                            'lang' => 'role_delete_plugin'
                        ],
                    ]
                ]
            ],
            null,
            [
                'role_user_management' => [
                    'lang' => 'role_user_management',
                    'items' => [
                        'new_user' => [
                            'lang' => 'role_new_user'
                        ],
                        'edit_user' => [
                            'lang' => 'role_edit_user'
                        ],
                        'save_user' => [
                            'lang' => 'role_save_user'
                        ],
                        'delete_user' => [
                            'lang' => 'role_delete_user'
                        ],
                    ]
                ]
            ],
            [
                'role_web_user_management' => [
                    'lang' => 'role_web_user_management',
                    'items' => [
                        'new_web_user' => [
                            'lang' => 'role_new_web_user'
                        ],
                        'edit_web_user' => [
                            'lang' => 'role_edit_web_user'
                        ],
                        'save_web_user' => [
                            'lang' => 'role_save_web_user'
                        ],
                        'delete_web_user' => [
                            'lang' => 'role_delete_web_user'
                        ],
                    ]
                ]
            ],
            [
                'role_udperms' => [
                    'lang' => 'role_udperms',
                    'items' => [
                        'access_permissions' => [
                            'lang' => 'role_access_persmissions'
                        ],
                        'web_access_permissions' => [
                            'lang' => 'role_web_access_persmissions'
                        ],
                    ]
                ]
            ],
            [
                'role_role_management' => [
                    'lang' => 'role_role_management',
                    'items' => [
                        'new_role' => [
                            'lang' => 'role_new_role'
                        ],
                        'edit_role' => [
                            'lang' => 'role_edit_role'
                        ],
                        'save_role' => [
                            'lang' => 'role_save_role'
                        ],
                        'delete_role' => [
                            'lang' => 'role_delete_role'
                        ],
                    ]
                ]
            ],
            null,
            [
                'role_eventlog_management' => [
                    'lang' => 'role_eventlog_management',
                    'items' => [
                        'view_eventlog' => [
                            'lang' => 'role_view_eventlog'
                        ],
                        'delete_eventlog' => [
                            'lang' => 'role_delete_eventlog'
                        ],
                    ]
                ]
            ],
            [
                'role_config_management' => [
                    'lang' => 'role_config_management',
                    'items' => [
                        'logs' => [
                            'lang' => 'role_view_logs'
                        ],
                        'settings' => [
                            'lang' => 'role_edit_settings'
                        ],
                        'bk_manager' => [
                            'lang' => 'role_bk_manager'
                        ],
                        'import_static' => [
                            'lang' => 'role_import_static'
                        ],
                        'export_static' => [
                            'lang' => 'role_export_static'
                        ],
                        'remove_locks' => [
                            'lang' => 'role_remove_locks'
                        ],
                        'display_locks' => [
                            'lang' => 'role_display_locks'
                        ],
                    ]
                ]
            ],
        ];

        return [$data, $meta];
    }
}
