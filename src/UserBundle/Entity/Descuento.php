<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Descuento.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Descuento
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
     * @ORM\Column(name="nombreDescuento", type="string", length=255)
     */
    private $nombreDescuento;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="float")
     */
    private $monto;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\DatosPrestaciones", inversedBy="descuentos")
     * 
     * @var [type]
     */
    private $prestaciones;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\TipoDescuento")
     * @var [type]
     */
    private $tipoDescuento;
    
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
     * Set nombreDescuento.
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
     * Get nombreDescuento.
     *
     * @return string
     */
    public function getNombreDescuento()
    {
        return $this->nombreDescuento;
    }

    /**
     * Set prestaciones.
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
     * Get prestaciones.
     *
     * @return \UserBundle\Entity\DatosPrestaciones
     */
    public function getPrestaciones()
    {
        return $this->prestaciones;
    }

    /**
     * Set tipoDescuento
     *
     * @param \UserBundle\Entity\TipoDescuento $tipoDescuento
     *
     * @return Descuento
     */
    public function setTipoDescuento(\UserBundle\Entity\TipoDescuento $tipoDescuento = null)
    {
        $this->tipoDescuento = $tipoDescuento;

        return $this;
    }

    /**
     * Get tipoDescuento
     *
     * @return \UserBundle\Entity\TipoDescuento
     */
    public function getTipoDescuento()
    {
        return $this->tipoDescuento;
    }

    /**
     * Set monto
     *
     * @param float $monto
     *
     * @return Descuento
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return float
     */
    public function getMonto()
    {
        return $this->monto;
    }

    public function __toString()
    {
        return $this->monto;
    }
}
