<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * MemberGroups
 *
 * @ORM\Table(name="member_groups", uniqueConstraints={@ORM\UniqueConstraint(name="ix_group_member", columns={"user_group", "member"})})
 * @ORM\Entity
 */
class MemberGroups extends AbstractModel
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
     * @ORM\Column(name="user_group", type="integer", nullable=false)
     */
    public $userGroup = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="member", type="integer", nullable=false)
     */
    public $member = 0;
}
