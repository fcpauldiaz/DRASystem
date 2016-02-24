<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actividad.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Actividad
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreActividad", type="string", length=255)
     */
    private $nombreActividad;

    /**
     * @var string
     *
     * @ORM\Column(name="abreviatura", type="string", length=255)
     */
    private $abreviatura;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombreActividad.
     *
     * @param string $nombreActividad
     *
     * @return Actividad
     */
    public function setNombreActividad($nombreActividad)
    {
        $this->nombreActividad = $nombreActividad;

        return $this;
    }

    /**
     * Get nombreActividad.
     *
     * @return string
     */
    public function getNombreActividad()
    {
        return $this->nombreActividad;
    }

    /**
     * Set abreviatura.
     *
     * @param string $abreviatura
     *
     * @return Actividad
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura.
     *
     * @return string
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    public function __toString()
    {
        return $this->abreviatura.': '.$this->nombreActividad;
    }
}
