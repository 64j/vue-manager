<?php

declare(strict_types=1);

namespace VueManager\Services\v1;

use VueManager\Traits\ServiceMetaTrait;

class TreeService
{
    use ServiceMetaTrait;

    /**
     * @var array
     */
    protected array $opened = [];

    /**
     * @param array $params
     * @return array
     */
    public function get(array $params = []): array
    {
        $app = evolutionCMS();

        if (isset($params['opened'])) {
            $_SESSION['openedArray'] = $params['opened'];
        }

        $expandAll = (int) ($params['expandAll'] ?? 0);
        $indent = (int) ($params['indent'] ?? 0);
        $parent = (int) ($params['parent'] ?? 0);
        $hereid = (int) ($params['id'] ?? 0);

        if (isset($params['showonlyfolders'])) {
            $_SESSION['tree_show_only_folders'] = $params['showonlyfolders'];
        }

        // setup sorting
        $sortParams = [
            'tree_sortby',
            'tree_sortdir',
            'tree_nodename'
        ];

        foreach ($sortParams as $value) {
            if (isset($params[$value])) {
                $_SESSION[$value] = $params[$value];
                $app->manager->saveLastUserSetting($value, $params[$value]);
            }
        }

        if (empty($_SESSION['tree_nodename'])) {
            $_SESSION['tree_nodename'] = 'default';
        }

        if (isset($_SESSION['openedArray'])) {
            $this->opened = array_filter(array_map('intval', explode('|', $_SESSION['openedArray'])));
        }

        return $this->makeHTML($indent, $parent, $expandAll, $hereid);
    }

