<?php
// src/Dahlberg/PodrBundle/Entity/PodcastDTO.php;
namespace Dahlberg\PodrBundle\Entity;

class PodcastDTO {
    private $id;
    private $title;
    private $count;

    public function __construct($id, $title, $count) {
        $this->id = $id;
        $this->title = $title;
        $this->count = $count;
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
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }
}