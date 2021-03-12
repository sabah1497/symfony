<?php

namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class review
 * @package Blogger\BlogBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="reviews")
 * @ORM\Entity(repositoryClass="Blogger\BlogBundle\Repository\ReviewRepository")
 */

class Review
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;


    /**
     * @var string
     * @Assert\NotBlank(message="please enter the review title")
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The title is too short",
     *     maxMessage="The title is too long."
     * )
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;




    /**
     * @var string
     * @Assert\NotBlank(message="please write a review")
     * @Assert\Length(
     *     min=10,
     *     max=300,
     *     minMessage="The review is too short",
     *     maxMessage="The review is too long."
     * )
     * @ORM\Column(name="article", type="text")
     */
    private $article;

    /**
     * @var \DateTime
     * @Assert\DateTime
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var \Blogger\BlogBundle\Entity\Album
     * @ORM\ManyToOne(targetEntity="Blogger\BlogBundle\Entity\Album",inversedBy="reviews")
     * @ORM\JoinColumn(name="reviewof", referencedColumnName="id")
     */
    private $reviewof;

    /**
     * @var \Blogger\BlogBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="\Blogger\BlogBundle\Entity\User",inversedBy="articles")
     * @ORM\JoinColumn(name="reviewer", referencedColumnName="id")
     */
    private $reviewer;



    /**
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param string $blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
    }


    /**
     * @return string
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param string $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }



    /**
     * Get reviewof.
     *
     * @return \Blogger\BlogBundle\Entity\Album|null
     */
    public function getReviewof()
    {
        return $this->reviewof;
    }

    public function __toString()
    {
        return (string) $this->getArticle();
    }


    /**
     * Set reviewof.
     *
     * @param \Blogger\BlogBundle\Entity\Album|null $reviewof
     *
     * @return Review
     */
    public function setReviewof(\Blogger\BlogBundle\Entity\Album $reviewof = null)
    {
        $this->reviewof = $reviewof;
        return $this;
    }


    /**
     * Get reviewer.
     *
     * @return \Blogger\BlogBundle\Entity\User|null
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }

    /**
     * Set reviewer.
     *
     * @param \Blogger\BlogBundle\Entity\User|null $reviewer
     *
     * @return Review
     */
    public function setReviewer(\Blogger\BlogBundle\Entity\User $reviewer = null)
    {
        $this->reviewer = $reviewer;
        return $this;
    }

}