    /**
     * @param $indent
     * @param $parent
     * @param $expandAll
     * @param $hereid
     * @return array
     */
    protected function makeHTML($indent, $parent, $expandAll, $hereid = ''): array
    {
        global $opened2, $closed2;

        $app = evolutionCMS();
        $_lang = vum()->getLang();
        $icons = $this->getIconInfo();

        $output = [];

        // setup spacer
        $level = 0;
        $spacer = '<span class="indent">';
        for ($i = 2; $i <= $indent; $i++) {
            $spacer .= '<i></i>';
            $level++;
        }
        $spacer .= '</span>';

        // manage order-by
        if (!isset($_SESSION['tree_sortby']) && !isset($_SESSION['tree_sortdir'])) {
            // This is the first startup, set default sort order
            $_SESSION['tree_sortby'] = 'menuindex';
            $_SESSION['tree_sortdir'] = 'ASC';
        }

        switch ($_SESSION['tree_sortby']) {
            case 'createdon':
            case 'editedon':
            case 'publishedon':
            case 'pub_date':
            case 'unpub_date':
                $sortby = sprintf('CASE WHEN %s IS NULL THEN 1 ELSE 0 END, %s', 'sc.' . $_SESSION['tree_sortby'], 'sc.' . $_SESSION['tree_sortby']);
                break;
            default:
                $sortby = 'sc.' . $_SESSION['tree_sortby'];
        };

        $orderby = $app->db->escape($sortby . ' ' . $_SESSION['tree_sortdir']);

        // Folder sorting gets special setup ;) Add menuindex and pagetitle
        if ($_SESSION['tree_sortby'] == 'isfolder') {
            $orderby .= ', menuindex ASC, pagetitle';
        }

        $tblsc = $app->getFullTableName('site_content');
        $tbldg = $app->getFullTableName('document_groups');
        $tblst = $app->getFullTableName('site_templates');
        // get document groups for current user
        $docgrp = (isset($_SESSION['mgrDocgroups']) && is_array($_SESSION['mgrDocgroups'])) ? implode(',', $_SESSION['mgrDocgroups']) : '';
        $showProtected = false;
        if (isset ($app->config['tree_show_protected'])) {
            $showProtected = (boolean) $app->config['tree_show_protected'];
        }
        $mgrRole = (isset ($_SESSION['mgrRole']) && (string) $_SESSION['mgrRole'] === '1') ? '1' : '0';
        if (!$showProtected) {
            $access = "AND (1={$mgrRole} OR sc.privatemgr=0" . (!$docgrp ? ')' : " OR dg.document_group IN ({$docgrp}))");
        } else {
            $access = '';
        }
        $docgrp_cond = $docgrp ? "OR dg.document_group IN ({$docgrp})" : '';
        $field = "DISTINCT sc.id, pagetitle, longtitle, menutitle, parent, isfolder, published, pub_date, unpub_date, richtext, searchable, cacheable, deleted, type, template, templatename, menuindex, donthit, hidemenu, alias, contentType, privateweb, privatemgr,
        MAX(IF(1={$mgrRole} OR sc.privatemgr=0 {$docgrp_cond}, 1, 0)) AS hasAccess, GROUP_CONCAT(document_group SEPARATOR ',') AS roles";
        $from = "{$tblsc} AS sc LEFT JOIN {$tbldg} dg on dg.document = sc.id LEFT JOIN {$tblst} st on st.id = sc.template";
        $where = "(parent={$parent}) {$access} GROUP BY sc.id";
        $result = $app->db->select($field, $from, $where, $orderby);
        if ($app->db->getRecordCount($result) == 0) {
            $output[] = sprintf('<div><a class="empty">%s%s&nbsp;<span class="empty">%s</span></a></div>', $spacer, 'tree_deletedpage', $_lang['empty_folder']);
        }

        $nodeNameSource = $_SESSION['tree_nodename'] == 'default' ? $app->config['resource_tree_node_name'] : $_SESSION['tree_nodename'];

        while ($row = $app->db->getRow($result)) {
            $node = [];
            $nodetitle = $this->getNodeTitle($nodeNameSource, $row);
            $nodetitleDisplay = $nodetitle;
            $treeNodeClass = 'node';
            $treeNodeClass .= $row['hasAccess'] == 0 ? ' protected' : '';

            if ($row['deleted'] == 1) {
                $treeNodeClass .= ' deleted';
            } elseif ($row['published'] == 0) {
                $treeNodeClass .= ' unpublished';
            } elseif ($row['hidemenu'] == 1) {
                $treeNodeClass .= ' hidemenu';
            }

            if ($row['id'] == $hereid) {
                $treeNodeClass .= ' current';
            }

            $weblinkDisplay = $row['type'] == 'reference' ? sprintf('&nbsp;%s', 'tree_linkgo') : '';
            $pageIdDisplay = '<small>(' . $row['id'] . ')</small>';

            // Prepare displaying user-locks
            $lockedByUser = '';
            $rowLock = $app->elementIsLocked(7, $row['id'], true);
            if ($rowLock && $app->hasPermission('display_locks')) {
                if ($rowLock['sid'] == $app->sid) {
                    $title = $app->parseText($_lang["lock_element_editing"], array(
                        'element_type' => $_lang["lock_element_type_7"],
                        'lasthit_df' => $rowLock['lasthit_df']
                    ));
                    $lockedByUser = '<span title="' . $title . '" class="editResource">' . 'tree_preview_resource' . '</span>';
                } else {
                    $title = $app->parseText($_lang["lock_element_locked_by"], array(
                        'element_type' => $_lang["lock_element_type_7"],
                        'username' => $rowLock['username'],
                        'lasthit_df' => $rowLock['lasthit_df']
                    ));
                    if ($app->hasPermission('remove_locks')) {
                        $lockedByUser = '<span onclick="modx.tree.unlockElement(7, ' . $row['id'] . ', this);return false;" title="' . $title . '" class="lockedResource">' . 'icons_secured' . '</span>';
                    } else {
                        $lockedByUser = '<span title="' . $title . '" class="lockedResource">' . 'icons_secured' . '</span>';
                    }
                }
            }

            $url = $app->makeUrl($row['id']);

            $title = '';
            if ($this->isDateNode($nodeNameSource)) {
                $title = $_lang['pagetitle'] . ': ' . $row['pagetitle'] . '[+lf+]';
            }
            $title .= $_lang['id'] . ': ' . $row['id'];
            $title .= '[+lf+]' . $_lang['resource_opt_menu_title'] . ': ' . $row['menutitle'];
            $title .= '[+lf+]' . $_lang['resource_opt_menu_index'] . ': ' . $row['menuindex'];
            $title .= '[+lf+]' . $_lang['alias'] . ': ' . (!empty($row['alias']) ? $row['alias'] : '-');
            $title .= '[+lf+]' . $_lang['template'] . ': ' . $row['templatename'];
            $title .= '[+lf+]' . $_lang['publish_date'] . ': ' . $app->toDateFormat($row['pub_date']);
            $title .= '[+lf+]' . $_lang['unpublish_date'] . ': ' . $app->toDateFormat($row['unpub_date']);
            $title .= '[+lf+]' . $_lang['page_data_web_access'] . ': ' . ($row['privateweb'] ? $_lang['private'] : $_lang['public']);
            $title .= '[+lf+]' . $_lang['page_data_mgr_access'] . ': ' . ($row['privatemgr'] ? $_lang['private'] : $_lang['public']);
            $title .= '[+lf+]' . $_lang['resource_opt_richtext'] . ': ' . ($row['richtext'] == 0 ? $_lang['no'] : $_lang['yes']);
            $title .= '[+lf+]' . $_lang['page_data_searchable'] . ': ' . ($row['searchable'] == 0 ? $_lang['no'] : $_lang['yes']);
            $title .= '[+lf+]' . $_lang['page_data_cacheable'] . ': ' . ($row['cacheable'] == 0 ? $_lang['no'] : $_lang['yes']);
            $title = $app->htmlspecialchars($title);
            $title = str_replace('[+lf+]', ' &#13;', $title);   // replace line-breaks with empty space as fall-back

            $data = array(
                'id' => $row['id'],
                'pagetitle' => $row['pagetitle'],
                'longtitle' => $row['longtitle'],
                'menutitle' => $row['menutitle'],
                'parent' => $parent,
                'isfolder' => $row['isfolder'],
                'published' => $row['published'],
                'deleted' => $row['deleted'],
                'type' => $row['type'],
                'menuindex' => $row['menuindex'],
                'donthit' => $row['donthit'],
                'hidemenu' => $row['hidemenu'],
                'alias' => $row['alias'],
                'contenttype' => $row['contentType'],
                'privateweb' => $row['privateweb'],
                'privatemgr' => $row['privatemgr'],
                'hasAccess' => $row['hasAccess'],
                'template' => $row['template'],
                'nodetitle' => $nodetitle,
                'url' => $url,
                'title' => $title,
                'nodetitleDisplay' => $nodetitleDisplay,
                'weblinkDisplay' => $weblinkDisplay,
                'pageIdDisplay' => $pageIdDisplay,
                'lockedByUser' => $lockedByUser,
                'treeNodeClass' => $treeNodeClass,
                'treeNodeSelected' => $row['id'] == $hereid ? ' treeNodeSelected' : '',
                'tree_page_click' => $app->config['tree_page_click'],
                'showChildren' => 1,
                'openFolder' => 1,
                'contextmenu' => '',
                'tree_minusnode' => 'tree_minusnode',
                'tree_plusnode' => 'tree_plusnode',
                'spacer' => $spacer,
                'subMenuState' => '',
                'level' => $level,
                'isPrivate' => 0,
                'roles' => $row['roles'] ?: ''
            );

            $ph = $data;
            $ph['nodetitle_esc'] = addslashes($nodetitle);
            $ph['indent'] = $indent + 1;
            $ph['expandAll'] = $expandAll;
            $ph['isPrivate'] = ($row['privateweb'] || $row['privatemgr']) ? 1 : 0;

            if (!$row['isfolder']) {
                switch ($row['id']) {
                    case $app->config['site_start']            :
                        $icon = 'tree_page_home';
                        break;
                    case $app->config['error_page']            :
                        $icon = 'tree_page_404';
                        break;
                    case $app->config['site_unavailable_page'] :
                        $icon = 'tree_page_hourglass';
                        break;
                    case $app->config['unauthorized_page']     :
                        $icon = 'tree_page_info';
                        break;
                    default:
                        if (isset($icons[$row['contentType']])) {
                            $icon = $icons[$row['contentType']];
                        } else {
                            $icon = 'tree_page';
                        }
                }
                $ph['icon'] = $icon;

                // invoke OnManagerNodePrerender event
                $prenode = $app->invokeEvent("OnManagerNodePrerender", array('ph' => $ph));

                if (is_array($prenode)) {
                    $phnew = array();
                    foreach ($prenode as $pnode) {
                        $phnew = array_merge($phnew, unserialize($pnode));
                    }
                    $ph = (count($phnew) > 0) ? $phnew : $ph;
                }

                if ($ph['contextmenu']) {
                    $ph['contextmenu'] = ' data-contextmenu="' . $this->_htmlentities($ph['contextmenu']) . '"';
                }

                if (!empty($_SESSION['tree_show_only_folders'])) {
                    if ($row['parent'] == 0) {
                        $node = $ph;
                    }
                } else {
                    $node = $ph;
                }

            } else {
                $ph['icon_folder_open'] = 'tree_folderopen_new';
                $ph['icon_folder_close'] = 'tree_folder_new';

                if (!empty($_SESSION['tree_show_only_folders'])) {
                    $checkFolders = $this->checkIsFolder($row['id'], 1) ? 1 : 0; // folders
                    $checkDocs = $this->checkIsFolder($row['id'], 0) ? 1 : 0; // no folders
                    $ph['tree_page_click'] = 3;

                    // expandAll: two type for partial expansion
                    if ($expandAll == 1 || ($expandAll == 2 && in_array($row['id'], $this->opened))) {
                        if ($expandAll == 1) {
                            $opened2[] = $row['id'];
                        }

                        $ph['icon'] = $ph['icon_folder_open'];
                        $ph['icon_node_toggle'] = $ph['tree_minusnode'];
                        $ph['node_toggle'] = 1;
                        $ph['subMenuState'] = ' open';

                        if (($checkDocs && !$checkFolders) || (!$checkDocs && !$checkFolders)) {
                            $ph['showChildren'] = 1;
                            $ph['icon_node_toggle'] = '';
                            $ph['icon'] = $ph['icon_folder_close'];
                        } elseif (!$checkDocs && $checkFolders) {
                            $ph['showChildren'] = 0;
                            $ph['openFolder'] = 2;
                        } else {
                            $ph['openFolder'] = 2;
                        }

                        // invoke OnManagerNodePrerender event
                        $prenode = $app->invokeEvent("OnManagerNodePrerender", array(
                            'ph' => $ph,
                            'opened' => '1'
                        ));

                        if (is_array($prenode)) {
                            $phnew = array();
                            foreach ($prenode as $pnode) {
                                $phnew = array_merge($phnew, unserialize($pnode));
                            }
                            $ph = (count($phnew) > 0) ? $phnew : $ph;
                        }

                        if ($ph['contextmenu']) {
                            $ph['contextmenu'] = ' data-contextmenu="' . $this->_htmlentities($ph['contextmenu']) . '"';
                        }

                        $node = $ph;
                        if ($checkFolders) {
                            $node['children'] = $this->makeHTML($indent + 1, $row['id'], $expandAll, $hereid);
                        }
                    } else {
                        $closed2[] = $row['id'];
                        $ph['icon'] = $ph['icon_folder_close'];
                        $ph['icon_node_toggle'] = $ph['tree_plusnode'];
                        $ph['node_toggle'] = 0;

                        if (($checkDocs && !$checkFolders) || (!$checkDocs && !$checkFolders)) {
                            $ph['showChildren'] = 1;
                            $ph['icon_node_toggle'] = '';
                        } elseif (!$checkDocs && $checkFolders) {
                            $ph['showChildren'] = 0;
                            $ph['openFolder'] = 2;
                        } else {
                            $ph['openFolder'] = 2;
                        }

                        // invoke OnManagerNodePrerender event
                        $prenode = $app->invokeEvent("OnManagerNodePrerender", array(
                            'ph' => $ph,
                            'opened' => '0'
                        ));

                        if (is_array($prenode)) {
                            $phnew = array();
                            foreach ($prenode as $pnode) {
                                $phnew = array_merge($phnew, unserialize($pnode));
                            }
                            $ph = (count($phnew) > 0) ? $phnew : $ph;
                        }

                        if ($ph['contextmenu']) {
                            $ph['contextmenu'] = ' data-contextmenu="' . $this->_htmlentities($ph['contextmenu']) . '"';
                        }

                        $node = $ph;
                    }
                } else {
                    // expandAll: two type for partial expansion
                    if ($expandAll == 1 || ($expandAll == 2 && in_array($row['id'], $this->opened))) {
                        if ($expandAll == 1) {
                            $opened2[] = $row['id'];
                        }

                        $ph['icon'] = $ph['icon_folder_open'];
                        $ph['icon_node_toggle'] = $ph['tree_minusnode'];
                        $ph['node_toggle'] = 1;
                        $ph['subMenuState'] = ' open';

                        if ($ph['donthit'] == 1) {
                            $ph['tree_page_click'] = 3;
                            $ph['icon_node_toggle'] = '';
                            $ph['icon'] = $ph['icon_folder_close'];
                            $ph['showChildren'] = 0;
                        }

                        // invoke OnManagerNodePrerender event
                        $prenode = $app->invokeEvent("OnManagerNodePrerender", array(
                            'ph' => $ph,
                            'opened' => '1'
                        ));

                        if (is_array($prenode)) {
                            $phnew = array();
                            foreach ($prenode as $pnode) {
                                $phnew = array_merge($phnew, unserialize($pnode));
                            }
                            $ph = (count($phnew) > 0) ? $phnew : $ph;
                            if ($ph['showChildren'] == 0) {
                                unset($opened2[$row['id']]);
                                $ph['node_toggle'] = 0;
                                $ph['subMenuState'] = '';
                            }
                        }

                        if ($ph['showChildren'] == 0) {
                            $ph['icon_node_toggle'] = '';
                            $ph['donthit'] = 1;
                            $ph['icon'] = $ph['icon_folder_close'];
                        }

                        if ($ph['contextmenu']) {
                            $ph['contextmenu'] = ' data-contextmenu="' . $this->_htmlentities($ph['contextmenu']) . '"';
                        }

                        $node = $ph;
                        if ($ph['donthit'] == 0) {
                            $node['children'] = $this->makeHTML($indent + 1, $row['id'], $expandAll, $hereid);
                        }
                    } else {
                        $closed2[] = $row['id'];
                        $ph['icon'] = $ph['icon_folder_close'];
                        $ph['icon_node_toggle'] = $ph['tree_plusnode'];
                        $ph['node_toggle'] = 0;

                        if ($ph['donthit'] == 1) {
                            $ph['tree_page_click'] = 3;
                            $ph['icon_node_toggle'] = '';
                            $ph['icon'] = $ph['icon_folder_close'];
                            $ph['showChildren'] = 0;
                        }

                        // invoke OnManagerNodePrerender event
                        $prenode = $app->invokeEvent("OnManagerNodePrerender", array(
                            'ph' => $ph,
                            'opened' => '0'
                        ));

                        if (is_array($prenode)) {
                            $phnew = array();
                            foreach ($prenode as $pnode) {
                                $phnew = array_merge($phnew, unserialize($pnode));
                            }
                            $ph = (count($phnew) > 0) ? $phnew : $ph;
                        }

                        if ($ph['showChildren'] == 0) {
                            $ph['icon_node_toggle'] = '';
                            $ph['donthit'] = 1;
                            $ph['icon'] = $ph['icon_folder_close'];
                        }

                        if ($ph['contextmenu']) {
                            $ph['contextmenu'] = ' data-contextmenu="' . $this->_htmlentities($ph['contextmenu']) . '"';
                        }

                        $node = $ph;
                    }
                }
            }

            // invoke OnManagerNodeRender event
            $data['node'] = $node;
            $evtOut = $app->invokeEvent('OnManagerNodeRender', $data);
            if (is_array($evtOut)) {
                $evtOut = implode("\n", $evtOut);
            }
            if ($evtOut != '') {
                $node = $evtOut;
            }

            $output[] = $node;
        }

        return $output;
    }

