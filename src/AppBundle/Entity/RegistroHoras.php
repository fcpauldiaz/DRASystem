<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegistroHoras
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class RegistroHoras
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="horasInvertidas", type="integer")
     */
    private $horasInvertidas;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RegistroHoras
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set horasInvertidas
     *
     * @param integer $horasInvertidas
     *
     * @return RegistroHoras
     */
    public function setHorasInvertidas($horasInvertidas)
    {
        $this->horasInvertidas = $horasInvertidas;

        return $this;
    }

    /**
     * Get horasInvertidas
     *
     * @return integer
     */
    public function getHorasInvertidas()
    {
        return $this->horasInvertidas;
    }
}

