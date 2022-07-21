<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Interfaces\ModelInterface;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

/**
 * SiteTmplvars
 *
 * @ORM\Table(name="site_tmplvars", indexes={@ORM\Index(name="indx_rank", columns={"rank"})})
 * @ORM\Entity
 */
class SiteTmplvars extends AbstractModel implements ModelInterface
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
     * @ORM\Column(name="type", type="string", length=50, nullable=false, options={"default"="''"})
     */
    public string $type = '';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false, options={"default"="''"})
     */
    public string $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=80, nullable=false, options={"default"="''"})
     */
    public string $caption = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="''"})
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
     * @ORM\Column(name="category", type="integer", nullable=false, options={"comment"="category id"})
     */
    public int $category = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    public int $locked = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="elements", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public ?string $elements = null;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    public int $rank = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="display", type="string", length=20, nullable=false, options={"default"="''","comment"="Display Control"})
     */
    public string $display = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="display_params", type="text", length=65535, nullable=true, options={"default"="NULL","comment"="Display Control Properties"})
     */
    public ?string $displayParams = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="default_text", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public ?string $defaultText = null;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name != '' ? $name : vum()->getLang('new_tmplvars');
    }
}
