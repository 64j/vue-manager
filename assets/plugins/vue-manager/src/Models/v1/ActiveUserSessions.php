<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * ActiveUserSessions
 *
 * @ORM\Table(name="active_user_sessions")
 * @ORM\Entity
 */
class ActiveUserSessions extends AbstractModel
{
    /**
     * @var string
     *
     * @ORM\Column(name="sid", type="string", length=32, nullable=false, options={"default"="''"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $sid = '';

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
     * @ORM\Column(name="ip", type="string", length=50, nullable=false, options={"default"="''"})
     */
    public $ip = '';
}
