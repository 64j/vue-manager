<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * SiteModuleDepobj
 *
 * @ORM\Table(name="site_module_depobj")
 * @ORM\Entity
 */
class SiteModuleDepobj extends AbstractModel
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
     * @ORM\Column(name="module", type="integer", nullable=false)
     */
    public $module = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="resource", type="integer", nullable=false)
     */
    public $resource = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer", nullable=false, options={"comment"="10-chunks, 20-docs, 30-plugins, 40-snips, 50-tpls, 60-tvs"})
     */
    public $type = 0;
}
