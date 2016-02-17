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
 * Esta entidad cubre los tipos de Asistente, Supervisor y Gerente.
 *
 * @author  Pablo Díaz soporte@newtonlabs.com.gt
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
     * @ORM\Column(name="fechaEgreso", type="date",nullable=true)
     */
    protected $fechaEgreso;

    /**
     * @var  string DPI del trabajador
     * @ORM\Column(name="dpi",type="string",length=20, unique=true)
     */
    protected $dpi;

    /**
     * Número de identificación tributaria
     * @var  string 
     * @ORM\Column(name="nit", type="string",length=20,unique=true)
     */
    protected $nit;

    /**
     * @var int
     * @ORM\Column(name="telefono", type="string",length=15,nullable=true)
     */
    protected $telefono;


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
     * @return Usuario
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
     * @return Usuario
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

        /**
     * Set telefono.
     *
     * @param int $telefono
     *
     * @return Usuario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono.
     *
     * @return int
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
    }
}
