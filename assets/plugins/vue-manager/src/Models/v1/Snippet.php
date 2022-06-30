<?php

declare(strict_types=1);

namespace VueManager\Models\v1;

use VueManager\Application;
use VueManager\Models\AbstractModel;
use VueManager\Models\Traits\TimestampTrait;

class Snippet extends AbstractModel
{
    use TimestampTrait;

    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var int
     */
    public int $category;

    /**
     * @var string
     */
    public string $snippet;

    /**
     * @var int
     */
    public int $locked;

    /**
     * @var int
     */
    public int $disabled;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name != '' ? $name : Application::getInstance()
            ->getLang('new_snippet');
    }
}
