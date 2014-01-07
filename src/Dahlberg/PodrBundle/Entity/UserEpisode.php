<?php
// src/Dahlberg/PodrBundle/Entity/UserEpisode.php
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dahlberg\PodrBundle\Entity\Episode;

/**
 * @ORM\Entity(repositoryClass="Dahlberg\PodrBundle\Entity\UserEpisodeRepository")
 * @ORM\Table(name="user_episode")
 */
class UserEpisode implements \JsonSerializable {
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived = true;

    /**
     * @ORM\Column(name="current_position", type="integer")
     */
    private $currentPosition = -1;

    /**
     * @ORM\Column(type="integer", length=1)
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
        $this->dateUpdated = new \DateTime('NOW');
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
     * Set currentPosition
     *
     * @param integer $currentPosition
     * @return UserEpisode
     */
    public function setCurrentPosition($currentPosition)
    {
        $this->currentPosition = $currentPosition;

        return $this;
    }

    /**
     * Get currentPosition
     *
     * @return integer
     */
    public function getCurrentPosition()
    {
        return $this->currentPosition;
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
     * @param Episode $episode
     * @return UserEpisode
     */
    public function setEpisode(Episode $episode = null)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return Episode
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return UserEpisode
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return array(
            'id'                => $this->id,
            'archived'          => $this->archived,
            'currentPosition'   => $this->currentPosition,
            'rating'            => $this->rating,
            'stashed'           => $this->stashed,
            'unread'            => $this->unread,
            'dateUpdated'       => $this->dateUpdated
        );
    }
}
