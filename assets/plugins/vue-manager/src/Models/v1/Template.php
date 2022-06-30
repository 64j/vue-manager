<?php

declare(strict_types=1);

namespace VueManager\Models\v1;

use VueManager\Application;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

class Template extends AbstractModel
{
    use ModelTimestampTrait;

    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $templatename;

    /**
     * @var string|null
     */
    public ?string $templatealias;

    /**
     * @var string
     */
    public string $description = '';

    /**
     * @var int
     */
    public int $category = 0;

    /**
     * @var string
     */
    public string $content;

    /**
     * @var int
     */
    public int $locked = 0;

    /**
     * @var int
     */
    public int $selectable = 1;

    /**
     * @param string $templatename
     */
    public function setTemplatename(string $templatename): void
    {
        $this->templatename = $templatename != '' ? $templatename : Application::getInstance()
            ->getLang('new_template');
    }
}
