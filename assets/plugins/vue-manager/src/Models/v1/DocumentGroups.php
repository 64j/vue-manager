<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * DocumentGroups
 *
 * @ORM\Table(name="document_groups", uniqueConstraints={@ORM\UniqueConstraint(name="ix_dg_id", columns={"document_group", "document"})}, indexes={@ORM\Index(name="document", columns={"document"}), @ORM\Index(name="document_group", columns={"document_group"})})
 * @ORM\Entity
 */
class DocumentGroups extends AbstractModel
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
     * @ORM\Column(name="document_group", type="integer", nullable=false)
     */
    public $documentGroup = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="document", type="integer", nullable=false)
     */
    public $document = 0;
}
