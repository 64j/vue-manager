<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Interfaces\ModelInterface;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

/**
 * SitePlugins
 *
 * @ORM\Table(name="site_plugins")
 * @ORM\Entity
 */
class SitePlugins extends AbstractModel implements ModelInterface
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
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="'Plugin'"})
     */
    public string $description = 'Plugin';

    /**
     * @var int
     *
     * @ORM\Column(name="editor_type", type="integer", nullable=false, options={"comment"="0-plain text,1-rich text,2-code editor"})
     */
    public int $editorType = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="category", type="integer", nullable=false, options={"comment"="category id"})
     */
    public int $category = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="cache_type", type="boolean", nullable=false, options={"comment"="Cache option"})
     */
    public int $cacheType = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="plugincode", type="text", length=16777215, nullable=true, options={"default"="NULL"})
     */
    public ?string $plugincode = null;

    /**
     * @var int
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    public int $locked = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="properties", type="text", length=65535, nullable=true, options={"default"="NULL","comment"="Default Properties"})
     */
    public ?string $properties = null;

    /**
     * @var int
     *
     * @ORM\Column(name="disabled", type="boolean", nullable=false, options={"comment"="Disables the plugin"})
     */
    public int $disabled = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="moduleguid", type="string", length=32, nullable=false, options={"default"="''","comment"="GUID of module from which to import shared parameters"})
     */
    public string $moduleguid = '';

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name != '' ? $name : vum()->getLang('new_plugin');
    }
}
