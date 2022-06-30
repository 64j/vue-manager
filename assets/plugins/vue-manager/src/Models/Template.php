<?php

declare(strict_types=1);

namespace VueManager\Models;

use VueManager\Application;

class Template extends AbstractModel
{
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
     * @var int
     */
    public int $createdon = 0;

    /**
     * @var int
     */
    public int $editedon = 0;

    /**
     * @param string $templatename
     */
    public function setTemplatename(string $templatename): void
    {
        $this->templatename = $templatename != '' ? $templatename : Application::getInstance()
            ->getLang('new_template');
    }

    /**
     * @param int $createdon
     */
    public function setCreatedon(int $createdon): void
    {
        $this->createdon = $createdon ?: time();
    }

    /**
     * @param int $editedon
     */
    public function setEditedon(int $editedon): void
    {
        $this->editedon = time();
    }
}
