<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * SiteModuleAccess
 *
 * @ORM\Table(name="site_module_access")
 * @ORM\Entity
 */
class SiteModuleAccess extends AbstractModel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var int
     *
     * @ORM\Column(name="module", type="integer", nullable=false)
     */
    public $module = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="usergroup", type="integer", nullable=false)
     */
    public $usergroup = 0;
}
