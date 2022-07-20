<?php

namespace VueManager\Traits;

trait ServiceMetaTrait
{
    /**
     * @var array
     */
    protected array $meta = [];

    /**
     * @param array $meta
     * @return array
     */
    public function setMeta(array $meta): array
    {
        return $this->meta = $meta;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }
}
