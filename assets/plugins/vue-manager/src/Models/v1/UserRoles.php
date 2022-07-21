<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Interfaces\ModelInterface;
use VueManager\Models\AbstractModel;

/**
 * UserRoles
 *
 * @ORM\Table(name="user_roles")
 * @ORM\Entity
 */
class UserRoles extends AbstractModel implements ModelInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false, options={"default"="''"})
     */
    public string $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public string $description = '';

    /**
     * @var int
     *
     * @ORM\Column(name="frames", type="integer", nullable=false)
     */
    public int $frames = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="home", type="integer", nullable=false)
     */
    public int $home = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="view_document", type="integer", nullable=false)
     */
    public int $viewDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_document", type="integer", nullable=false)
     */
    public int $newDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_document", type="integer", nullable=false)
     */
    public int $saveDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="publish_document", type="integer", nullable=false)
     */
    public int $publishDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_document", type="integer", nullable=false)
     */
    public int $deleteDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="empty_trash", type="integer", nullable=false)
     */
    public int $emptyTrash = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="action_ok", type="integer", nullable=false)
     */
    public int $actionOk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="logout", type="integer", nullable=false)
     */
    public int $logout = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="help", type="integer", nullable=false)
     */
    public int $help = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="messages", type="integer", nullable=false)
     */
    public int $messages = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_user", type="integer", nullable=false)
     */
    public int $newUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_user", type="integer", nullable=false)
     */
    public int $editUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="logs", type="integer", nullable=false)
     */
    public int $logs = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_parser", type="integer", nullable=false)
     */
    public int $editParser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_parser", type="integer", nullable=false)
     */
    public int $saveParser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_template", type="integer", nullable=false)
     */
    public int $editTemplate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="settings", type="integer", nullable=false)
     */
    public int $settings = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="credits", type="integer", nullable=false)
     */
    public int $credits = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_template", type="integer", nullable=false)
     */
    public int $newTemplate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_template", type="integer", nullable=false)
     */
    public int $saveTemplate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_template", type="integer", nullable=false)
     */
    public int $deleteTemplate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_snippet", type="integer", nullable=false)
     */
    public int $editSnippet = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_snippet", type="integer", nullable=false)
     */
    public int $newSnippet = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_snippet", type="integer", nullable=false)
     */
    public int $saveSnippet = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_snippet", type="integer", nullable=false)
     */
    public int $deleteSnippet = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_chunk", type="integer", nullable=false)
     */
    public int $editChunk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_chunk", type="integer", nullable=false)
     */
    public int $newChunk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_chunk", type="integer", nullable=false)
     */
    public int $saveChunk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_chunk", type="integer", nullable=false)
     */
    public int $deleteChunk = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="empty_cache", type="integer", nullable=false)
     */
    public int $emptyCache = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_document", type="integer", nullable=false)
     */
    public int $editDocument = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="change_password", type="integer", nullable=false)
     */
    public int $changePassword = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="error_dialog", type="integer", nullable=false)
     */
    public int $errorDialog = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="about", type="integer", nullable=false)
     */
    public int $about = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="category_manager", type="integer", nullable=false)
     */
    public int $categoryManager = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="file_manager", type="integer", nullable=false)
     */
    public int $fileManager = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="assets_files", type="integer", nullable=false)
     */
    public int $assetsFiles = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="assets_images", type="integer", nullable=false)
     */
    public int $assetsImages = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_user", type="integer", nullable=false)
     */
    public int $saveUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_user", type="integer", nullable=false)
     */
    public int $deleteUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_password", type="integer", nullable=false)
     */
    public int $savePassword = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_role", type="integer", nullable=false)
     */
    public int $editRole = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_role", type="integer", nullable=false)
     */
    public int $saveRole = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_role", type="integer", nullable=false)
     */
    public int $deleteRole = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_role", type="integer", nullable=false)
     */
    public int $newRole = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="access_permissions", type="integer", nullable=false)
     */
    public int $accessPermissions = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="bk_manager", type="integer", nullable=false)
     */
    public int $bkManager = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_plugin", type="integer", nullable=false)
     */
    public int $newPlugin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_plugin", type="integer", nullable=false)
     */
    public int $editPlugin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_plugin", type="integer", nullable=false)
     */
    public int $savePlugin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_plugin", type="integer", nullable=false)
     */
    public int $deletePlugin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_module", type="integer", nullable=false)
     */
    public int $newModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_module", type="integer", nullable=false)
     */
    public int $editModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_module", type="integer", nullable=false)
     */
    public int $saveModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_module", type="integer", nullable=false)
     */
    public int $deleteModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="exec_module", type="integer", nullable=false)
     */
    public int $execModule = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="view_eventlog", type="integer", nullable=false)
     */
    public int $viewEventlog = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_eventlog", type="integer", nullable=false)
     */
    public int $deleteEventlog = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="new_web_user", type="integer", nullable=false)
     */
    public int $newWebUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="edit_web_user", type="integer", nullable=false)
     */
    public int $editWebUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="save_web_user", type="integer", nullable=false)
     */
    public int $saveWebUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="delete_web_user", type="integer", nullable=false)
     */
    public int $deleteWebUser = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="web_access_permissions", type="integer", nullable=false)
     */
    public int $webAccessPermissions = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="view_unpublished", type="integer", nullable=false)
     */
    public int $viewUnpublished = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="import_static", type="integer", nullable=false)
     */
    public int $importStatic = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="export_static", type="integer", nullable=false)
     */
    public int $exportStatic = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="remove_locks", type="integer", nullable=false)
     */
    public int $removeLocks = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="display_locks", type="integer", nullable=false)
     */
    public int $displayLocks = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="change_resourcetype", type="integer", nullable=false)
     */
    public int $changeResourcetype = 0;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name != '' ? $name : vum()->getLang('new_role');
    }
}
