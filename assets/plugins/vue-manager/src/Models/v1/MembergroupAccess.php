<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * MembergroupAccess
 *
 * @ORM\Table(name="membergroup_access")
 * @ORM\Entity
 */
class MembergroupAccess extends AbstractModel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var int
     *
     * @ORM\Column(name="membergroup", type="integer", nullable=false)
     */
    public $membergroup = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="documentgroup", type="integer", nullable=false)
     */
    public $documentgroup = 0;
}
