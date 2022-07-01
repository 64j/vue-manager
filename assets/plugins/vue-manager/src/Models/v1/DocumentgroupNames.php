<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * DocumentgroupNames
 *
 * @ORM\Table(name="documentgroup_names", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name"})})
 * @ORM\Entity
 */
class DocumentgroupNames extends AbstractModel
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
     * @ORM\Column(name="name", type="string", length=245, nullable=false, options={"default"="''"})
     */
    public $name = '';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="private_memgroup", type="boolean", nullable=true, options={"comment"="determine whether the document group is private to manager users"})
     */
    public $privateMemgroup = 0;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="private_webgroup", type="boolean", nullable=true, options={"comment"="determines whether the document is private to web users"})
     */
    public $privateWebgroup = 0;
}