    /**
     * @return array
     */
    protected function getIconInfo(): array
    {
        return [
            'text/html' => 'tree_page_html',
            'text/plain' => 'tree_page',
            'text/xml' => 'tree_page_xml',
            'text/css' => 'tree_page_css',
            'text/javascript' => 'tree_page_js',
            'application/rss+xml' => 'tree_page_rss',
            'application/pdf' => 'tree_page_pdf',
            'application/vnd.ms-word' => 'tree_page_word',
            'application/vnd.ms-excel' => 'tree_page_excel',
            'image/gif' => 'tree_page_gif',
            'image/jpg' => 'tree_page_jpg',
            'image/png' => 'tree_page_png'
        ];
    }

    /**
     * @param string $nodeNameSource
     * @param array $row
     * @return string
     */
    protected function getNodeTitle(string $nodeNameSource, array $row): string
    {
        $app = evolutionCMS();

        switch ($nodeNameSource) {
            case 'menutitle':
                $title = $row['menutitle'] ?: $row['pagetitle'];
                break;
            case 'alias':
                $title = $row['alias'] ?: $row['id'];
                if (strpos($row['alias'], '.') === false) {
                    if ($row['isfolder'] != 1 || $app->config['make_folders'] != 1) {
                        $title .= $app->config['friendly_url_suffix'];
                    }
                }
                $title = $app->config['friendly_url_prefix'] . $title;
                break;
            case 'pagetitle':
                $title = $row['pagetitle'];
                break;
            case 'longtitle':
                $title = $row['longtitle'] ?: $row['pagetitle'];
                break;
            case 'createdon':
            case 'editedon':
            case 'publishedon':
            case 'pub_date':
            case 'unpub_date':
                $doc = $app->getDocumentObject('id', $row['id']);
                $date = $doc[$nodeNameSource];
                if (!empty($date)) {
                    $title = $app->toDateFormat($date);
                } else {
                    $title = '- - -';
                }
                break;
            default:
                $title = $row['pagetitle'];
        }

        return $app->htmlspecialchars(str_replace(array(
            "\r\n",
            "\n",
            "\r"
        ), ' ', $title), ENT_COMPAT);
    }

