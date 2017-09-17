<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Area.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Area
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
     * Fecha de creacion.
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="datetime")
     */
    private $fechaActualizacion;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Codigo")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $creadoPor;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Codigo")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $actualizadoPor;

    /**
     * @var ManyToOne
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Departamento")
     */
    private $departamento;

    public function __construct($nombre = '')
    {
        $this->nombre = $nombre;
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
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Area
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Area
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
     * Set fechaActualizacion.
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Area
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion.
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set creadoPor.
     *
     * @param \UserBundle\Entity\Codigo $creadoPor
     *
     * @return Area
     */
    public function setCreadoPor(\UserBundle\Entity\Codigo $creadoPor = null)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Get creadoPor.
     *
     * @return \UserBundle\Entity\Codigo
     */
    public function getCreadoPor()
    {
        return $this->creadoPor;
    }

    /**
     * Set actualizadoPor.
     *
     * @param \UserBundle\Entity\Codigo $actualizadoPor
     *
     * @return Area
     */
    public function setActualizadoPor(\UserBundle\Entity\Codigo $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Get actualizadoPor.
     *
     * @return \UserBundle\Entity\Codigo
     */
    public function getActualizadoPor()
    {
        return $this->actualizadoPor;
    }

    /**
     * Set departamento.
     *
     * @param \UserBundle\Entity\Departamento $departamento
     *
     * @return Area
     */
    public function setDepartamento(\UserBundle\Entity\Departamento $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento.
     *
     * @return \UserBundle\Entity\Departamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Object String representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->departamento.' : '.$this->nombre;
    }
}
