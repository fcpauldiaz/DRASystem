<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * RegistroHorasPresupuesto.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RegistroHorasPresupuestoRepository")
 */
class RegistroHorasPresupuesto
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
     * @var int
     *
     * @ORM\Column(name="horas_presupuestadas", type="float")
     */
    private $horasPresupuestadas;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Area")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cliente")
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @var [type]
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\UsuarioTrabajador", cascade={"persist","remove"})
     */
    private $usuario;

    /**
     * [$proyecto description].
     *
     * @var [type]
     * @ORM\ManyToOne(targetEntity="ProyectoPresupuesto", inversedBy="presupuestoIndividual", cascade={"persist"})
     */
    private $proyecto;

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
        $this->fechaCreacion = new \DateTime();
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set horasPresupuestadas.
     *
     * @param int $horasPresupuestadas
     *
     * @return RegistroHoras
     */
    public function setHorasPresupuestadas($horasPresupuestadas)
    {
        $this->horasPresupuestadas = $horasPresupuestadas;

        return $this;
    }

    /**
     * Get horasPresupuestadas.
     *
     * @return int
     */
    public function getHorasPresupuestadas()
    {
        return $this->horasPresupuestadas;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return RegistroHoras
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
     * Set cliente.
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return RegistroHoras
     */
    public function setCliente(\AppBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente.
     *
     * @return \AppBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set proyecto.
     *
     * @param \AppBundle\Entity\ProyectoPresupuesto $proyecto
     *
     * @return RegistroHorasPresupuesto
     */
    public function setProyecto(\AppBundle\Entity\ProyectoPresupuesto $proyecto = null)
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto.
     *
     * @return \AppBundle\Entity\ProyectoPresupuesto
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }

    /**
     * Set fechaActualizacion.
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return RegistroHorasPresupuesto
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
     * @param string $creadoPor
     *
     * @return RegistroHorasPresupuesto
     */
    public function setCreadoPor($creadoPor)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Get creadoPor.
     *
     * @return string
     */
    public function getCreadoPor()
    {
        return $this->creadoPor;
    }

    /**
     * Set actualizadoPor.
     *
     * @param string $actualizadoPor
     *
     * @return RegistroHorasPresupuesto
     */
    public function setActualizadoPor($actualizadoPor)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Get actualizadoPor.
     *
     * @return string
     */
    public function getActualizadoPor()
    {
        return $this->actualizadoPor;
    }

    public function __toString()
    {
        return 'P'.$this->getId();
    }

    /**
     * Set area.
     *
     * @param \AppBundle\Entity\Area $area
     *
     * @return RegistroHorasPresupuesto
     */
    public function setArea(\AppBundle\Entity\Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area.
     *
     * @return \AppBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }

     /**
      * Set usuario.
      *
      * @param \UserBundle\Entity\Usuario $usuario
      *
      * @return RegistroHorasPresupuesto
      */
     public function setUsuario(\UserBundle\Entity\Usuario $usuario = null)
     {
         $this->usuario = $usuario;

         return $this;
     }

     /**
      * Get usuario.
      *
      * @return \UserBundle\Entity\Usuario
      */
     public function getUsuario()
     {
         return $this->usuario;
     }
}
