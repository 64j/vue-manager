<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * SystemEventnames
 *
 * @ORM\Table(name="system_eventnames", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name"})})
 * @ORM\Entity
 */
class SystemEventnames extends AbstractModel
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
     * @ORM\Column(name="name", type="string", length=50, nullable=false, options={"default"="''"})
     */
    public $name = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="service", type="boolean", nullable=false, options={"comment"="System Service number"})
     */
    public $service = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="groupname", type="string", length=20, nullable=false, options={"default"="''"})
     */
    public $groupname = '';
}
