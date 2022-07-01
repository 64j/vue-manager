<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * ManagerLog
 *
 * @ORM\Table(name="manager_log")
 * @ORM\Entity
 */
class ManagerLog extends AbstractModel
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
     * @ORM\Column(name="timestamp", type="integer", nullable=false)
     */
    public $timestamp = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="internalKey", type="integer", nullable=false)
     */
    public $internalkey = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    public $username = null;

    /**
     * @var int
     *
     * @ORM\Column(name="action", type="integer", nullable=false)
     */
    public $action = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="itemid", type="string", length=10, nullable=true, options={"default"="'0'"})
     */
    public $itemid = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="itemname", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    public $itemname = null;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public $message = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="ip", type="string", length=46, nullable=true, options={"default"="NULL"})
     */
    public $ip = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="useragent", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    public $useragent = null;
}
