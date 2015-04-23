<?php

namespace PressEnter\MatematiconBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Denuncia
 */
class Denuncia
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $motivo;

    /**
     * @var \PressEnter\MatematiconBundle\Entity\SharedDrawing
     */
    private $shared_drawing;


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
     * Set motivo
     *
     * @param string $motivo
     * @return Denuncia
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set shared_drawing
     *
     * @param \PressEnter\MatematiconBundle\Entity\SharedDrawing $sharedDrawing
     * @return Denuncia
     */
    public function setSharedDrawing(\PressEnter\MatematiconBundle\Entity\SharedDrawing $sharedDrawing)
    {
        $this->shared_drawing = $sharedDrawing;

        return $this;
    }

    /**
     * Get shared_drawing
     *
     * @return \PressEnter\MatematiconBundle\Entity\SharedDrawing 
     */
    public function getSharedDrawing()
    {
        return $this->shared_drawing;
    }
    /**
     * @var \DateTime
     */
    private $fecha_hora;


    /**
     * Set fecha_hora
     *
     * @param \DateTime $fechaHora
     * @return Denuncia
     */
    public function setFechaHora($fechaHora)
    {
        $this->fecha_hora = $fechaHora;

        return $this;
    }

    /**
     * Get fecha_hora
     *
     * @return \DateTime 
     */
    public function getFechaHora()
    {
        return $this->fecha_hora;
    }
}
