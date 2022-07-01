<?php

namespace VueManager\Models\v1;

use Doctrine\ORM\Mapping as ORM;
use VueManager\Models\AbstractModel;

/**
 * UserAttributes
 *
 * @ORM\Table(name="user_attributes", indexes={@ORM\Index(name="userid", columns={"internalKey"})})
 * @ORM\Entity
 */
class UserAttributes extends AbstractModel
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
     * @ORM\Column(name="internalKey", type="integer", nullable=false)
     */
    public $internalkey = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=100, nullable=false, options={"default"="''"})
     */
    public $fullname = '';

    /**
     * @var int
     *
     * @ORM\Column(name="role", type="integer", nullable=false)
     */
    public $role = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false, options={"default"="''"})
     */
    public $email = '';

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=100, nullable=false, options={"default"="''"})
     */
    public $phone = '';

    /**
     * @var string
     *
     * @ORM\Column(name="mobilephone", type="string", length=100, nullable=false, options={"default"="''"})
     */
    public $mobilephone = '';

    /**
     * @var int
     *
     * @ORM\Column(name="blocked", type="integer", nullable=false)
     */
    public $blocked = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="blockeduntil", type="integer", nullable=false)
     */
    public $blockeduntil = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="blockedafter", type="integer", nullable=false)
     */
    public $blockedafter = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="logincount", type="integer", nullable=false)
     */
    public $logincount = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="lastlogin", type="integer", nullable=false)
     */
    public $lastlogin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="thislogin", type="integer", nullable=false)
     */
    public $thislogin = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="failedlogincount", type="integer", nullable=false)
     */
    public $failedlogincount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="sessionid", type="string", length=100, nullable=false, options={"default"="''"})
     */
    public $sessionid = '';

    /**
     * @var int
     *
     * @ORM\Column(name="dob", type="integer", nullable=false)
     */
    public $dob = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="gender", type="integer", nullable=false, options={"comment"="0 - unknown, 1 - Male 2 - female"})
     */
    public $gender = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=5, nullable=false, options={"default"="''"})
     */
    public $country = '';

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public $street = '';

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false, options={"default"="''"})
     */
    public $city = '';

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=25, nullable=false, options={"default"="''"})
     */
    public $state = '';

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=25, nullable=false, options={"default"="''"})
     */
    public $zip = '';

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=100, nullable=false, options={"default"="''"})
     */
    public $fax = '';

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=false, options={"default"="''","comment"="link to photo"})
     */
    public $photo = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    public $comment = null;

    /**
     * @var int
     *
     * @ORM\Column(name="createdon", type="integer", nullable=false)
     */
    public $createdon = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="editedon", type="integer", nullable=false)
     */
    public $editedon = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="verified", type="integer", nullable=false, options={"default"="1"})
     */
    public $verified = 1;
}
