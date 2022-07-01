<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * EventLog
 *
 * @ORM\Table(name="event_log", indexes={@ORM\Index(name="user", columns={"user"})})
 * @ORM\Entity
 */
class EventLog extends AbstractModel
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
     * @var int|null
     *
     * @ORM\Column(name="eventid", type="integer", nullable=true)
     */
    public $eventid = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="createdon", type="integer", nullable=false)
     */
    public $createdon = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="type", type="boolean", nullable=false, options={"default"="1","comment"="1- information, 2 - warning, 3- error"})
     */
    public $type = true;

    /**
     * @var int
     *
     * @ORM\Column(name="user", type="integer", nullable=false, options={"comment"="link to user table"})
     */
    public $user = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="usertype", type="boolean", nullable=false, options={"comment"="0 - manager, 1 - web"})
     */
    public $usertype = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=50, nullable=false, options={"default"="''"})
     */
    public $source = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public $description = null;
}
