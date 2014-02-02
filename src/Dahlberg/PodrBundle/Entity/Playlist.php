<?php
// src/Dahlberg/PodrBundle/Entity/Playlist.php
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="playlists")
 */
class Playlist implements \JsonSerializable {
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $style;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userOwnedPlaylists")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $owner;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

    /**
     * @ORM\OneToMany(targetEntity="PlaylistPodcast", mappedBy="playlist")
     */
    private $playlistPodcasts;

    public function __construct() {
        $this->dateCreated = new \DateTime('NOW');
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
     * Set title
     *
     * @param string $title
     * @return Playlist
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Playlist
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set style
     *
     * @param string $style
     * @return Playlist
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Playlist
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return Playlist
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
     * Set owner
     *
     * @param User $owner
     * @return Playlist
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add playlistPodcasts
     *
     * @param PlaylistPodcast $playlistPodcasts
     * @return Playlist
     */
    public function addPlaylistPodcast(PlaylistPodcast $playlistPodcasts)
    {
        $this->playlistPodcasts[] = $playlistPodcasts;

        return $this;
    }

    /**
     * Remove playlistPodcasts
     *
     * @param PlaylistPodcast $playlistPodcasts
     */
    public function removePlaylistPodcast(PlaylistPodcast $playlistPodcasts)
    {
        $this->playlistPodcasts->removeElement($playlistPodcasts);
    }

    /**
     * Get playlistPodcasts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaylistPodcasts()
    {
        return $this->playlistPodcasts;
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
            'title' => $this->title,
        );
    }
}