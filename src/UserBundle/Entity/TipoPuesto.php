<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TipoPuesto
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("nombrePuesto")
 */
class TipoPuesto
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
     * @var string
     *
     * @ORM\Column(name="nombrePuesto", type="string", length=255)
     */
    private $nombrePuesto;


     /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255,nullable=true)
     */
    private $descripcion;


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
     * Set nombrePuesto
     *
     * @param string $nombrePuesto
     *
     * @return TipoPuesto
     */
    public function setNombrePuesto($nombrePuesto)
    {
        $this->nombrePuesto = $nombrePuesto;

        return $this;
    }

    /**
     * Get nombrePuesto
     *
     * @return string
     */
    public function getNombrePuesto()
    {
        return $this->nombrePuesto;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return TipoPuesto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function __toString()
    {
        return $this->nombrePuesto;
    }

    
}
