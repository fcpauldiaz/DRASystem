<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Actividad.
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("nombre")
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="abreviatura", type="string", length=255, nullable=true)
     */
    private $abreviatura;

    /**
     * @var bool
     *
     * @ORM\Column(name="horaNoCargable", type="boolean")
     */
    private $horaNoCargable;

    public function __construct()
    {
        $this->horaNoCargable = false;
    }
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
    public function setNombre($nombreActividad)
    {
        $this->nombre = $nombreActividad;

        return $this;
    }

    /**
     * Get nombreActividad.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion.
     *
     * @param string $descripcion
     *
     * @return Actividad
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
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
        $abreviatura = $this->abreviatura;
        if ($abreviatura != null) {
            return $this->abreviatura.': '.$this->nombre;
        }

        return $this->nombre;
    }

    /**
     * Set horaNoCargable.
     *
     * @param bool $horaNoCargable
     *
     * @return Actividad
     */
    public function setHoraNoCargable($horaNoCargable)
    {
        $this->horaNoCargable = $horaNoCargable;

        return $this;
    }

    /**
     * Get horaNoCargable.
     *
     * @return bool
     */
    public function getHoraNoCargable()
    {
        return $this->horaNoCargable;
    }
}
