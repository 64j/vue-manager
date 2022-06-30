<?php

namespace VueManager\Models\Traits;

trait TimestampTrait
{
    /**
     * @var int
     */
    public int $createdon = 0;

    /**
     * @var int
     */
    public int $editedon = 0;

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