    /**
     * @return string
     */
    protected function getTplFolderNodeNotChildren(): string
    {
        return '<div id="node[+id+]"><a class="[+treeNodeClass+]"
        onclick="modx.tree.treeAction(event,[+id+]);"
        oncontextmenu="modx.tree.showPopup(event,[+id+],\'[+nodetitle_esc+]\');"
        data-id="[+id+]"
        data-title-esc="[+nodetitle_esc+]"
        data-published="[+published+]"
        data-deleted="[+deleted+]"
        data-isfolder="[+isfolder+]"
        data-href="[+url+]"
        data-private="[+isPrivate+]"
        data-roles="[+roles+]"
        data-level="[+level+]"
        data-icon-expanded="[+tree_plusnode+]"
        data-icon-collapsed="[+tree_minusnode+]"
        data-icon-folder-open="[+icon_folder_open+]"
        data-icon-folder-close="[+icon_folder_close+]"
        data-treepageclick="[+tree_page_click+]"
        data-showchildren="[+showChildren+]"
        data-openfolder="[+openFolder+]"
        data-indent="[+indent+]"
        data-expandall="[+expandAll+]"
        [+contextmenu+]
        >[+spacer+]<span
        class="icon"
        onclick="modx.tree.showPopup(event,[+id+],\'[+nodetitle_esc+]\');return false;"
        oncontextmenu="this.onclick(event);return false;"
        >[+icon+]</span>[+lockedByUser+]<span
        class="title"
        title="[+title+]">[+nodetitleDisplay+][+weblinkDisplay+]</span>[+pageIdDisplay+]</a><div>';
    }

