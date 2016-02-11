<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use UserBundle\Entity\Usuario;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario_trabajador")
 * @UniqueEntity(fields = "username", targetClass = "UserBundle\Entity\Usuario", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "UserBundle\Entity\Usuario", message="fos_user.email.already_used")
 */
class UsuarioTrabajador extends Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var  date fecha de ingreso a la empresa.
     * @ORM\Column(name="fechaIngreso", type="date")
     */
    protected $fechaIngreso;

    /**
     * @var  date fecha de egreso de la empresa.
     * @ORM\Column(name="fechaEgreso", type="date")
     */
    protected $fechaEgreso;



    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return UsuarioTrabajador
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set fechaEgreso
     *
     * @param \DateTime $fechaEgreso
     *
     * @return UsuarioTrabajador
     */
    public function setFechaEgreso($fechaEgreso)
    {
        $this->fechaEgreso = $fechaEgreso;

        return $this;
    }

    /**
     * Get fechaEgreso
     *
     * @return \DateTime
     */
    public function getFechaEgreso()
    {
        return $this->fechaEgreso;
    }

    /**
     * Set dpi
     *
     * @param string $dpi
     *
     * @return UsuarioTrabajador
     */
    public function setDpi($dpi)
    {
        $this->dpi = $dpi;

        return $this;
    }

    /**
     * Get dpi
     *
     * @return string
     */
    public function getDpi()
    {
        return $this->dpi;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return UsuarioTrabajador
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }
}
