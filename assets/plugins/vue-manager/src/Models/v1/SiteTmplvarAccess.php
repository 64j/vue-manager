<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * SiteTmplvarAccess
 *
 * @ORM\Table(name="site_tmplvar_access")
 * @ORM\Entity
 */
class SiteTmplvarAccess extends AbstractModel
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
     * @ORM\Column(name="tmplvarid", type="integer", nullable=false)
     */
    public $tmplvarid = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="documentgroup", type="integer", nullable=false)
     */
    public $documentgroup = 0;
}
