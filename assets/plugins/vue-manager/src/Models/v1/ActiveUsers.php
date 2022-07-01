<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * ActiveUsers
 *
 * @ORM\Table(name="active_users")
 * @ORM\Entity
 */
class ActiveUsers extends AbstractModel
{
    /**
     * @var string
     *
     * @ORM\Column(name="sid", type="string", length=32, nullable=false, options={"default"="''"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $sid = '';

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=false, options={"default"="''"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $username = '';

    /**
     * @var int
     *
     * @ORM\Column(name="internalKey", type="integer", nullable=false)
     */
    public $internalkey = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="lasthit", type="integer", nullable=false)
     */
    public $lasthit = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=10, nullable=false, options={"default"="''"})
     */
    public $action = '';

    /**
     * @var int|null
     *
     * @ORM\Column(name="id", type="integer", nullable=true, options={"default"="NULL"})
     */
    public $id = null;
}
