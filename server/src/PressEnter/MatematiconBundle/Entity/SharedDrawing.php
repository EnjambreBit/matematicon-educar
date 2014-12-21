<?php

namespace PressEnter\MatematiconBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SharedDrawing
 */
class SharedDrawing
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $image;

    /**
     * @var \PressEnter\MatematiconBundle\Entity\Drawing
     */
    private $drawing;


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
     * Set image
     *
     * @param string $image
     * @return SharedDrawing
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set drawing
     *
     * @param \PressEnter\MatematiconBundle\Entity\Drawing $drawing
     * @return SharedDrawing
     */
    public function setDrawing(\PressEnter\MatematiconBundle\Entity\Drawing $drawing)
    {
        $this->drawing = $drawing;

        return $this;
    }

    /**
     * Get drawing
     *
     * @return \PressEnter\MatematiconBundle\Entity\Drawing 
     */
    public function getDrawing()
    {
        return $this->drawing;
    }
}
