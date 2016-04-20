<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario_socio")
 * @UniqueEntity(fields = "username", targetClass = "UserBundle\Entity\Usuario", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "UserBundle\Entity\Usuario", message="fos_user.email.already_used")
 *
 *  El usuario Socio no necesita guardar todas los campos de un usuario trabajador
 * Solo necesita los básicos de FOSUserBundle
 *
 * @author  Pablo Díaz soporte@newtonlabs.com.gt
 */
class UsuarioSocio extends Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ProyectoPresupuesto", mappedBy="socios")
     */
    private $presupuestos;

    public function __construct()
    {
        $this->presupuestos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add presupuesto.
     *
     * @param \AppBundle\Entity\ProyectoPresupuesto $presupuesto
     *
     * @return UsuarioSocio
     */
    public function addPresupuesto(\AppBundle\Entity\ProyectoPresupuesto $presupuesto)
    {
        $this->presupuestos[] = $presupuesto;

        return $this;
    }

    /**
     * Remove presupuesto.
     *
     * @param \AppBundle\Entity\ProyectoPresupuesto $presupuesto
     */
    public function removePresupuesto(\AppBundle\Entity\ProyectoPresupuesto $presupuesto)
    {
        $this->presupuestos->removeElement($presupuesto);
    }

    /**
     * Get presupuestos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresupuestos()
    {
        return $this->presupuestos;
    }

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
    }
}
