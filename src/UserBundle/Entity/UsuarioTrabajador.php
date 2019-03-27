<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as Unique;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="usuario_trabajador")
 * @UniqueEntity(fields = "username", targetClass = "UserBundle\Entity\Usuario", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "UserBundle\Entity\Usuario", message="fos_user.email.already_used")
 *
 * @UniqueEntity(fields = "nit", targetClass = "UserBundle\Entity\UsuarioTrabajador", message="El nit debe ser único")
 * @UniqueEntity(fields = "dpi", targetClass = "UserBundle\Entity\UsuarioTrabajador", message="El dpi debe ser único")
 * @Unique("codigo")
 * Esta entidad cubre los tipos de Asistente, Supervisor y Gerente.
 *
 * @ORM\Entity(repositoryClass="UsuarioTrabajadorRepository")
 *
 * @author  Pablo Díaz support@chapilabs.com
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
     * @var string
     * @ORM\Column(name="direccion",type="string",length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var date fecha de egreso de la empresa
     * @ORM\Column(name="fecha_egreso", type="date",nullable=true)
     */
    private $fechaEgreso;

    /**
     * @var string DPI del trabajador
     * @ORM\Column(name="dpi",type="string",length=20, unique=true, nullable=true)
     *
     * @Assert\Length(
     *      min = 13,
     *      max = 13
     * )
     */
    private $dpi;

    /**
     * Número de identificación tributaria.
     *
     * @var string
     * @ORM\Column(name="nit", type="string",length=20,unique=true, nullable=true)
     */
    private $nit;

    /**
     * @var int
     * @ORM\Column(name="telefono", type="string",length=15,nullable=true)
     */
    private $telefono;

    /**
     * Número de afiliación del igss.
     *
     * @var string
     * @ORM\Column(name="numero_afiliacion_igss", type="string", length=15, nullable=true)
     * @Assert\Length(
     *      min = 9,
     *      max = 13
     * )
     */
    private $numeroIgss;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="DatosPrestaciones", mappedBy="usuario")
     */
    private $datosPrestaciones;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Puesto", mappedBy="usuario")
     */
    private $puestos;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->datosPrestaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set direccion.
     *
     * @param string $direccion
     *
     * @return UsuarioTrabajador
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion.
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set fechaEgreso.
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
     * Get fechaEgreso.
     *
     * @return \DateTime
     */
    public function getFechaEgreso()
    {
        return $this->fechaEgreso;
    }

    /**
     * Set dpi.
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
     * Get dpi.
     *
     * @return string
     */
    public function getDpi()
    {
        return $this->dpi;
    }

    /**
     * Set nit.
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
     * Get nit.
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
     * @param string $telefono
     *
     * @return UsuarioTrabajador
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono.
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Add datosPrestacione.
     *
     * @param \UserBundle\Entity\DatosPrestaciones $datosPrestacione
     *
     * @return UsuarioTrabajador
     */
    public function addDatosPrestacione(\UserBundle\Entity\DatosPrestaciones $datosPrestacione)
    {
        $this->datosPrestaciones[] = $datosPrestacione;

        return $this;
    }

    /**
     * Remove datosPrestacione.
     *
     * @param \UserBundle\Entity\DatosPrestaciones $datosPrestacione
     */
    public function removeDatosPrestacione(\UserBundle\Entity\DatosPrestaciones $datosPrestacione)
    {
        $this->datosPrestaciones->removeElement($datosPrestacione);
    }

    /**
     * Get datosPrestaciones.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDatosPrestaciones()
    {
        return $this->datosPrestaciones;
    }

    /**
     * Add puesto.
     *
     * @param \UserBundle\Entity\Puesto $puesto
     *
     * @return UsuarioTrabajador
     */
    public function addPuesto(\UserBundle\Entity\Puesto $puesto)
    {
        $this->puestos[] = $puesto;

        return $this;
    }

    /**
     * Remove puesto.
     *
     * @param \UserBundle\Entity\Puesto $puesto
     */
    public function removePuesto(\UserBundle\Entity\Puesto $puesto)
    {
        $this->puestos->removeElement($puesto);
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

    /**
     * Get el último puesto ingresado.
     *
     * @return AppBundle\Entity\Puesto
     */
    public function getPuestoActual()
    {
        return $this->puestos->last();
    }

    public function getDatosPrestacionesActuales()
    {
        $datos = $this->getDatosPrestaciones()->last();
        if ($datos !== false) {
            return $datos;
        }

        return;
    }

    /**
     * Set numeroIgss.
     *
     * @param string $numeroIgss
     *
     * @return UsuarioTrabajador
     */
    public function setNumeroIgss($numeroIgss)
    {
        $this->numeroIgss = $numeroIgss;

        return $this;
    }

    /**
     * Get numeroIgss.
     *
     * @return string
     */
    public function getNumeroIgss()
    {
        return $this->numeroIgss;
    }

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
    }

    public function getCodigoString()
    {
        if (!empty($this->getPuestoActual())) {
            return $this->getPuestoActual()
                ->getTipoPuesto()
                ->getAbreviatura()
                .' '.
                $this->getCodigo().' : '.$this->__toString();
        }

        return $this->getCodigo().' : '.$this->__toString();
    }
}
