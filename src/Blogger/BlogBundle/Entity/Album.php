<?php

namespace Blogger\BlogBundle\Entity;

use Blogger\BlogBundle\BloggerBlogBundle;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class album
 * @package Blogger\BlogBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="albums")
 *
 */

class Album
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\ GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     *@ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var
     *
     *@ORM\Column(name="artist", type="string", length=255)
     *
     */

    private $artist;

    /**
     * @var
     *
     *@ORM\Column(name="trackList", type="string", length=255)
     */

    private $trackList;


    /**
     * @var
     * @Assert\NotBlank(message="please enter an image")
     * @Assert\Image()
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;




    /**
     *
     * @ORM\Column(name="timestamp", type="datetime")
     *
     */

    private $timestamp;


    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Blogger\BlogBundle\Entity\Review",mappedBy="reviewof")
     */

    private $reviews;



    /**
     * @ORM\ManyToOne(targetEntity="Blogger\BlogBundle\Entity\User",inversedBy="album")
     * @ORM\JoinColumn(name="writer", referencedColumnName="id")
     *
     */
    private $writer;


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
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return string
     */
    public function getTrackList()
    {
        return $this->trackList;
    }

    /**
     * @param string $trackList
     */
    public function setTrackList($trackList)
    {
        $this->trackList = $trackList;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;

    }

    /**
     * Set image.
     *
     * @param string $image
     *
     */
    public function setImageFile(File $image = null)
    {
        $this->image = $image;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return \DateTime
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }


    /**
     * @return string
     */
    public function getReviews()
    {
        return $this->reviews;
    }
    /**
     * @param string $review
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * @return BloggerBlogBundle/Entity/User|null
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @param BloggerBlogBundle/Entity/User|null $writer
     */
    public function setWriter($writer)
    {
        $this->writer = $writer;
    }



}