    /**
     * @param int $parent
     * @param int $isfolder
     * @return int
     */
    protected function checkIsFolder(int $parent = 0, int $isfolder = 1): int
    {
        $app = evolutionCMS();

        return (int) $app->db->getValue(
            $app->db->query(
                'SELECT count(*) FROM ' . $app->getFullTableName('site_content') . ' WHERE parent=' . $parent . ' AND isfolder=' . $isfolder
            )
        );
    }

    /**
     * @param mixed $array
     * @return string
     */
    protected function _htmlentities($array): string
    {
        $app = evolutionCMS();
        $array = json_encode($array, JSON_UNESCAPED_UNICODE);

        return htmlentities($array, ENT_COMPAT, $app->config['modx_charset']);
    }

    /**
     * @return string
     */
    protected function getTplSingleNode(): string
    {
        return '<div id="node[+id+]"><a class="[+treeNodeClass+]"
        onclick="modx.tree.treeAction(event,[+id+]);"
        oncontextmenu="modx.tree.showPopup(event,[+id+],\'[+nodetitle_esc+]\');"
        data-id="[+id+]"
        data-title-esc="[+nodetitle_esc+]"
        data-published="[+published+]"
        data-deleted="[+deleted+]"
        data-isfolder="[+isfolder+]"
        data-href="[+url+]"
        data-private="[+isPrivate+]"
        data-roles="[+roles+]"
        data-level="[+level+]"
        data-treepageclick="[+tree_page_click+]"
        [+contextmenu+]
        >[+spacer+]<span
        class="icon"
        onclick="modx.tree.showPopup(event,[+id+],\'[+nodetitle_esc+]\');return false;"
        oncontextmenu="this.onclick(event);return false;"
        >[+icon+]</span>[+lockedByUser+]<span
        class="title"
        title="[+title+]">[+nodetitleDisplay+][+weblinkDisplay+]</span>[+pageIdDisplay+]</a></div>';
    }

