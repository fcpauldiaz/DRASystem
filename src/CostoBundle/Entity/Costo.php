<?php

namespace CostoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Costo.
 *
 * @ORM\Table()
 * @UniqueEntity(fields={"fechaInicio", "fechaFinal","usuario"},
 *     message = "No se puede calcular el costo para un mismo período de un mismo usuario"
 * 
 * )
 * @ORM\Entity(repositoryClass="CostoRepository")
 */
class Costo
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
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInicio", type="date")
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaFinal", type="date")
     */
    private $fechaFinal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCreacion", type="datetime")
     */
    private $fechaCreacion;

    /**
     * @var float
     *
     * @ORM\Column(name="costo", type="float")
     */
    private $costo;

    /**
     * @var UsuarioTrabajador
     * 
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\UsuarioTrabajador")
     */
    private $usuario;

    public function __construct()
    {
        $this->fechaCreacion = new \DateTime();
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
     * Set fechaInicio.
     *
     * @param \DateTime $fechaInicio
     *
     * @return Costo
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio.
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFinal.
     *
     * @param \DateTime $fechaFinal
     *
     * @return Costo
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * Get fechaFinal.
     *
     * @return \DateTime
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Costo
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion.
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set costo.
     *
     * @param float $costo
     *
     * @return Costo
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo.
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set usuario.
     *
     * @param \UserBundle\Entity\UsuarioTrabajador $usuario
     *
     * @return Costo
     */
    public function setUsuario(\UserBundle\Entity\UsuarioTrabajador $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \UserBundle\Entity\UsuarioTrabajador
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    public function __toString()
    {
        $this->id;
    }
}
