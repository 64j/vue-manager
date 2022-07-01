<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * UserRoles
 *
 * @ORM\Table(name="user_roles")
 * @ORM\Entity
 */
class UserRoles extends AbstractModel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false, options={"default"="''"})
     */
    public $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public $description = '';

    /**
     * @var int
     *
     * @ORM\Column(name="frames", type="integer", nullable=false)
     */
    public $frames = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="home", type="integer", nullable=false)
     */
    public $home = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="view_document", type="integer", nullable=false)
     */
    public $viewDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_document", type="integer", nullable=false)
     */
    public $newDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_document", type="integer", nullable=false)
     */
    public $saveDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="publish_document", type="integer", nullable=false)
     */
    public $publishDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_document", type="integer", nullable=false)
     */
    public $deleteDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="empty_trash", type="integer", nullable=false)
     */
    public $emptyTrash = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="action_ok", type="integer", nullable=false)
     */
    public $actionOk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="logout", type="integer", nullable=false)
     */
    public $logout = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="help", type="integer", nullable=false)
     */
    public $help = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="messages", type="integer", nullable=false)
     */
    public $messages = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_user", type="integer", nullable=false)
     */
    public $newUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_user", type="integer", nullable=false)
     */
    public $editUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="logs", type="integer", nullable=false)
     */
    public $logs = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_parser", type="integer", nullable=false)
     */
    public $editParser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_parser", type="integer", nullable=false)
     */
    public $saveParser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_template", type="integer", nullable=false)
     */
    public $editTemplate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="settings", type="integer", nullable=false)
     */
    public $settings = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="credits", type="integer", nullable=false)
     */
    public $credits = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_template", type="integer", nullable=false)
     */
    public $newTemplate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_template", type="integer", nullable=false)
     */
    public $saveTemplate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_template", type="integer", nullable=false)
     */
    public $deleteTemplate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_snippet", type="integer", nullable=false)
     */
    public $editSnippet = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_snippet", type="integer", nullable=false)
     */
    public $newSnippet = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_snippet", type="integer", nullable=false)
     */
    public $saveSnippet = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_snippet", type="integer", nullable=false)
     */
    public $deleteSnippet = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_chunk", type="integer", nullable=false)
     */
    public $editChunk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_chunk", type="integer", nullable=false)
     */
    public $newChunk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_chunk", type="integer", nullable=false)
     */
    public $saveChunk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_chunk", type="integer", nullable=false)
     */
    public $deleteChunk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="empty_cache", type="integer", nullable=false)
     */
    public $emptyCache = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_document", type="integer", nullable=false)
     */
    public $editDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="change_password", type="integer", nullable=false)
     */
    public $changePassword = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="error_dialog", type="integer", nullable=false)
     */
    public $errorDialog = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="about", type="integer", nullable=false)
     */
    public $about = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="category_manager", type="integer", nullable=false)
     */
    public $categoryManager = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="file_manager", type="integer", nullable=false)
     */
    public $fileManager = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="assets_files", type="integer", nullable=false)
     */
    public $assetsFiles = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="assets_images", type="integer", nullable=false)
     */
    public $assetsImages = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_user", type="integer", nullable=false)
     */
    public $saveUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_user", type="integer", nullable=false)
     */
    public $deleteUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_password", type="integer", nullable=false)
     */
    public $savePassword = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_role", type="integer", nullable=false)
     */
    public $editRole = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_role", type="integer", nullable=false)
     */
    public $saveRole = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_role", type="integer", nullable=false)
     */
    public $deleteRole = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_role", type="integer", nullable=false)
     */
    public $newRole = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="access_permissions", type="integer", nullable=false)
     */
    public $accessPermissions = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="bk_manager", type="integer", nullable=false)
     */
    public $bkManager = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_plugin", type="integer", nullable=false)
     */
    public $newPlugin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_plugin", type="integer", nullable=false)
     */
    public $editPlugin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_plugin", type="integer", nullable=false)
     */
    public $savePlugin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_plugin", type="integer", nullable=false)
     */
    public $deletePlugin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_module", type="integer", nullable=false)
     */
    public $newModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_module", type="integer", nullable=false)
     */
    public $editModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_module", type="integer", nullable=false)
     */
    public $saveModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_module", type="integer", nullable=false)
     */
    public $deleteModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="exec_module", type="integer", nullable=false)
     */
    public $execModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="view_eventlog", type="integer", nullable=false)
     */
    public $viewEventlog = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_eventlog", type="integer", nullable=false)
     */
    public $deleteEventlog = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_web_user", type="integer", nullable=false)
     */
    public $newWebUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_web_user", type="integer", nullable=false)
     */
    public $editWebUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_web_user", type="integer", nullable=false)
     */
    public $saveWebUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_web_user", type="integer", nullable=false)
     */
    public $deleteWebUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="web_access_permissions", type="integer", nullable=false)
     */
    public $webAccessPermissions = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="view_unpublished", type="integer", nullable=false)
     */
    public $viewUnpublished = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="import_static", type="integer", nullable=false)
     */
    public $importStatic = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="export_static", type="integer", nullable=false)
     */
    public $exportStatic = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="remove_locks", type="integer", nullable=false)
     */
    public $removeLocks = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="display_locks", type="integer", nullable=false)
     */
    public $displayLocks = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="change_resourcetype", type="integer", nullable=false)
     */
    public $changeResourcetype = 0;
}