    /**
     * @return string
     */
    protected function getTplFolderNode(): string
    {
        return '<div id="node[+id+]"><a class="[+treeNodeClass+]"
        onclick="modx.tree.treeAction(event,[+id+]);"
        oncontextmenu="modx.tree.showPopup(event,[+id+],\'[+nodetitle_esc+]\');"
        data-id="[+id+]"
        data-title-esc="[+nodetitle_esc+]"
        data-published="[+published+]"
        data-deleted="[+deleted+]"
        data-isfolder="[+isfolder+]"
        data-href="[+url+]"
        data-private="[+isPrivate+]"
        data-roles="[+roles+]"
        data-level="[+level+]"
        data-icon-expanded="[+tree_plusnode+]"
        data-icon-collapsed="[+tree_minusnode+]"
        data-icon-folder-open="[+icon_folder_open+]"
        data-icon-folder-close="[+icon_folder_close+]"
        data-treepageclick="[+tree_page_click+]"
        data-showchildren="[+showChildren+]"
        data-openfolder="[+openFolder+]"
        data-indent="[+indent+]"
        data-expandall="[+expandAll+]"
        [+contextmenu+]
        >[+spacer+]<span
        class="toggle"
        onclick="modx.tree.toggleNode(event, [+id+]);"
        oncontextmenu="this.onclick(event);"
        >[+icon_node_toggle+]</span><span
        class="icon"
        onclick="modx.tree.showPopup(event,[+id+],\'[+nodetitle_esc+]\');return false;"
        oncontextmenu="this.onclick(event);return false;"
        >[+icon+]</span>[+lockedByUser+]<span
        class="title"
        title="[+title+]">[+nodetitleDisplay+][+weblinkDisplay+]</span>[+pageIdDisplay+]</a><div>';
    }

    /**
     * @param string $nodeNameSource
     * @return bool
     */
    protected function isDateNode(string $nodeNameSource): bool
    {
        switch ($nodeNameSource) {
            case 'createdon':
            case 'editedon':
            case 'publishedon':
            case 'pub_date':
            case 'unpub_date':
                return true;
            default:
                return false;
        }
    }
}
