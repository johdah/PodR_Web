<?php
// src/Dahlberg/PodrBundle/Entity/Podcast.php
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dahlberg\PodrBundle\Entity\Episode;

/**
 * @ORM\Entity
 * @ORM\Table(name="podcasts")
 */
class Podcast implements \JsonSerializable {
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $copyright;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $feedurl;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $language;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(name="itunes_author", type="string", length=100, nullable=true)
     */
    private $itunesAuthor;

    /**
     * @ORM\Column(name="itunes_block", type="boolean")
     */
    private $itunesBlock = false;

    /**
     * @ORM\Column(name="itunes_complete", type="boolean")
     */
    private $itunesComplete = false;

    /**
     * @ORM\Column(name="itunes_explicit", type="boolean")
     */
    private $itunesExplicit = false;

    /**
     * @ORM\Column(name="itunes_image", type="string", nullable=true)
     */
    private $itunesImage;

    /**
     * @ORM\Column(name="itunes_owner_email", type="string", length=100, nullable=true)
     */
    private $itunesOwnerEmail;

    /**
     * @ORM\Column(name="itunes_owner_name", type="string", length=100, nullable=true)
     */
    private $itunesOwnerName;

    /**
     * @ORM\Column(name="itunes_subtitle", type="string", nullable=true)
     */
    private $itunesSubtitle;

    /**
     * @ORM\Column(name="itunes_summary", type="string", nullable=true)
     */
    private $itunesSummary;

    /**
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

    /**
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="podcast")
     * @ORM\OrderBy({"publishedDate" = "desc"})
     */
    private $episodes;

    /**
     * @ORM\OneToMany(targetEntity="UserPodcast", mappedBy="podcast")
     */
    private $userPodcasts;

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
     * Set copyright
     *
     * @param string $copyright
     * @return Podcast
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * Get copyright
     *
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Podcast
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set feedurl
     *
     * @param string $feedurl
     * @return Podcast
     */
    public function setFeedurl($feedurl)
    {
        $this->feedurl = $feedurl;

        return $this;
    }

    /**
     * Get feedurl
     *
     * @return string
     */
    public function getFeedurl()
    {
        return $this->feedurl;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Podcast
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set lastUpdated
     *
     * @param \DateTime $lastUpdated
     * @return Podcast
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return \DateTime
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Podcast
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Podcast
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
     * @return Podcast
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
     * @return Podcast
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
     * Set itunesComplete
     *
     * @param boolean $itunesComplete
     * @return Podcast
     */
    public function setItunesComplete($itunesComplete)
    {
        $this->itunesComplete = $itunesComplete;

        return $this;
    }

    /**
     * Get itunesComplete
     *
     * @return boolean
     */
    public function getItunesComplete()
    {
        return $this->itunesComplete;
    }

    /**
     * Set itunesExplicit
     *
     * @param boolean $itunesExplicit
     * @return Podcast
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
     * Set itunesImage
     *
     * @param string $itunesImage
     * @return Podcast
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
     * Set itunesOwnerEmail
     *
     * @param string $itunesOwnerEmail
     * @return Podcast
     */
    public function setItunesOwnerEmail($itunesOwnerEmail)
    {
        $this->itunesOwnerEmail = $itunesOwnerEmail;

        return $this;
    }

    /**
     * Get itunesOwnerEmail
     *
     * @return string
     */
    public function getItunesOwnerEmail()
    {
        return $this->itunesOwnerEmail;
    }

    /**
     * Set itunesOwnerName
     *
     * @param string $itunesOwnerName
     * @return Podcast
     */
    public function setItunesOwnerName($itunesOwnerName)
    {
        $this->itunesOwnerName = $itunesOwnerName;

        return $this;
    }

    /**
     * Get itunesOwnerName
     *
     * @return string
     */
    public function getItunesOwnerName()
    {
        return $this->itunesOwnerName;
    }

    /**
     * Set itunesSubtitle
     *
     * @param string $itunesSubtitle
     * @return Podcast
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
     * @return Podcast
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
     * @return Podcast
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
     * @return Podcast
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
     * Add episodes
     *
     * @param \Dahlberg\PodrBundle\Entity\Episode $episodes
     * @return Podcast
     */
    public function addEpisode(\Dahlberg\PodrBundle\Entity\Episode $episodes)
    {
        $this->episodes[] = $episodes;

        return $this;
    }

    /**
     * Remove episodes
     *
     * @param \Dahlberg\PodrBundle\Entity\Episode $episodes
     */
    public function removeEpisode(\Dahlberg\PodrBundle\Entity\Episode $episodes)
    {
        $this->episodes->removeElement($episodes);
    }

    /**
     * Get episodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpisodes()
    {
        return $this->episodes;
    }

    /**
     * Add userPodcasts
     *
     * @param \Dahlberg\PodrBundle\Entity\UserPodcast $userPodcasts
     * @return Podcast
     */
    public function addUserPodcast(\Dahlberg\PodrBundle\Entity\UserPodcast $userPodcasts)
    {
        $this->userPodcasts[] = $userPodcasts;

        return $this;
    }

    /**
     * Remove userPodcasts
     *
     * @param \Dahlberg\PodrBundle\Entity\UserPodcast $userPodcasts
     */
    public function removeUserPodcast(\Dahlberg\PodrBundle\Entity\UserPodcast $userPodcasts)
    {
        $this->userPodcasts->removeElement($userPodcasts);
    }

    /**
     * Get userPodcasts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserPodcasts()
    {
        return $this->userPodcasts;
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