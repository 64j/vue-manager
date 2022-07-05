<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Application;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

/**
 * SiteTmplvars
 *
 * @ORM\Table(name="site_tmplvars", indexes={@ORM\Index(name="indx_rank", columns={"rank"})})
 * @ORM\Entity
 */
class SiteTmplvars extends AbstractModel
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
     * @ORM\Column(name="type", type="string", length=50, nullable=false, options={"default"="''"})
     */
    public $type = '';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false, options={"default"="''"})
     */
    public $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=80, nullable=false, options={"default"="''"})
     */
    public $caption = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public $description = '';

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
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    public $locked = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="elements", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public $elements = null;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    public $rank = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="display", type="string", length=20, nullable=false, options={"default"="''","comment"="Display Control"})
     */
    public $display = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="display_params", type="text", length=65535, nullable=true, options={"default"="NULL","comment"="Display Control Properties"})
     */
    public $displayParams = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="default_text", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public $defaultText = null;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name != '' ? $name : Application::getInstance()
            ->getLang('new_tmplvars');
    }
}
