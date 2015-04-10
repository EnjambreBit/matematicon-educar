<?php

namespace PressEnter\MatematiconBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scene
 */
class Scene
{
    /**
     * @var integer
     */
    private $id;


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
     * @var string
     */
    private $title;


    /**
     * Set title
     *
     * @param string $title
     * @return Scene
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
}
