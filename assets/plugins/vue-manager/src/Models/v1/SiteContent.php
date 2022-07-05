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
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false, options={"default"="'document'"})
     */
    public $type = 'document';

    /**
     * @var string
     *
     * @ORM\Column(name="contentType", type="string", length=50, nullable=false, options={"default"="'text/html'"})
     */
    public $contenttype = 'text/html';

    /**
     * @var string
     *
     * @ORM\Column(name="pagetitle", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public $pagetitle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="longtitle", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public $longtitle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public $description = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="alias", type="string", length=245, nullable=true, options={"default"="''"})
     */
    public $alias = '';

    /**
     * @var string
     *
     * @ORM\Column(name="link_attributes", type="string", length=255, nullable=false, options={"default"="''","comment"="Link attriubtes"})
     */
    public $linkAttributes = '';

    /**
     * @var int
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    public $published = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="pub_date", type="integer", nullable=false)
     */
    public $pubDate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="unpub_date", type="integer", nullable=false)
     */
    public $unpubDate = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="parent", type="integer", nullable=false)
     */
    public $parent = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="isfolder", type="integer", nullable=false)
     */
    public $isfolder = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="introtext", type="text", length=65535, nullable=true, options={"default"="NULL","comment"="Used to provide quick summary of the document"})
     */
    public $introtext = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="text", length=16777215, nullable=true, options={"default"="NULL"})
     */
    public $content = null;

    /**
     * @var bool
     *
     * @ORM\Column(name="richtext", type="boolean", nullable=false, options={"default"="1"})
     */
    public $richtext = true;

    /**
     * @var int
     *
     * @ORM\Column(name="template", type="integer", nullable=false)
     */
    public $template = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="menuindex", type="integer", nullable=false)
     */
    public $menuindex = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="searchable", type="integer", nullable=false, options={"default"="1"})
     */
    public $searchable = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="cacheable", type="integer", nullable=false, options={"default"="1"})
     */
    public $cacheable = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="createdby", type="integer", nullable=false)
     */
    public $createdby = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="editedby", type="integer", nullable=false)
     */
    public $editedby = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="deleted", type="integer", nullable=false)
     */
    public $deleted = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="deletedon", type="integer", nullable=false)
     */
    public $deletedon = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="deletedby", type="integer", nullable=false)
     */
    public $deletedby = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="publishedon", type="integer", nullable=false, options={"comment"="Date the document was published"})
     */
    public $publishedon = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="publishedby", type="integer", nullable=false, options={"comment"="ID of user who published the document"})
     */
    public $publishedby = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="menutitle", type="string", length=255, nullable=false, options={"default"="''","comment"="Menu title"})
     */
    public $menutitle = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="donthit", type="boolean", nullable=false, options={"comment"="Disable page hit count"})
     */
    public $donthit = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="privateweb", type="boolean", nullable=false, options={"comment"="Private web document"})
     */
    public $privateweb = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="privatemgr", type="boolean", nullable=false, options={"comment"="Private manager document"})
     */
    public $privatemgr = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="content_dispo", type="boolean", nullable=false, options={"comment"="0-inline, 1-attachment"})
     */
    public $contentDispo = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="hidemenu", type="boolean", nullable=false, options={"comment"="Hide document from menu"})
     */
    public $hidemenu = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="alias_visible", type="integer", nullable=false, options={"default"="1"})
     */
    public $aliasVisible = 1;
}
