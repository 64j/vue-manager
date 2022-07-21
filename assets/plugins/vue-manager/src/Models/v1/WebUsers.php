<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Interfaces\ModelInterface;
use VueManager\Models\AbstractModel;

/**
 * WebUsers
 *
 * @ORM\Table(name="web_users", uniqueConstraints={@ORM\UniqueConstraint(name="username", columns={"username"})})
 * @ORM\Entity
 */
class WebUsers extends AbstractModel implements ModelInterface
{
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
     * @ORM\Column(name="username", type="string", length=100, nullable=false, options={"default"="''"})
     */
    public string $username = '';

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false, options={"default"="''"})
     */
    public string $password = '';

    /**
     * @var string
     *
     * @ORM\Column(name="cachepwd", type="string", length=100, nullable=false, options={"default"="''","comment"="Store new unconfirmed password"})
     */
    public string $cachepwd = '';
}
