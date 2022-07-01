<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * WebGroups
 *
 * @ORM\Table(name="web_groups", uniqueConstraints={@ORM\UniqueConstraint(name="ix_group_user", columns={"webgroup", "webuser"})})
 * @ORM\Entity
 */
class WebGroups extends AbstractModel
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
     * @ORM\Column(name="webgroup", type="integer", nullable=false)
     */
    public $webgroup = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="webuser", type="integer", nullable=false)
     */
    public $webuser = 0;
}
