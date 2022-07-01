<?php

namespace VueManager\Traits;

trait ModelTimestampTrait
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
        if ($this->__saved) {
            $this->editedon = time();
        }
    }
}
