<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProyectoPresupuesto
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProyectoPresupuesto
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
     * @ORM\Column(name="nombrePresupuesto", type="string")
     */
    private $nombrePresupuesto;


    // ...
    /**
     * @ORM\OneToMany(targetEntity="RegistroHorasPresupuesto", mappedBy="proyecto" ,cascade={"persist"})
     */
    private $presupuestoIndividual;

     public function __construct() {
        $this->presupuestoIndividual = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add presupuestoIndividual
     *
     * @param \AppBundle\Entity\RegistroHorasPresupuesto $presupuestoIndividual
     *
     * @return ProyectoPresupuesto
     */
    public function addPresupuestoIndividual(\AppBundle\Entity\RegistroHorasPresupuesto $presupuestoIndividual)
    {
        $this->presupuestoIndividual[] = $presupuestoIndividual;
        $presupuestoIndividual->setProyecto($this); //esta línea es muy importante para que se guarda la relación

        return $this;
    }

    /**
     * Remove presupuestoIndividual
     *
     * @param \AppBundle\Entity\RegistroHorasPresupuesto $presupuestoIndividual
     */
    public function removePresupuestoIndividual(\AppBundle\Entity\RegistroHorasPresupuesto $presupuestoIndividual)
    {
        $this->presupuestoIndividual->removeElement($presupuestoIndividual);
    }

    /**
     * Get presupuestoIndividual
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresupuestoIndividual()
    {
        return $this->presupuestoIndividual;
    }

    /**
     * Set nombrePresupuesto
     *
     * @param string $nombrePresupuesto
     *
     * @return ProyectoPresupuesto
     */
    public function setNombrePresupuesto($nombrePresupuesto)
    {
        $this->nombrePresupuesto = $nombrePresupuesto;

        return $this;
    }

    /**
     * Get nombrePresupuesto
     *
     * @return string
     */
    public function getNombrePresupuesto()
    {
        return $this->nombrePresupuesto;
    }

    public function __toString()
    {
        return $this->nombrePresupuesto;
    }
}
