<?php
// src/Dahlberg/PodrBundle/Entity/PodcastDTO.php;
namespace Dahlberg\PodrBundle\Entity;

class PodcastDTO {
    private $id;
    private $title;
    private $rating;

    public function __construct($id, $title, $rating) {
        $this->id = $id;
        $this->title = $title;
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }
}