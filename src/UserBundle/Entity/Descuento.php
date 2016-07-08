<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Descuento
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Descuento
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
     * @ORM\Column(name="nombreDescuento", type="string", length=255)
     */
    private $nombreDescuento;

    /**
     * @var float
     *
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\DatosPrestaciones", inversedBy="descuentos")
     * 
     * 
     * @var [type]
     */
    private $prestaciones;

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
     * Set nombreDescuento
     *
     * @param string $nombreDescuento
     *
     * @return Descuento
     */
    public function setNombreDescuento($nombreDescuento)
    {
        $this->nombreDescuento = $nombreDescuento;

        return $this;
    }

    /**
     * Get nombreDescuento
     *
     * @return string
     */
    public function getNombreDescuento()
    {
        return $this->nombreDescuento;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return Descuento
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set prestaciones
     *
     * @param \UserBundle\Entity\DatosPrestaciones $prestaciones
     *
     * @return Descuento
     */
    public function setPrestaciones(\UserBundle\Entity\DatosPrestaciones $prestaciones = null)
    {
        $this->prestaciones = $prestaciones;

        return $this;
    }

    /**
     * Get prestaciones
     *
     * @return \UserBundle\Entity\DatosPrestaciones
     */
    public function getPrestaciones()
    {
        return $this->prestaciones;
    }
}
