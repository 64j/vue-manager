<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Application;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

/**
 * SiteModules
 *
 * @ORM\Table(name="site_modules")
 * @ORM\Entity
 */
class SiteModules extends AbstractModel
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
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="'0'"})
     */
    public $description = '';

    /**
     * @var int
     *
     * @ORM\Column(name="editor_type", type="integer", nullable=false, options={"comment"="0-plain text,1-rich text,2-code editor"})
     */
    public $editorType = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="disabled", type="boolean", nullable=false)
     */
    public $disabled = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="category", type="integer", nullable=false, options={"comment"="category id"})
     */
    public $category = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="wrap", type="boolean", nullable=false)
     */
    public $wrap = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    public $locked = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=false, options={"default"="''","comment"="url to module icon"})
     */
    public $icon = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="enable_resource", type="boolean", nullable=false, options={"comment"="enables the resource file feature"})
     */
    public $enableResource = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="resourcefile", type="string", length=255, nullable=false, options={"default"="''","comment"="a physical link to a resource file"})
     */
    public $resourcefile = '';

    /**
     * @var string
     *
     * @ORM\Column(name="guid", type="string", length=32, nullable=false, options={"default"="''","comment"="globally unique identifier"})
     */
    public $guid = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="enable_sharedparams", type="boolean", nullable=false)
     */
    public $enableSharedparams = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="properties", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public $properties = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="modulecode", type="text", length=16777215, nullable=true, options={"default"="NULL","comment"="module boot up code"})
     */
    public $modulecode = null;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name != '' ? $name : Application::getInstance()
            ->getLang('new_module');
    }
}
