<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * SiteTmplvarTemplates
 *
 * @ORM\Table(name="site_tmplvar_templates")
 * @ORM\Entity
 */
class SiteTmplvarTemplates extends AbstractModel
{
    /**
     * @var int
     *
     * @ORM\Column(name="tmplvarid", type="integer", nullable=false, options={"comment"="Template Variable id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $tmplvarid = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="templateid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $templateid = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    public $rank = 0;
}
