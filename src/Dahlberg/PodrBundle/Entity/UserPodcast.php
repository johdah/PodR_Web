<?php
// src/Dahlberg/PodrBundle/Entity/UserPodcast.php
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dahlberg\PodrBundle\Entity\Podcast;

/**
 * @ORM\Entity(repositoryClass="Dahlberg\PodrBundle\Entity\UserPodcastRepository")
 * @ORM\Table(name="user_podcast")
 */
class UserPodcast {
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $following = false;

    /**
     * @ORM\Column(type="integer", length=0)
     */
    private $rating = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $starred = false;

    /**
     * @ORM\ManyToOne(targetEntity="Podcast", inversedBy="userPodcasts")
     * @ORM\JoinColumn(name="podcast_id", referencedColumnName="id")
     **/
    private $podcast;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userPodcasts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    /**
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set following
     *
     * @param boolean $following
     * @return UserPodcast
     */
    public function setFollowing($following)
    {
        $this->following = $following;

        return $this;
    }

    /**
     * Get following
     *
     * @return boolean
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return UserPodcast
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set starred
     *
     * @param boolean $starred
     * @return UserPodcast
     */
    public function setStarred($starred)
    {
        $this->starred = $starred;

        return $this;
    }

    /**
     * Get starred
     *
     * @return boolean
     */
    public function getStarred()
    {
        return $this->starred;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return UserPodcast
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set podcast
     *
     * @param \Dahlberg\PodrBundle\Entity\Podcast $podcast
     * @return UserPodcast
     */
    public function setPodcast(\Dahlberg\PodrBundle\Entity\Podcast $podcast = null)
    {
        $this->podcast = $podcast;

        return $this;
    }

    /**
     * Get podcast
     *
     * @return \Dahlberg\PodrBundle\Entity\Podcast
     */
    public function getPodcast()
    {
        return $this->podcast;
    }

    /**
     * Set user
     *
     * @param \Dahlberg\PodrBundle\Entity\User $user
     * @return UserPodcast
     */
    public function setUser(\Dahlberg\PodrBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Dahlberg\PodrBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}