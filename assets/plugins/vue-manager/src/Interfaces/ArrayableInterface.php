<?php

namespace VueManager\Interfaces;

interface ArrayableInterface
{
    /**
     * Get the instance as an array.
     *
     * @return array<TKey, TValue>
     */
    public function toArray(): array;
}
