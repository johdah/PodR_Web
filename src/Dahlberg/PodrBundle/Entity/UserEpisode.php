<?php
// src/Dahlberg/PodrBundle/Entity/UserEpisode.php
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dahlberg\PodrBundle\Entity\Episode;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_episode")
 */
class UserEpisode {
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived = false;

    /**
     * @ORM\Column(name="current_time", type="integer", length=0)
     */
    private $currentTime = 0;

    /**
     * @ORM\Column(type="integer", length=0)
     */
    private $rating = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stashed = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $unread = true;

    /**
     * @ORM\ManyToOne(targetEntity="Episode", inversedBy="userEpisodes")
     * @ORM\JoinColumn(name="episode_id", referencedColumnName="id")
     **/
    private $episode;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userEpisodes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    /**
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

    public function __construct() {
        $this->unread = true;
    }

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
     * Set archived
     *
     * @param boolean $archived
     * @return UserEpisode
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * Set currentTime
     *
     * @param integer $currentTime
     * @return UserEpisode
     */
    public function setCurrentTime($currentTime)
    {
        $this->currentTime = $currentTime;

        return $this;
    }

    /**
     * Get currentTime
     *
     * @return integer
     */
    public function getCurrentTime()
    {
        return $this->currentTime;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return UserEpisode
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
     * Set stashed
     *
     * @param boolean $stashed
     * @return UserEpisode
     */
    public function setStashed($stashed)
    {
        $this->stashed = $stashed;

        return $this;
    }

    /**
     * Get stashed
     *
     * @return boolean
     */
    public function getStashed()
    {
        return $this->stashed;
    }

    /**
     * Set unread
     *
     * @param boolean $unread
     * @return UserEpisode
     */
    public function setUnread($unread)
    {
        $this->unread = $unread;

        return $this;
    }

    /**
     * Get unread
     *
     * @return boolean
     */
    public function getUnread()
    {
        return $this->unread;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return UserEpisode
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
     * Set episode
     *
     * @param \Dahlberg\PodrBundle\Entity\Episode $episode
     * @return UserEpisode
     */
    public function setEpisode(\Dahlberg\PodrBundle\Entity\Episode $episode = null)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return \Dahlberg\PodrBundle\Entity\Episode
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * Set user
     *
     * @param \Dahlberg\PodrBundle\Entity\User $user
     * @return UserEpisode
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