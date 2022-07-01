<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * WebgroupAccess
 *
 * @ORM\Table(name="webgroup_access")
 * @ORM\Entity
 */
class WebgroupAccess extends AbstractModel
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
     * @ORM\Column(name="documentgroup", type="integer", nullable=false)
     */
    public $documentgroup = 0;
}
