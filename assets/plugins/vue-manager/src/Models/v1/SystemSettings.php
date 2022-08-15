<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * SystemSettings
 *
 * @ORM\Table(name="system_settings")
 * @ORM\Entity
 */
class SystemSettings extends AbstractModel
{
    /**
     * @var string
     *
     * @ORM\Column(name="setting_name", type="string", length=50, nullable=false, options={"default"="''"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public string $settingName = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="setting_value", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public ?string $settingValue = null;
}
