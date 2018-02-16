<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TipoPuesto.
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("nombrePuesto")
 */
class TipoPuesto
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
     * @ORM\Column(name="nombre_puesto", type="string", length=255)
     */
    private $nombrePuesto;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255,nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="abreviatura", type="string", nullable=true)
     *
     * @var [type]
     */
    private $abreviatura;

    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Permiso", inversedBy="tipoPuestos")
     * @ORM\JoinTable(name="permisos_por_tipo_puesto")
     */
    private $permisos;

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

    public function __construct()
    {
        $this->permisos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombrePuesto.
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
     * Get nombrePuesto.
     *
     * @return string
     */
    public function getNombrePuesto()
    {
        return $this->nombrePuesto;
    }

    /**
     * Set descripcion.
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
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Add permiso.
     *
     * @param \UserBundle\Entity\Permiso $permiso
     *
     * @return TipoPuesto
     */
    public function addPermiso(\UserBundle\Entity\Permiso $permiso)
    {
        $this->permisos[] = $permiso;
        $permiso->addTipoPuesto($this);

        return $this;
    }

    /**
     * Remove permiso.
     *
     * @param \UserBundle\Entity\Permiso $permiso
     */
    public function removePermiso(\UserBundle\Entity\Permiso $permiso)
    {
        $this->permisos->removeElement($permiso);
        $permiso->removeTipoPuesto($this);
    }

    /**
     * Get permisos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisos()
    {
        return $this->permisos;
    }

    /**
     * Set abreviatura.
     *
     * @param string $abreviatura
     *
     * @return TipoPuesto
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
        if ($this->abreviatura !== null) {
            return $this->abreviatura;
        }

        return '';
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return TipoPuesto
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
     * @return TipoPuesto
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
     * @param \UserBundle\Entity\Usuario $creadoPor
     *
     * @return TipoPuesto
     */
    public function setCreadoPor(\UserBundle\Entity\Usuario $creadoPor = null)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Get creadoPor.
     *
     * @return \UserBundle\Entity\Usuario
     */
    public function getCreadoPor()
    {
        return $this->creadoPor;
    }

    /**
     * Set actualizadoPor.
     *
     * @param \UserBundle\Entity\Usuario $actualizadoPor
     *
     * @return TipoPuesto
     */
    public function setActualizadoPor(\UserBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Get actualizadoPor.
     *
     * @return \UserBundle\Entity\Usuario
     */
    public function getActualizadoPor()
    {
        return $this->actualizadoPor;
    }
    /**
     * Return significant identifier of class.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nombrePuesto;
    }
}
