<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoDescuento
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TipoDescuento
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
     * @ORM\Column(name="nombre_tipo_descuento", type="string", length=255)
     */
    private $nombreTipoDescuento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    private $fechaCreacion;

   

    public function __construct()
    {
        $this->fechaCreacion = new \DateTime();
    }

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
     * Set nombreTipoDescuento
     *
     * @param string $nombreTipoDescuento
     *
     * @return TipoDescuento
     */
    public function setNombreTipoDescuento($nombreTipoDescuento)
    {
        $this->nombreTipoDescuento = $nombreTipoDescuento;

        return $this;
    }

    /**
     * Get nombreTipoDescuento
     *
     * @return string
     */
    public function getNombreTipoDescuento()
    {
        return $this->nombreTipoDescuento;
    }

    /**
     * Set fechaReacion
     *
     * @param \DateTime $fechaReacion
     *
     * @return TipoDescuento
     */
    public function setFechaReacion($fechaReacion)
    {
        $this->fechaReacion = $fechaReacion;

        return $this;
    }

    /**
     * Get fechaReacion
     *
     * @return \DateTime
     */
    public function getFechaReacion()
    {
        return $this->fechaReacion;
    }

    public function __toString()
    {
        return $this->nombreTipoDescuento;
    }
}

