<?php
// src/Dahlberg/PodrBundle/Entity/Episode.php
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dahlberg\PodrBundle\Entity\Podcast;

/**
 * @ORM\Entity
 * @ORM\Table(name="episodes")
 */
class Episode implements \JsonSerializable {
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $enclosureLength = -1;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $enclosureType;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $enclosureUrl;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    private $guid;

    /**
     * @ORM\Column(name="published_date", type="datetime", nullable=true)
     */
    private $publishedDate;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(name="itunes_Author", type="string", length=100, nullable=true)
     */
    private $itunesAuthor;

    /**
     * @ORM\Column(name="itunes_block", type="boolean")
     */
    private $itunesBlock = false;

    /**
     * @ORM\Column(name="itunes_duration", type="integer")
     */
    private $itunesDuration = -1;

    /**
     * @ORM\Column(name="itunes_explicit", type="boolean")
     */
    private $itunesExplicit = false;

    /**
     * @ORM\Column(name="itunes_isclosedcaption", type="boolean")
     */
    private $itunesIsClosedCaption = false;

    /**
     * @ORM\Column(name="itunes_image", type="string", nullable=true)
     */
    private $itunesImage;

    /**
     * @ORM\Column(name="itunes_subtitle", type="text", nullable=true)
     */
    private $itunesSubtitle;

    /**
     * @ORM\Column(name="itunes_summary", type="text", nullable=true)
     */
    private $itunesSummary;

    // ...
    /**
     * @ORM\ManyToOne(targetEntity="Podcast", inversedBy="episodes")
     * @ORM\JoinColumn(name="podcast_id", referencedColumnName="id")
     **/
    private $podcast;

    /**
     * @ORM\OneToMany(targetEntity="UserEpisode", mappedBy="episode")
     */
    private $userEpisodes;

    /**
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

    public function __construct() {
        $this->dateAdded = new \DateTime('NOW');
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
     * Set enclosureLength
     *
     * @param integer $enclosureLength
     * @return Episode
     */
    public function setEnclosureLength($enclosureLength)
    {
        $this->enclosureLength = $enclosureLength;

        return $this;
    }

    /**
     * Get enclosureLength
     *
     * @return integer
     */
    public function getEnclosureLength()
    {
        return $this->enclosureLength;
    }

    /**
     * Set enclosureType
     *
     * @param string $enclosureType
     * @return Episode
     */
    public function setEnclosureType($enclosureType)
    {
        $this->enclosureType = $enclosureType;

        return $this;
    }

    /**
     * Get enclosureType
     *
     * @return string
     */
    public function getEnclosureType()
    {
        return $this->enclosureType;
    }

    /**
     * Set enclosureUrl
     *
     * @param string $enclosureUrl
     * @return Episode
     */
    public function setEnclosureUrl($enclosureUrl)
    {
        $this->enclosureUrl = $enclosureUrl;

        return $this;
    }

    /**
     * Get enclosureUrl
     *
     * @return string
     */
    public function getEnclosureUrl()
    {
        return $this->enclosureUrl;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return Episode
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     * @return Episode
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get publishedDate
     *
     * @return \DateTime
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Episode
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
     * Set itunesAuthor
     *
     * @param string $itunesAuthor
     * @return Episode
     */
    public function setItunesAuthor($itunesAuthor)
    {
        $this->itunesAuthor = $itunesAuthor;

        return $this;
    }

    /**
     * Get itunesAuthor
     *
     * @return string
     */
    public function getItunesAuthor()
    {
        return $this->itunesAuthor;
    }

    /**
     * Set itunesBlock
     *
     * @param boolean $itunesBlock
     * @return Episode
     */
    public function setItunesBlock($itunesBlock)
    {
        $this->itunesBlock = $itunesBlock;

        return $this;
    }

    /**
     * Get itunesBlock
     *
     * @return boolean
     */
    public function getItunesBlock()
    {
        return $this->itunesBlock;
    }

    /**
     * Set itunesDuration
     *
     * @param integer $itunesDuration
     * @return Episode
     */
    public function setItunesDuration($itunesDuration)
    {
        $this->itunesDuration = $itunesDuration;

        return $this;
    }

    /**
     * Get itunesDuration
     *
     * @return integer
     */
    public function getItunesDuration()
    {
        return $this->itunesDuration;
    }

    /**
     * Set itunesExplicit
     *
     * @param boolean $itunesExplicit
     * @return Episode
     */
    public function setItunesExplicit($itunesExplicit)
    {
        $this->itunesExplicit = $itunesExplicit;

        return $this;
    }

    /**
     * Get itunesExplicit
     *
     * @return boolean
     */
    public function getItunesExplicit()
    {
        return $this->itunesExplicit;
    }

    /**
     * Set itunesIsClosedCaption
     *
     * @param boolean $itunesIsClosedCaption
     * @return Episode
     */
    public function setItunesIsClosedCaption($itunesIsClosedCaption)
    {
        $this->itunesIsClosedCaption = $itunesIsClosedCaption;

        return $this;
    }

    /**
     * Get itunesIsClosedCaption
     *
     * @return boolean
     */
    public function getItunesIsClosedCaption()
    {
        return $this->itunesIsClosedCaption;
    }

    /**
     * Set itunesImage
     *
     * @param string $itunesImage
     * @return Episode
     */
    public function setItunesImage($itunesImage)
    {
        $this->itunesImage = $itunesImage;

        return $this;
    }

    /**
     * Get itunesImage
     *
     * @return string
     */
    public function getItunesImage()
    {
        return $this->itunesImage;
    }

    /**
     * Set itunesSubtitle
     *
     * @param string $itunesSubtitle
     * @return Episode
     */
    public function setItunesSubtitle($itunesSubtitle)
    {
        $this->itunesSubtitle = $itunesSubtitle;

        return $this;
    }

    /**
     * Get itunesSubtitle
     *
     * @return string
     */
    public function getItunesSubtitle()
    {
        return $this->itunesSubtitle;
    }

    /**
     * Set itunesSummary
     *
     * @param string $itunesSummary
     * @return Episode
     */
    public function setItunesSummary($itunesSummary)
    {
        $this->itunesSummary = $itunesSummary;

        return $this;
    }

    /**
     * Get itunesSummary
     *
     * @return string
     */
    public function getItunesSummary()
    {
        return $this->itunesSummary;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Episode
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return Episode
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
     * @return Episode
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
     * Add userEpisodes
     *
     * @param \Dahlberg\PodrBundle\Entity\UserEpisode $userEpisodes
     * @return Episode
     */
    public function addUserEpisode(\Dahlberg\PodrBundle\Entity\UserEpisode $userEpisodes)
    {
        $this->userEpisodes[] = $userEpisodes;

        return $this;
    }

    /**
     * Remove userEpisodes
     *
     * @param \Dahlberg\PodrBundle\Entity\UserEpisode $userEpisodes
     */
    public function removeUserEpisode(\Dahlberg\PodrBundle\Entity\UserEpisode $userEpisodes)
    {
        $this->userEpisodes->removeElement($userEpisodes);
    }

    /**
     * Get userEpisodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserEpisodes()
    {
        return $this->userEpisodes;
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
            'title'             => $this->title,
            'enclosure_url'     => $this->enclosureUrl,
            'enclosure_type'    => $this->enclosureType,
            'podcast'           => $this->podcast,
        );
    }
}