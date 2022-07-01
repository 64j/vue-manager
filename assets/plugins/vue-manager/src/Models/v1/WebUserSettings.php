<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * WebUserSettings
 *
 * @ORM\Table(name="web_user_settings", indexes={@ORM\Index(name="webuserid", columns={"webuser"}), @ORM\Index(name="setting_name", columns={"setting_name"})})
 * @ORM\Entity
 */
class WebUserSettings extends AbstractModel
{
    /**
     * @var int
     *
     * @ORM\Column(name="webuser", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $webuser;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_name", type="string", length=50, nullable=false, options={"default"="''"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $settingName = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="setting_value", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public $settingValue = null;
}
