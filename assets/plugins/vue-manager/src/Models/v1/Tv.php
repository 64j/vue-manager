<?php

declare(strict_types=1);

namespace VueManager\Models\v1;

use VueManager\Application;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

class Tv extends AbstractModel
{
    use ModelTimestampTrait;

    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $caption;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var int
     */
    public int $category;

    /**
     * @var int
     */
    public int $locked;

    /**
     * @var string
     */
    public string $elements;

    /**
     * @var string
     */
    public string $display_params;

    /**
     * @var string
     */
    public string $default_text;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name != '' ? $name : Application::getInstance()
            ->getLang('new_tmplvars');
    }
}
