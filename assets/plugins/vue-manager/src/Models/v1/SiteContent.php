<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;
use VueManager\Traits\ModelTimestampTrait;

/**
 * SiteContent
 *
 * @ORM\Table(name="site_content", indexes={@ORM\Index(name="aliasidx", columns={"alias"}), @ORM\Index(name="typeidx", columns={"type"}), @ORM\Index(name="id", columns={"id"}), @ORM\Index(name="content_ft_idx", columns={"pagetitle", "description", "content"}), @ORM\Index(name="parent", columns={"parent"})})
 * @ORM\Entity
 */
class SiteContent extends AbstractModel
{
    use ModelTimestampTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false, options={"default"="'document'"})
     */
    public string $type = 'document';

    /**
     * @var string
     *
     * @ORM\Column(name="contentType", type="string", length=50, nullable=false, options={"default"="'text/html'"})
     */
    public string $contentType = 'text/html';

    /**
     * @var string
     *
     * @ORM\Column(name="pagetitle", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public string $pagetitle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="longtitle", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public string $longtitle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public string $description = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="alias", type="string", length=245, nullable=true, options={"default"="''"})
     */
    public ?string $alias = '';

    /**
     * @var string
     *
     * @ORM\Column(name="link_attributes", type="string", length=255, nullable=false, options={"default"="''","comment"="Link attriubtes"})
     */
    public string $linkAttributes = '';

    /**
     * @var int
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    public int $published = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="pub_date", type="integer", nullable=false)
     */
    public int $pubDate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="unpub_date", type="integer", nullable=false)
     */
    public int $unpubDate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="parent", type="integer", nullable=false)
     */
    public int $parent = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="isfolder", type="integer", nullable=false)
     */
    public int $isfolder = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="introtext", type="text", length=65535, nullable=true, options={"default"="NULL","comment"="Used to provide quick summary of the document"})
     */
    public ?string $introtext = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="text", length=16777215, nullable=true, options={"default"="NULL"})
     */
    public ?string $content = null;

    /**
     * @var int
     *
     * @ORM\Column(name="richtext", type="boolean", nullable=false, options={"default"="1"})
     */
    public int $richtext = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="template", type="integer", nullable=false)
     */
    public int $template = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="menuindex", type="integer", nullable=false)
     */
    public int $menuindex = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="searchable", type="integer", nullable=false, options={"default"="1"})
     */
    public int $searchable = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="cacheable", type="integer", nullable=false, options={"default"="1"})
     */
    public int $cacheable = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="createdby", type="integer", nullable=false)
     */
    public int $createdby = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="editedby", type="integer", nullable=false)
     */
    public int $editedby = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="deleted", type="integer", nullable=false)
     */
    public int $deleted = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="deletedon", type="integer", nullable=false)
     */
    public int $deletedon = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="deletedby", type="integer", nullable=false)
     */
    public int $deletedby = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="publishedon", type="integer", nullable=false, options={"comment"="Date the document was published"})
     */
    public int $publishedon = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="publishedby", type="integer", nullable=false, options={"comment"="ID of user who published the document"})
     */
    public int $publishedby = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="menutitle", type="string", length=255, nullable=false, options={"default"="''","comment"="Menu title"})
     */
    public string $menutitle = '';

    /**
     * @var int
     *
     * @ORM\Column(name="donthit", type="boolean", nullable=false, options={"comment"="Disable page hit count"})
     */
    public int $donthit = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="privateweb", type="boolean", nullable=false, options={"comment"="Private web document"})
     */
    public int $privateweb = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="privatemgr", type="boolean", nullable=false, options={"comment"="Private manager document"})
     */
    public int $privatemgr = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="content_dispo", type="boolean", nullable=false, options={"comment"="0-inline, 1-attachment"})
     */
    public int $contentDispo = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="hidemenu", type="boolean", nullable=false, options={"comment"="Hide document from menu"})
     */
    public int $hidemenu = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="alias_visible", type="integer", nullable=false, options={"default"="1"})
     */
    public int $aliasVisible = 1;
}
