<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Application;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

/**
 * SiteTemplates
 *
 * @ORM\Table(name="site_templates")
 * @ORM\Entity
 */
class SiteTemplates extends AbstractModel
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
     * @ORM\Column(name="templatename", type="string", length=100, nullable=false, options={"default"="''"})
     */
    public string $templatename = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="templatealias", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    public ?string $templatealias = null;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="'Template'"})
     */
    public string $description = 'Template';

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
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=false, options={"default"="''","comment"="url to icon file"})
     */
    public string $icon = '';

    /**
     * @var int
     *
     * @ORM\Column(name="template_type", type="integer", nullable=false, options={"comment"="0-page,1-content"})
     */
    public int $templateType = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="text", length=16777215, nullable=true, options={"default"="NULL"})
     */
    public ?string $content = null;

    /**
     * @var int
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    public int $locked = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="selectable", type="boolean", nullable=false, options={"default"="1"})
     */
    public int $selectable = 1;

    /**
     * @var array
     */
    public array $tvs = [];

    /**
     * @param string $templatename
     */
    public function setTemplatename(string $templatename): void
    {
        $this->templatename = $templatename != '' ? $templatename : Application::getInstance()
            ->getLang('new_template');
    }
}
