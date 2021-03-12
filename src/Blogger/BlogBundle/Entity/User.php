<?php

namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="workshop_users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    public function getId()
    {
        return $this->id;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setEmail($email)
    {

//        $this->setUsername($email);

        return parent::setEmail($email);
    }


    public function __construct()
    {
        parent::__construct();
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /** @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Blogger\BlogBundle\Entity\Entry",mappedBy="reviewer")
     */
    protected $articles;


    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Blogger\BlogBundle\Entity\Album",mappedBy="writer")
     */
    protected $album;

}
