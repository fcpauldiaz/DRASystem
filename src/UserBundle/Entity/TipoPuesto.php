<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Permiso", inversedBy="tipoPuestos")
     * @ORM\JoinTable(name="permisos_por_tipo_puesto")
     */
    private $permisos;

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

    public function __toString()
    {
        return $this->nombrePuesto;
    }


    /**
     * Add permiso
     *
     * @param \UserBundle\Entity\Permiso $permiso
     *
     * @return TipoPuesto
     */
    public function addPermiso(\UserBundle\Entity\Permiso $permiso)
    {
        $this->permisos[] = $permiso;

        return $this;
    }

    /**
     * Remove permiso
     *
     * @param \UserBundle\Entity\Permiso $permiso
     */
    public function removePermiso(\UserBundle\Entity\Permiso $permiso)
    {
        $this->permisos->removeElement($permiso);
    }

    /**
     * Get permisos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisos()
    {
        return $this->permisos;
    }
}
