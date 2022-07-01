<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * SitePluginEvents
 *
 * @ORM\Table(name="site_plugin_events")
 * @ORM\Entity
 */
class SitePluginEvents extends AbstractModel
{
    /**
     * @var int
     *
     * @ORM\Column(name="pluginid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $pluginid;

    /**
     * @var int
     *
     * @ORM\Column(name="evtid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $evtid = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer", nullable=false, options={"comment"="determines plugin run order"})
     */
    public $priority = 0;
}
