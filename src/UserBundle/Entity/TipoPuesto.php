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
     * @ORM\ManyToMany(targetEntity="TipoPuesto", mappedBy="puestos")
     */
    private $jerarquiaPuestos;

    /**
     * @ORM\ManyToMany(targetEntity="TipoPuesto", inversedBy="jerarquiaPuestos")
     */
    private $puestos;

    public function __construct()
    {
        $this->jerarquiaPuestos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->puestos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add jerarquiaPuesto.
     *
     * @param \UserBundle\Entity\TipoPuesto $jerarquiaPuesto
     *
     * @return TipoPuesto
     */
    public function addJerarquiaPuesto(\UserBundle\Entity\TipoPuesto $jerarquiaPuesto)
    {
        $this->jerarquiaPuestos[] = $jerarquiaPuesto;

        return $this;
    }

    /**
     * Remove jerarquiaPuesto.
     *
     * @param \UserBundle\Entity\TipoPuesto $jerarquiaPuesto
     */
    public function removeJerarquiaPuesto(\UserBundle\Entity\TipoPuesto $jerarquiaPuesto)
    {
        $this->jerarquiaPuestos->removeElement($jerarquiaPuesto);
    }

    /**
     * Get jerarquiaPuestos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJerarquiaPuestos()
    {
        return $this->jerarquiaPuestos;
    }

    /**
     * Add puesto.
     *
     * @param \UserBundle\Entity\TipoPuesto $puesto
     *
     * @return TipoPuesto
     */
    public function addPuesto(\UserBundle\Entity\TipoPuesto $puesto)
    {
        $this->puesto[] = $puesto;

        return $this;
    }

    /**
     * Remove puesto.
     *
     * @param \UserBundle\Entity\TipoPuesto $puesto
     */
    public function removePuesto(\UserBundle\Entity\TipoPuesto $puesto)
    {
        $this->puesto->removeElement($puesto);
    }

    /**
     * Get puestos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestos()
    {
        return $this->puestos;
    }
}
