<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * UserMessages
 *
 * @ORM\Table(name="user_messages")
 * @ORM\Entity
 */
class UserMessages extends AbstractModel
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=15, nullable=false, options={"default"="''"})
     */
    public $type = '';

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=60, nullable=false, options={"default"="''"})
     */
    public $subject = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public $message = null;

    /**
     * @var int
     *
     * @ORM\Column(name="sender", type="integer", nullable=false)
     */
    public $sender = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="recipient", type="integer", nullable=false)
     */
    public $recipient = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="private", type="boolean", nullable=false)
     */
    public $private = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="postdate", type="integer", nullable=false)
     */
    public $postdate = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="messageread", type="boolean", nullable=false)
     */
    public $messageread = 0;
}
