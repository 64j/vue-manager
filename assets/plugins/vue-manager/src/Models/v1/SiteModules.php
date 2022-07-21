<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Interfaces\ModelInterface;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

/**
 * SiteModules
 *
 * @ORM\Table(name="site_modules")
 * @ORM\Entity
 */
class SiteModules extends AbstractModel implements ModelInterface
{
    use ModelTimestampTrait;

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
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="'0'"})
     */
    public string $description = '';

    /**
     * @var int
     *
     * @ORM\Column(name="editor_type", type="integer", nullable=false, options={"comment"="0-plain text,1-rich text,2-code editor"})
     */
    public int $editorType = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="disabled", type="boolean", nullable=false)
     */
    public int $disabled = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="category", type="integer", nullable=false, options={"comment"="category id"})
     */
    public int $category = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="wrap", type="boolean", nullable=false)
     */
    public int $wrap = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    public int $locked = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=false, options={"default"="''","comment"="url to module icon"})
     */
    public string $icon = '';

    /**
     * @var int
     *
     * @ORM\Column(name="enable_resource", type="boolean", nullable=false, options={"comment"="enables the resource file feature"})
     */
    public int $enableResource = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="resourcefile", type="string", length=255, nullable=false, options={"default"="''","comment"="a physical link to a resource file"})
     */
    public string $resourcefile = '';

    /**
     * @var string
     *
     * @ORM\Column(name="guid", type="string", length=32, nullable=false, options={"default"="''","comment"="globally unique identifier"})
     */
    public string $guid = '';

    /**
     * @var int
     *
     * @ORM\Column(name="enable_sharedparams", type="boolean", nullable=false)
     */
    public int $enableSharedparams = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="properties", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public ?string $properties = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="modulecode", type="text", length=16777215, nullable=true, options={"default"="NULL","comment"="module boot up code"})
     */
    public ?string $modulecode = null;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name != '' ? $name : vum()->getLang('new_module');
    }
}
