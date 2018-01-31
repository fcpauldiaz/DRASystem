<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoDescuento.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TipoDescuento
{
    //constante de permisos default
    const TIPOS = [
        'Corporación de Inversiones',
        'Comcel',
        'Otros descuentos',
        'Valens1',
        'Valens2',
        'Valens3',
    ];

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
     * @ORM\Column(name="nombre_tipo_descuento", type="string", length=255)
     */
    private $nombreTipoDescuento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    private $fechaCreacion;

    public function __construct($nombre)
    {
        $this->nombreTipoDescuento = $nombre;
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
     * Set nombreTipoDescuento.
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
     * Get nombreTipoDescuento.
     *
     * @return string
     */
    public function getNombreTipoDescuento()
    {
        return $this->nombreTipoDescuento;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return TipoDescuento
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

    public function __toString()
    {
        return $this->nombreTipoDescuento;
    }
}
