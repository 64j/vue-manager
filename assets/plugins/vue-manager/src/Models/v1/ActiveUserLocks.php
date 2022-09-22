<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * ActiveUserLocks
 *
 * @ORM\Table(name="active_user_locks", uniqueConstraints={@ORM\UniqueConstraint(name="ix_element_id", columns={"elementType", "elementId", "sid"})})
 * @ORM\Entity
 */
class ActiveUserLocks extends AbstractModel
{
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
     * @ORM\Column(name="sid", type="string", length=32, nullable=false, options={"default"="''"})
     */
    public $sid = '';

    /**
     * @var int
     *
     * @ORM\Column(name="internalKey", type="integer", nullable=false)
     */
    public int $internalKey = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="elementType", type="integer", nullable=false)
     */
    public int $elementType = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="elementId", type="integer", nullable=false)
     */
    public int $elementId = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="lasthit", type="integer", nullable=false)
     */
    public int $lasthit = 0;
}
