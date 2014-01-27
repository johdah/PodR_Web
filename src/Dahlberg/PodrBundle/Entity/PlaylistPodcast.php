<?php
// src/Dahlberg/PodrBundle/Entity/PlaylistPodcast.php
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="playlist_podcast")
 */
class PlaylistPodcast {
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Playlist", inversedBy="playlistPodcasts")
     * @ORM\JoinColumn(name="playlist_id", referencedColumnName="id")
     **/
    private $playlist;

    /**
     * @ORM\ManyToOne(targetEntity="Podcast", inversedBy="playlistPodcasts")
     * @ORM\JoinColumn(name="podcast_id", referencedColumnName="id")
     **/
    private $podcast;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return PlaylistPodcast
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
     * @return PlaylistPodcast
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
     * Set playlist
     *
     * @param Playlist $playlist
     * @return PlaylistPodcast
     */
    public function setPlaylist(Playlist $playlist = null)
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * Get playlist
     *
     * @return Playlist
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * Set podcast
     *
     * @param Podcast $podcast
     * @return PlaylistPodcast
     */
    public function setPodcast(Podcast $podcast = null)
    {
        $this->podcast = $podcast;

        return $this;
    }

    /**
     * Get podcast
     *
     * @return Podcast
     */
    public function getPodcast()
    {
        return $this->podcast;
    }
}