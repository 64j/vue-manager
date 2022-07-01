<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * SiteTmplvarContentvalues
 *
 * @ORM\Table(name="site_tmplvar_contentvalues", uniqueConstraints={@ORM\UniqueConstraint(name="ix_tvid_contentid", columns={"tmplvarid", "contentid"})}, indexes={@ORM\Index(name="idx_tmplvarid", columns={"tmplvarid"}), @ORM\Index(name="idx_id", columns={"contentid"}), @ORM\Index(name="value_ft_idx", columns={"value"})})
 * @ORM\Entity
 */
class SiteTmplvarContentvalues extends AbstractModel
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
     * @ORM\Column(name="tmplvarid", type="integer", nullable=false, options={"comment"="Template Variable id"})
     */
    public $tmplvarid = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="contentid", type="integer", nullable=false, options={"comment"="Site Content Id"})
     */
    public $contentid = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="value", type="text", length=16777215, nullable=true, options={"default"="NULL"})
     */
    public $value = null;
}
