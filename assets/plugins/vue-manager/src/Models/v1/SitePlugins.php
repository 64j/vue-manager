<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Application;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

/**
 * SitePlugins
 *
 * @ORM\Table(name="site_plugins")
 * @ORM\Entity
 */
class SitePlugins extends AbstractModel
{
    use ModelTimestampTrait;

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
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="'Plugin'"})
     */
    public $description = 'Plugin';

    /**
     * @var int
     *
     * @ORM\Column(name="editor_type", type="integer", nullable=false, options={"comment"="0-plain text,1-rich text,2-code editor"})
     */
    public $editorType = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="category", type="integer", nullable=false, options={"comment"="category id"})
     */
    public $category = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="cache_type", type="boolean", nullable=false, options={"comment"="Cache option"})
     */
    public $cacheType = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="plugincode", type="text", length=16777215, nullable=true, options={"default"="NULL"})
     */
    public $plugincode = null;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    public $locked = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="properties", type="text", length=65535, nullable=true, options={"default"="NULL","comment"="Default Properties"})
     */
    public $properties = null;

    /**
     * @var bool
     *
     * @ORM\Column(name="disabled", type="boolean", nullable=false, options={"comment"="Disables the plugin"})
     */
    public $disabled = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="moduleguid", type="string", length=32, nullable=false, options={"default"="''","comment"="GUID of module from which to import shared parameters"})
     */
    public $moduleguid = '';

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name != '' ? $name : Application::getInstance()
            ->getLang('new_plugin');
    }
}
